<?php


namespace App\Http\Resources\Agencies;

use Illuminate\Http\Resources\Json\JsonResource;

class AgencyCollection extends JsonResource
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
            'company_name' => $this->company_name,
            'address' => $this->address,
            'email' => $this->user->email,
            'web' => $this->web,
            'phone_number' => $this->user->phone_number

        ];
    }
}
