<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'orders' => OrderResource::collection($this->orders()->get()),
            'total_orders' => $this->total_orders,
            'created_at' => $this->created_at->format(config('app.date_format')),
            'updated_at' => $this->updated_at->format(config('app.date_format'))
        ];
    }
}
