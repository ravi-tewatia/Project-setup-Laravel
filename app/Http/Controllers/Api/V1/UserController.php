<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Models\User;
use App\Traits\ApiVersioning;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    use ApiVersioning;

    public function index(): JsonResponse
    {
        $users = User::all();
        return $this->sendResponse($users, 'Users retrieved successfully');
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->sendResponse($user, 'User created successfully', 201);
    }

    public function show(int $id): JsonResponse
    {
        $user = User::find($id);

        if (is_null($user)) {
            return $this->sendNotFound('User not found');
        }

        return $this->sendResponse($user, 'User retrieved successfully');
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (is_null($user)) {
            return $this->sendNotFound('User not found');
        }

        $user->update($request->validated());

        return $this->sendResponse($user, 'User updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $user = User::find($id);

        if (is_null($user)) {
            return $this->sendNotFound('User not found');
        }

        $user->delete();

        return $this->sendResponse(null, 'User deleted successfully');
    }
} 