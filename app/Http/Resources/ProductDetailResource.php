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
            'product_name' => $this->name,
            'category' => $this->category->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'description' => $this->description,
            'images' => $product_image
        ];
    }
}
