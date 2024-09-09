<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ResponseHandler;
use Illuminate\Http\JsonResponse;



class UserService
{
    use ResponseHandler;

    public function create(array $validated): JsonResponse
    {
        try {
            $user = User::create($validated);

            if (!$user) {
                return $this->errorResponse(__METHOD__, self::class, 'User could not be created.');
            }

            return $this->successResponse(__METHOD__, self::class, 'User created successfully.');
        } catch (\Throwable $th) {
            return $this->errorResponse(__METHOD__, self::class, 'Ups!, an error just happened, please notify this issue to the customer support team.');
        }
    }

}
