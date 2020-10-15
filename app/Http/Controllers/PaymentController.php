<?php

namespace App\Http\Controllers;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use App\Traits\ApiResponser;
use App\Trip;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PayPal\Api\PaymentExecution;

class PaymentController extends Controller
{
    use ApiResponser;

    /**
     * Undocumented function
     */
    public function __construct()
    {
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function createPayment(Request $request)
    {
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
     * @param Request $request
     * @return void
     */
    public function executePayment(Request $request)
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

        DB::table('customer_trip')->where('customer_id','=',$request->customer)->where('trip_id','=',$request->trip)->paid=true;

        return $result;
    }


}
