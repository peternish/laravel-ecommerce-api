<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'status' => $this->status == 0 ? 'open' : 'closed',
            'user_id' => $this->user_id,
            'value' => round($this->value/100, 2),
            'created_at' => $this->created_at->format(config('app.date_format')),
            'updated_at' => $this->updated_at->format(config('app.date_format'))
        ];
    }
}
