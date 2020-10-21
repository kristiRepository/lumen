<?php

namespace App\Http\Controllers;

use App\Repositories\AdminRepository;
use App\Review;
use App\Services\AdminService;
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
    



  
    public function __construct(AdminService $adminService,AdminRepository $adminRepo)
    {

       $this->adminService=$adminService;
       $this->adminRepo=$adminRepo;
    }

    public function allReviews(){

        return $this->adminRepo->allReviews();
    }

    public function deleteInappropriateReviews(Request $request,$review){

        
        $review=Review::findOrFail($review);
        

        $this->adminService->sendMail($review->customer->user->email,$request->reason);
        $review->delete();
        

    }




}