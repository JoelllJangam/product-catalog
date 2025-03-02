<?php

namespace App\Http\Controllers;


use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\BusinessObjects\ProductBO;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProducts(): JsonResponse
    {
        $categoryId = request()->query('category_id') ?? null;
        $products = $this->productRepository->getProducts(10, $categoryId);
        return response()->json($products);
    }

    public function getProductById(int $id): JsonResponse
    {
        $product = $this->productRepository->getProductById($id);
        return $product ? response()->json($product) : response()->json(['error' => 'Product not found'], 404);
    }


    public function createProduct(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|unique:products,sku|max:50',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $productBO = new ProductBO(
                0,
                $request->name,
                $request->description,
                $request->sku,
                $request->price,
                $request->category_id
            );

            $created = $this->productRepository->createProduct($productBO);

            return response()->json($created, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function updateProduct(Request $request, int $id): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'sometimes|string|unique:products,sku,' . $id . '|max:50',
            'price' => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|exists:categories,id',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {

            $product = $this->productRepository->getProductById($id);
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }


            $productBO = new ProductBO(
                $id,
                $request->name ?? $product->name,
                $request->description ?? $product->description,
                $request->sku ?? $product->sku,
                $request->price ?? $product->price,
                $request->category_id ?? $product->categoryId
            );


            $updated = $this->productRepository->updateProduct($id, $productBO);
            return response()->json($updated);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function deleteProduct(int $id): JsonResponse
    {
        $deleted = $this->productRepository->deleteProduct($id);

        if ($deleted) {
            return response()->json(["message" => "Successfully Deleted"], 200);
        }

        return response()->json(['error' => 'Product not found'], 404);
    }
}
