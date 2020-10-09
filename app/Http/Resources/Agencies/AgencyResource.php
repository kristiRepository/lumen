<?php



namespace App\Http\Resources\Agencies;

use Illuminate\Http\Resources\Json\JsonResource;

class AgencyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'company_name'=>$this->company_name,
            'address'=>$this->address,
            'email'=>$this->user->email,
            'web'=>$this->web,
            'username'=>$this->user->username,
            'phone_number'=>$this->user->phone_number

            
         ];
    }
}