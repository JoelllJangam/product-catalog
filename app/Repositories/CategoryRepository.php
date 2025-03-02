<?php

namespace App\Repositories;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getCategories(): array
    {
        $categories = DB::select("
        WITH RECURSIVE category_tree AS (
            SELECT 
                id, name, parent_category_id, created_at, updated_at, 0 AS level
            FROM categories
            WHERE parent_category_id IS NULL

            UNION ALL

            SELECT 
                c.id, c.name, c.parent_category_id, c.created_at, c.updated_at, ct.level + 1
            FROM categories c
            INNER JOIN category_tree ct ON c.parent_category_id = ct.id
        )
        SELECT * FROM category_tree ORDER BY level, id;
    ");

        $categoriesArray = array_map(fn($category) => [
            'id' => $category->id,
            'name' => $category->name,
            'parent_category_id' => $category->parent_category_id,
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
            'children' => []
        ], $categories);

        return $this->buildTree($categoriesArray);
    }

    /**
     * Convert flat category list into hierarchical tree
     */
    private function buildTree(array $categories, $parentId = null): array
    {
        $tree = [];

        foreach ($categories as &$category) {
            if ($category['parent_category_id'] === $parentId) {
                $children = $this->buildTree($categories, $category['id']);
                if ($children) {
                    $category['children'] = $children;
                }
                $tree[] = $category;
            }
        }

        return $tree;
    }
}
