<?php

namespace App\BusinessObjects;

class ProductBO
{
    public int $id;
    public string $name;
    public ?string $description;
    public string $sku;
    public float $price;
    public int $categoryId;

    public function __construct(int $id, string $name, ?string $description, string $sku, float $price, int $categoryId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->sku = $sku;
        $this->price = $price;
        $this->categoryId = $categoryId;
    }
}

?>