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
use Exception;
use Illuminate\Http\Request;
use PayPal\Api\PaymentExecution;

class PaymentController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
    }


    public function createPayment()
    {


        $apiContext = new \PayPal\Rest\ApiContext(
            new OAuthTokenCredential(
                'AViakS56MjelJOW0VHU0nzbYcHs4AravkiLnpaiwyDV9lmWO56e9EeXyzpBeYxRi5Gq0-MSqUad8hXBq',
                'EIRiX0eSKYG9UUC-un5FPR2EbojP9r94783kD2cpwZCuArzgOgABIjUOurxNbnJjfYMjpJ1IicGcNLFR'
            )
        );

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");


        $item1 = new Item();
        $item1->setName('')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setSku("123123") // Similar to `item_number` in Classic API
            ->setPrice(7.5);
        $item2 = new Item();
        $item2->setName('Granola bars')
            ->setCurrency('USD')
            ->setQuantity(5)
            ->setSku("321321") // Similar to `item_number` in Classic API
            ->setPrice(2);

        $itemList = new ItemList();
        $itemList->setItems(array($item1, $item2));

        $details = new Details();
        $details->setShipping(1.2)
            ->setTax(1.3)
            ->setSubtotal(17.50);

        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal(20)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
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
    public function execute_payment(Request $request)
    {

        $apiContext = new \PayPal\Rest\ApiContext(
            new OAuthTokenCredential(
                'AViakS56MjelJOW0VHU0nzbYcHs4AravkiLnpaiwyDV9lmWO56e9EeXyzpBeYxRi5Gq0-MSqUad8hXBq',
                'EIRiX0eSKYG9UUC-un5FPR2EbojP9r94783kD2cpwZCuArzgOgABIjUOurxNbnJjfYMjpJ1IicGcNLFR'
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


        return $result;
    }

    public function test()
    {

        return view('test');
    }
}
