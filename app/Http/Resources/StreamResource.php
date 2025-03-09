<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StreamResource extends JsonResource
{

    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description ?? null,
            'tokens_price' => $this->tokens_price,
            'type_id' => $this->type ?? null,
            'date_expiration' => $this->date_expiration,
        ];
        
    }
}
