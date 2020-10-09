<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Http\Resources\Agencies\AgencyCollection;
use App\Http\Resources\Agencies\AgencyResource;
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

    public function numberOfParticipantsOnTrip($trip){
        $agency=Auth::user();
        dd($agency);
    }

    public function getPreviousTripsReports(){

        $trips=[];
        foreach(Auth::user()->agency->trips as $trip){
            $trips[]=$trip->title;
        }

        $total_earnings=0;
        foreach(Auth::user()->agency->trips as $trip){
            $total_earnings=$total_earnings + $trip->price;
        }
        
        
    

    }

   

    
}
