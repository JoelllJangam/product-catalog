<?php

namespace App\Repositories\Contracts;

use App\BusinessObjects\CategoryBO;

interface CategoryRepositoryInterface
{
    public function getCategories(): array;
}
