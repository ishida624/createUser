<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|max:255',
                'phone'    => 'required|numeric|digits:10|unique:users',
                'password' => 'required|string|min:8',
            ]);

        } catch (\Throwable $throwable) {
            return response()->json(['message' => $throwable->getMessage()], 422);
        }
        $user = User::firstOrCreate(['phone' => $validated['phone']], $validated);

        return response()->json([
            'message' => 'success',
            'data'    => [
                'user' => $user
            ]
        ], 201);
    }

    public function userList()
    {
        $users = User::all();

        return response()->json([
            'message' => 'success',
            'data'    => [
                'users' => $users->toArray(),
            ]
        ], 200);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (blank($user)) {
            return response()->json(['message' => 'user not found',], 404);
        }

        return response()->json([
            'message' => 'success',
            'data'    => [
                'user' => $user->toArray(),
            ]
        ], 200);
    }
}
