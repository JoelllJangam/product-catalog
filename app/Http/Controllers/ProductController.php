<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Http\Resources\ProductResource;
use App\Models\Product;


class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProducts(Request $request)
    {
        $categoryId = $request->query('category_id');
        $products = $this->productRepository->getProducts(10, $categoryId);
        return ProductResource::collection($products);
    }

    public function getProductById($id)
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return new ProductResource($product);

    }

    public function createProduct(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|unique:products,sku|max:100',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);

        $product = $this->productRepository->createProduct($data);

        return new ProductResource($product);

    }

    public function updateProduct(Request $request, $id)
    {
        // Validate the request data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => "required|string|max:100|unique:products,sku,{$id}",
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);
        $product = $this->productRepository->findById($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $updatedProduct = $this->productRepository->updateProduct($product, $data);

        return new ProductResource($updatedProduct);
    }

    public function deleteProduct($id)
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $this->productRepository->deleteProduct($product);

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }

}
