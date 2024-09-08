<?php

namespace App\Services\Contracts;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;

interface IStandardContract
{
    public function create(array $data): Model|array|RedirectResponse;

    public function update(string $id, array $data): Model|array|bool|RedirectResponse;

    public function delete(int|string $id): bool|array|Model|RedirectResponse;
}