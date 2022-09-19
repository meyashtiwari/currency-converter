<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'symbol' => $this->symbol,
            'rate' => $this->rate,
        ];
    }
}
