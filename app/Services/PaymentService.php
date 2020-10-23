<?php

namespace App\Services;

use App\Customer;
use App\Mail\AgencyPaymentMailable;
use App\Mail\CustomerPaymentMailable;
use App\Services\ServiceInterface;
use App\Traits\ApiResponser;
use App\Trip;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

class PaymentService implements ServiceInterface
{

    use ApiResponser;

    /**
     * Undocumented function
     *
     * @param [type] $data
     * @return void
     */
    public function sendMail($data,$reason)
    {

        return;
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @return void
     */
    public function create($request)
    {
        $paid=DB::table('customer_trip')->where('customer_id',auth()->user()->customer->id)->where('trip_id',$request->trip)->paid;
        if($paid=NULL){
            return $this->errorResponse('You have already paid for this trip',403);
        }
        $trip = Trip::findOrFail($request->trip);

        if (!auth()->user()->customer->registeredForTrip($request->trip)) {

            return $this->errorResponse('You are not registered for this trip', 403);
        }

        $apiContext = new \PayPal\Rest\ApiContext(
            new OAuthTokenCredential(
                config('payment.paypal_public'),
                config('payment.paypal_secret')
            )
        );

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");


        $item1 = new Item();
        $item1->setName($trip->title)
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setSku($trip->id) // Similar to `item_number` in Classic API
            ->setPrice($trip->price);

        $itemList = new ItemList();
        $itemList->setItems(array($item1));


        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($trip->price);


        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment for trip")
            ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(url('/api/trips'))
            ->setCancelUrl(url('/api/trips'));


        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));


        try {
            $payment->create($apiContext);
        } catch (Exception $ex) {

            return $this->errorResponse($ex->getMessage(), 403);
        }

        return $payment;
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @return void
     */
    public function execute($request)
    {

        $apiContext = new \PayPal\Rest\ApiContext(
            new OAuthTokenCredential(
                config('payment.paypal_public'),
                config('payment.paypal_secret')
            )
        );

        $paymentId = $request->paymentID;
        $payment = Payment::get($paymentId, $apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($request->payerID);

        try {
            $result = $payment->execute($execution, $apiContext);
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 403);
        }

        DB::table('customer_trip')->where('customer_id', '=', $request->customer)->where('trip_id', '=', $request->trip)->paid = true;

        $trip=Trip::findOrFail($request->trip);
        $customer=Customer::findOrFail($request->customer);
        $agency=$trip->agency;

        $pdf = app('dompdf.wrapper')->loadView('invoice', ['trip' => $trip,'customer'=>$customer,'agency'=>$agency]);
        $invoice_number=DB::table('customer_trip')->where('customer_id',$request->customer)->where('trip_id',$request->trip)->first()->id;
        $content = $pdf->download()->getOriginalContent();
        Storage::put('invoices/invoice'.$request->customer.'.pdf',$content);


        Mail::to($customer->user->email)->send(new CustomerPaymentMailable($agency,$trip,$invoice_number));
        Mail::to($trip->agency->user->email)->send(new AgencyPaymentMailable($customer,$trip,$invoice_number));

        return $result;
    }
}
