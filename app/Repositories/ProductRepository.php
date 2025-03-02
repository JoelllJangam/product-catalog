<?php

namespace App\Repositories;

use App\BusinessObjects\ProductBO;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    public function getProducts(int $perPage, ?int $categoryId): array
    {
        $query = DB::table('products');
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        $products = $query->paginate($perPage)->items();
        return array_map(fn($product) => $this->toBO($product), $products);
    }

    public function getProductById(int $id): ?ProductBO
    {
        $product = DB::table('products')->where('id', $id)->first();
        return $product ? $this->toBO($product) : null;
    }

    public function createProduct(ProductBO $productBO): ProductBO
    {
        $existingSku = DB::table('products')->where('sku', $productBO->sku)->exists();
        if ($existingSku) {
            throw new \Exception("SKU '{$productBO->sku}' already exists.");
        }

        $id = DB::table('products')->insertGetId([
            'name' => $productBO->name,
            'description' => $productBO->description,
            'sku' => $productBO->sku,
            'price' => $productBO->price,
            'category_id' => $productBO->categoryId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $this->getProductById($id);
    }

    public function updateProduct(int $id, ProductBO $productBO): ProductBO
    {

        $existingProduct = DB::table('products')
            ->where('sku', $productBO->sku)
            ->where('id', '<>', $id)
            ->exists();

        if ($existingProduct) {
            throw new \Exception("SKU '{$productBO->sku}' is already taken by another product.");
        }

        DB::table('products')->where('id', $id)->update([
            'name' => $productBO->name,
            'description' => $productBO->description,
            'sku' => $productBO->sku,
            'price' => $productBO->price,
            'category_id' => $productBO->categoryId,
            'updated_at' => now(),
        ]);

        return $this->getProductById($id);
    }


    public function deleteProduct(int $id): bool
    {
        return DB::table('products')->where('id', $id)->delete() > 0;
    }

    private function toBO($product): ProductBO
    {
        return new ProductBO(
            $product->id,
            $product->name,
            $product->description,
            $product->sku,
            $product->price,
            $product->category_id
        );
    }
}
