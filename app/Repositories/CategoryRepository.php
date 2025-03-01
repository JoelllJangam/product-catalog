<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    /**
     * Get all categories with their child categories in a nested structure.
     */
    public function getCategories()
    {
        return Category::whereNull('parent_category_id')->with('children')->get();
    }
}
