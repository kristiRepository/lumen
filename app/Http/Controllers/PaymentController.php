<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    use ApiResponser;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $paymentService;

    /**
     * Undocumented function
     *
     * @param PaymentService $paymentService
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService=$paymentService;
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function createPayment(Request $request)
    {
        
        return $this->paymentService->create($request);
        
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function executePayment(Request $request)
    {
        return $this->paymentService->execute($request);
        
        
    }


}
