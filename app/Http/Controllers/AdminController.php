<?php

namespace App\Http\Controllers;

use App\Repositories\AdminRepository;
use App\Review;
use App\Services\AdminService;
use App\Services\AuthService;
use Illuminate\Http\Request;


class AdminController extends Controller
{

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $adminService;
    private $adminRepo;
    private $authService;
    


    /**
     * Undocumented function
     *
     * @param AdminService $adminService
     * @param AdminRepository $adminRepo
     */
    public function __construct(AdminService $adminService,AdminRepository $adminRepo,AuthService $authService)
    {

       $this->adminService=$adminService;
       $this->adminRepo=$adminRepo;
       $this->authService=$authService;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function allReviews(){

        return $this->adminRepo->allReviews();
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param [type] $review
     * @return void
     */
    public function deleteInappropriateReviews(Request $request,$review){

        
        $review=Review::findOrFail($review);
        
        $this->adminService->sendMail($review->customer->user->email,$request->reason);
        $review->delete();
        
    }

    public function loginAsUser(Request $request){

        return $this->authService->login($request);

    }


}