<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        // Get order count if needed
        $orderCount = 0;
        $user = User::find(auth()->id());
        
        if ($user && $user->orders) {
            $orderCount = $user->orders->count();
        }
        
        $activeMenu = 'profile'; // Set active menu for sidebar
        
        // Create breadcrumb data
        $breadcrumb = (object)[
            'title' => 'Profil',
            'list' => ['Home', 'Profil']
        ];
        
        return view('profile.index', compact('orderCount', 'user', 'activeMenu', 'breadcrumb'));
    }
    
    public function updateProfile(Request $request)
    {
        $userId = auth()->id();
        $user = User::findOrFail($userId);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);
        
        DB::table('users')->where('id', $userId)->update($validated);
        
        return redirect()->route('profile.index')->with('success', 'Informasi profil berhasil diperbarui.');
    }
    
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_picture' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);
        
        $userId = auth()->id();
        $user = User::find($userId);
        
        // Delete old image if exists and not default
        if ($user && $user->profile_picture && !str_contains($user->profile_picture, 'default-avatar.png')) {
            // Extract the path relative to public directory
            $oldPath = str_replace(url('/'), '', $user->profile_picture);
            $oldPath = str_replace('/storage', 'public', $oldPath);
            
            if (Storage::exists($oldPath)) {
                Storage::delete($oldPath);
            }
        }
        
        // Store new image
        $path = $request->file('profile_picture')->store('public/profile-pictures');
        $profilePicture = Storage::url($path);
        
        // Use DB facade instead of model update
        DB::table('users')->where('id', $userId)->update([
            'profile_picture' => $profilePicture
        ]);
        
        return redirect()->route('profile.index')
            ->with('success', 'Foto profil berhasil diperbarui.');
    }
    
    public function updatePassword(Request $request)
    {
        $userId = auth()->id();
        $user = User::find($userId);
        
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!$user || !Hash::check($value, $user->password)) {
                    $fail('Kata sandi saat ini tidak cocok.');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        // Use DB facade for direct update
        DB::table('users')->where('id', $userId)->update([
            'password' => Hash::make($request->password)
        ]);
        
        return redirect()->route('profile.index')
            ->with('password_success', 'Kata sandi berhasil diperbarui.');
    }
    
    public function updatePreferences(Request $request)
    {
        $userId = auth()->id();
        $user = User::find($userId);
        
        $settings = $user && isset($user->settings) ? $user->settings : [];
        if (is_string($settings)) {
            // Convert JSON string to array if needed
            $settings = json_decode($settings, true) ?: [];
        }
        
        $settings['theme'] = $request->input('theme', 'light');
        $settings['notifications_enabled'] = $request->has('notifications_enabled');
        
        // Use DB facade for direct update
        DB::table('users')->where('id', $userId)->update([
            'settings' => json_encode($settings)
        ]);
        
        return redirect()->route('profile.index')
            ->with('preferences_success', 'Pengaturan berhasil diperbarui.');
    }
}