<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CivilianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'mobile' => $this->mobile,
            'token' => $this->token_name,
            'NID' => $this->NID,
            'img' => $this->img,
            'type' => 'Civilian'
        ];
    }
}
