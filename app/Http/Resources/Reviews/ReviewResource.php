<?php


namespace App\Http\Resources\Reviews;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'trip'=>$this->trip->title,
            'rating'=>$this->rating,
            'body'=>$this->body,
            
         ];
    }
}