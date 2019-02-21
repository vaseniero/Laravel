<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamineeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /**
         * comment out
         * return parent::toArray($request);
        */
        return [
            'id'        => $this->id,
            'examinee'  => $this->name_of_examinee,
            'campus'    => $this->campus_eligibility,
            'school'    => $this->school,
            'division'  => $this->division
        ];
    }
}
