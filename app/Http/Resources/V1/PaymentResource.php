<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this['order_id'],
            'snap_token' => $this['snap_token'],
            'redirect_url' => $this['redirect_url'],
        ];
    }
}