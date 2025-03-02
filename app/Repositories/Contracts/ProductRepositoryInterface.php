<?php

namespace App\Repositories\Contracts;

use App\BusinessObjects\ProductBO;

interface ProductRepositoryInterface
{
    public function getProducts(int $perPage, ?int $categoryId): array;
    public function getProductById(int $id): ?ProductBO;
    public function createProduct(ProductBO $productBO): ProductBO;
    public function updateProduct(int $id, ProductBO $productBO): ProductBO;
    public function deleteProduct(int $id): bool;
}
