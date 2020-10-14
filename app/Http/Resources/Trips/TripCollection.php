<?php


namespace App\Http\Resources\Trips;

use Illuminate\Http\Resources\Json\JsonResource;

class TripCollection extends JsonResource
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
            'title' => $this->title,
            'destination' => $this->destination,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'price' => $this->price,
            'href' => [
                'link' => url('/api/trips', $this->id)
            ]
        ];
    }
}
