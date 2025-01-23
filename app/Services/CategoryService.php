<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{
    private CategoryRepository $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function getAll()
    {
        return $this->categoryRepo->getAll();
    }

    public function findById(int $id)
    {
        return $this->categoryRepo->findById($id);
    }

    public function create(array $data)
    {
        return $this->categoryRepo->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->categoryRepo->update($id, $data);
    }

    public function delete(int $id): void
    {
        $this->categoryRepo->delete($id);
    }
}
