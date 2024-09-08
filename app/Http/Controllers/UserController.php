<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Services\UserService;

class UserController extends Controller
{

    public function __construct(private UserService $userService)
    {
    }

    public function store(CreateUserRequest $request)
    {
        return $this->userService->create($request->validated());
    }
}
