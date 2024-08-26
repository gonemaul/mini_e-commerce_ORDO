<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product_image = $this->productImage->map(function ($image) {
                return url('storage/' . $image->image);
        });


        return [
            'id' => $this->id,
            __('product.name') => $this->name,
            __('general.category') => $this->category->name,
            __('general.price') => $this->price,
            __('general.stock') => $this->stock,
            __('general.descriptions') => $this->description,
            __('general.image') => $product_image
        ];
    }
}