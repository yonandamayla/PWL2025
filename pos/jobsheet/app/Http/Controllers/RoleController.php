<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleController extends Controller
{
    public function index()
    {
        $roles = RoleModel::all();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $role = RoleModel::create($validated);
        return response()->json($role, 201);
    }

    public function show($id)
    {
        $role = RoleModel::find($id);
        if (!$role) {
            throw new NotFoundHttpException("Role with ID {$id} not found");
        }
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = RoleModel::find($id);
        if (!$role) {
            throw new NotFoundHttpException("Role with ID {$id} not found");
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $role->update($validated);
        return response()->json($role);
    }

    public function destroy($id)
    {
        $role = RoleModel::find($id);
        if (!$role) {
            throw new NotFoundHttpException("Role with ID {$id} not found");
        }

        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }
}