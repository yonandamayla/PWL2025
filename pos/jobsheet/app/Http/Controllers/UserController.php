<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{

    /**
     * Check if user is admin
     */
    public function isAdmin($user = null)
    {
        $user = $user ?? auth()->user();
        return $user && $user->role_id === 1;  // Assuming role_id 1 is admin
    }
    /**
     * Display users management page
     */
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Home', 'Data Pengguna',
            'list' => [
                'Home' => route('home'),
                'Data Pengguna' => null
            ]
        ];
        
        return view('users.index', compact('breadcrumb'))->with('activeMenu', 'users');
    }
    

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Get all users for AJAX request
     */
    public function getUsers(Request $request)
    {
        try {
            $users = UserModel::select('id', 'name', 'email', 'role_id', 'created_at')
                ->get();

            return response()->json(['data' => $users]);
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch users'], 500);
        }
    }

    /**
     * Store a new user
     */
    public function store(Request $request)
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,kasir,customer', // Make sure this matches the values in your form
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            $user = new UserModel();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            
            // Convert role string to role_id (this was missing)
            switch($request->role) {
                case 'admin': $user->role_id = 1; break;
                case 'kasir': $user->role_id = 2; break;
                case 'customer': $user->role_id = 3; break;
                default: $user->role_id = 3; // Default to customer
            }
            
            $user->is_active = true;
            $user->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil ditambahkan',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific user
     */
    public function show($id)
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = UserModel::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['data' => $user]);
    }

    /**
     * Update user details
     */
    public function update(Request $request, $id)
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        $user = UserModel::find($id);
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,kasir,customer',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            // Convert role string to role_id
            switch($request->role) {
                case 'admin': $user->role_id = 1; break;
                case 'kasir': $user->role_id = 2; break;
                case 'customer': $user->role_id = 3; break;
                default: $user->role_id = 3; // Default to customer
            }
            
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil diperbarui',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a user
     */
    public function destroy($id)
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = UserModel::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Don't allow admin to delete themselves
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'Cannot delete your own account'], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil dihapus'
        ]);
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus($id)
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = UserModel::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Don't allow admin to deactivate themselves
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'Cannot change status of your own account'], 400);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return response()->json([
            'success' => true,
            'message' => "Pengguna berhasil {$status}",
            'data' => $user
        ]);
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            // Filter by role if provided
            $role_id = $request->get('role_id');
            $users = UserModel::select(['id', 'name', 'email', 'role_id']);
            
            if ($role_id) {
                $users->where('role_id', $role_id);
            }
            
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">
                            <button type="button" data-id="' . $row->id . '" class="btn btn-info btn-sm view-btn" title="Lihat detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" data-id="' . $row->id . '" class="btn btn-primary btn-sm edit-btn" title="Edit pengguna">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete-btn" title="Hapus pengguna">
                                <i class="fas fa-trash"></i>
                            </button>
                            </div>';
                    return $btn;
                })
                // Add a default status column since it doesn't exist in DB
                ->addColumn('is_active', function () {
                    return true; // Default value
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        $breadcrumb = (object)[
            'title' => 'Data Pengguna',
            'list' => [
                'Home' => route('home'),
                'Data Pengguna' => null
            ]
        ];
        
        return view('users.list', compact('breadcrumb'))->with('activeMenu', 'users');
    }
}
