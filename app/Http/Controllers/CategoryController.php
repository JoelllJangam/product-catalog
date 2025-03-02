<?php 

namespace App\Http\Controllers;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategories(): JsonResponse
    {
        $categories = $this->categoryRepository->getCategories();
        return response()->json($categories);
    }
}