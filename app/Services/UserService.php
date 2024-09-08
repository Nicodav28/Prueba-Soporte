<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Services\Contracts\IStandardContract;
use App\Traits\ResponseHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;


class UserService
{
    use ResponseHandler;

    public function create(array $validated)
    {
        try {
            $user = User::create($validated);

            if (!$user) {
                return $this->redirectWithError('User could not be created.', __METHOD__, self::class);
            }

            return $this->redirectWithSuccess('User created successfully.', __METHOD__, self::class);
        } catch (\Throwable $th) {
            return $this->redirectWithError('Ups!, an error just happened, please notify this issue to the customer support team.', __METHOD__, self::class);
        }
    }

}
