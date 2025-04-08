<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    public function index()
    {
        $users = UserModel::with('role')->get();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'profile_picture' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = UserModel::create($validated);
        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = UserModel::with('role')->find($id);
        if (!$user) {
            throw new NotFoundHttpException("User with ID {$id} not found");
        }
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = UserModel::find($id);
        if (!$user) {
            throw new NotFoundHttpException("User with ID {$id} not found");
        }

        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'profile_picture' => 'nullable|string',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = UserModel::find($id);
        if (!$user) {
            throw new NotFoundHttpException("User with ID {$id} not found");
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}