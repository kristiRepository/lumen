<?php


namespace App\Http\Resources\Reviews;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewCollection extends JsonResource
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
            'href' => [
                'link' => url('/api/' . $this->trip->id . '/reviews', $this->id)
            ]
        ];
    }
}
