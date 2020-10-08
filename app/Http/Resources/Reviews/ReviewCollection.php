<?php


namespace App\Http\Resources\Reviews;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
           'trip'=>$this->trip->title,
           'rating'=>$this->rating,
           'href'=>[
               'link'=>url('/api/'.$this->trip->id.'/reviews',$this->id)
           ]
        ];
    }
}