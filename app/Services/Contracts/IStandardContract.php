<?php

namespace App\Services\Contracts;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

interface IStandardContract
{
    public function getAll(int $paginate, ?string $filter): LengthAwarePaginator|JsonResponse;

    public function create(array $data): Model|array|JsonResponse;

    public function update(string $id, array $data): Model|array|bool|JsonResponse;

    public function delete(int|string $id): bool|array|Model|JsonResponse;
}