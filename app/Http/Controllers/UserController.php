<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    // JSON response helper
    private function JsonRes(int $status, string $message, $data = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }

    // Register user
    public function register(Request $request)
    {
        // Validate input
        $input = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        $input['password'] = Hash::make($input['password']);

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/'), $fileName);
            $input['profile_image'] = url('upload/' . $fileName);
        }

        $user = User::create($input);
        $token = JWTAuth::fromUser($user);

        return $this->JsonRes(200, 'Register success', [
            'token' => $token,
            'user' => $user
        ]);
    }

    // Login user
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->JsonRes(400, 'Invalid credentials');
        }

        $user = auth()->user();

        return $this->JsonRes(200, 'Login success', [
            'token' => $token,
            'email' => $user->email,
            'name' => $user->name,
            'profile_image' => $user->profile_image ?? null,
            'role' => $user->role
        ]);
    }

    // Get all users
    public function getUser()
    {
        $users = User::all();
        return $this->JsonRes(200, 'Users fetched successfully', $users);
    }

    // Toggle user role
    public function changeRole(int $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->role = $user->role == 1 ? 0 : 1;
            $user->save();

            return $this->JsonRes(200, 'User role updated', $user);
        } catch (Exception $e) {
            return $this->JsonRes(500, 'Error: ' . $e->getMessage());
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Find or create user
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'profile_image' => $googleUser->getAvatar(),
                    'password' => bcrypt(uniqid()), // random password
                    'role' => 0
                ]
            );

            // Generate JWT token
            $token = JWTAuth::fromUser($user);

            // Redirect to front-end with token and user info
            return redirect()->to(
                "http://127.0.0.1:5502/auth/login.html?token=$token&email={$user->email}&name={$user->name}&role={$user->role}&profile_image={$user->profile_image}"
            );
        } catch (\Exception $e) {
            return redirect()->to("http://127.0.0.1:5500/login.html?error=google_auth_failed");
        }
    }
}
