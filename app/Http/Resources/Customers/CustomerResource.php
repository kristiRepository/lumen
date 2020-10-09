<?php



namespace App\Http\Resources\Customers;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name'=>$this->name,
            'surname'=>$this->surname,
            'email'=>$this->user->email,
            'age'=>$this->age,
            'gender'=>$this->gender,
            'username'=>$this->user->username,
            'phone_number'=>$this->user->phone_number
            
         ];
    }
}