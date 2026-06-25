<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the registration form.
     */
    public function register()
    {
        $roles = Role::pluck('name');

        return view('auth.register', compact('roles'));
    }

    /**
     * Handle a registration request.
     */
    public function storeUser(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'type' => $validated['role_name'],
                'avatar' => 'photo_defaults.jpg',
                'join_date' => now()->toDayDateTimeString(),
                'password' => $validated['password'],
            ]);

            $user->assignRole($validated['role_name']);

            return response()->json([
                'message' => 'Account created successfully!',
                'redirect' => route('login'),
            ]);
        } catch (\Exception $e) {
            Log::error('User registration failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to create account. Please try again.',
            ], 500);
        }
    }
}
