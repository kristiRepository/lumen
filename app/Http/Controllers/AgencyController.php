<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Http\Resources\Agencies\AgencyCollection;
use App\Http\Resources\Agencies\AgencyResource;
use App\Jobs\SendOfferJob;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AgencyController extends Controller
{
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function index(){

        return AgencyCollection::collection(Agency::paginate(10));

    }



    public function profile($agency){

        $agency = Agency::findOrFail($agency);


        if (Gate::allows('agency-profile', $agency)) {
            return $this->successResponse(new AgencyResource($agency));
        } else {
            return $this->errorResponse('You have not access to this data', 401);
        }
        
    }

    public function update(Request $request,$agency){
       
        $agency=Agency::findOrFail($agency);
        
        if (Gate::allows('agency-profile', $agency)) {
            $this->validate($request,$agency->user->updateRulesAgency);
            $agency->fill($request->all());

            if($agency->isClean()){
                return $this->errorResponse('At least one value must change',Response::HTTP_UNPROCESSABLE_ENTITY);
            }
    
            $agency->save();
    
            return $this->successResponse($agency);

        }else{
            return $this->errorResponse('You have not access to this data', 401);
        }
       


    }

    public function destroy($agency){

        $agency = Agency::findOrFail($agency);
        if (Gate::allows('agency-profile', $agency)) {
            
            $agency->user->delete();
            $agency->delete();

            return $this->successResponse($agency);
        } else {
            return $this->errorResponse('You have not access to this data', 401);
        }


    }



    public function getPreviousTripsReports(){

        $data=Auth::user()->agency->trips()->where('start_date','<',date('Y-m-d'))->get();
        
        $trips=[];
        foreach($data as $trip){
            $trips[]=$trip->title;
        } 

        $ratings=[];
        foreach($data as $trip){

           $sum=0;
           if($trip->reviews->count()>0){
           foreach($trip->reviews as $review){
               $sum=$sum+$review->rating;
           }
           $rating_per_trip=$sum/$trip->reviews->count();
        
           $ratings[$trip->title]=$rating_per_trip;}
           else{
            $ratings[$trip->title]='This trip has no ratings yet';
           }
        }


        $participants_per_trip=[];

        foreach($data as $trip){
           
            $participants_per_trip[$trip->title]=$trip->numberOfParticipantsOnTrip($trip->id);
        }

        $total_participants=0;
        foreach($data as $trip){
            $total_participants=$total_participants+$trip->numberOfParticipantsOnTrip($trip->id);
        }

        $earnings_per_trip=[];
        foreach($data as $trip){
            $earnings_per_trip[$trip->title]=$trip->numberOfParticipantsOnTrip($trip->id)*$trip->price;
        }

       
        $total_earnings=0;
        foreach($data as $trip){
            $total_earnings=$total_earnings + $trip->numberOfParticipantsOnTrip($trip->id)*$trip->price;
        }

        $total_cost=0;
        foreach($data as $trip){
            $total_cost=$total_cost + $trip->cost;
        }

        $profit=$total_earnings-$total_cost;

        $report=[
            'my_trips'=>$trips,
            'participants_per_trip'=>$participants_per_trip,
            'total_participants'=>$total_participants,
            'ratings_per_trip'=>$ratings,
            'earnings_per_trip'=>$earnings_per_trip,
            'total_earnings'=>$total_earnings,
            'total_cost'=>$total_cost,
            'profit'=>$profit
        ];

        return $this->successResponse($report,200);
       

    }

    public function sendOffers(Request $request){

        
        $this->dispatch(new SendOfferJob($request->all()));
        
       
    }

   

    
}
