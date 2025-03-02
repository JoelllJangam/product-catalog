<?php 

namespace App\BusinessObjects;

class CategoryBO
{
    public int $id;
    public string $name;
    public ?int $parentCategoryId;
    public ?array $children;

    public function __construct(int $id, string $name, ?int $parentCategoryId, ?array $children = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->parentCategoryId = $parentCategoryId;
        $this->children = $children;
    }
}

?>