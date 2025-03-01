<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function getProducts($limit = 10, $categoryId = null)
    {
        $query = Product::query();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query->paginate($limit);
    }

    public function findById($id)
    {
        return Product::find($id);
    }

    public function createProduct(array $data)
    {
        return Product::createProduct($data);
    }

    public function updateProduct(Product $product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function deleteProduct(Product $product)
    {
        return $product->delete();
    }


}
