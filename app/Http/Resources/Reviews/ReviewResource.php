<?php


namespace App\Http\Resources\Reviews;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Undocumented function
     *
     * @param [type] $request
     * @return void
     */
    public function toArray($request)
    {
        return [
            'trip' => $this->trip->title,
            'rating' => $this->rating,
            'body' => $this->body,

        ];
    }
}
