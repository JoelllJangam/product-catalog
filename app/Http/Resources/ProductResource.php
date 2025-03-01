<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'name' => $this->name ?? 'N/A',
        'description' => $this->description ?? 'No description available',
        'sku' => $this->sku ?? 'N/A',
        'price' => $this->price ?? 0.00,
        'category_id' => $this->category_id ?? null,
        'created_at' => optional($this->created_at)->format('Y-m-d H:i:s') ?? null,
        'updated_at' => optional($this->updated_at)->format('Y-m-d H:i:s') ?? null,
    ];
}

}
