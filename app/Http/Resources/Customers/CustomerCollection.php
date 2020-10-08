<?php


namespace App\Http\Resources\Customers;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
           'name'=>$this->name,
           'surname'=>$this->surname,
           'href'=>[
               'link'=>url('/api/customers',$this->id)
           ]
        ];
    }
}