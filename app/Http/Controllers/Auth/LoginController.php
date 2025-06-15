<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Check if the user is active
        $user = User::where('email', $request->email)->first();

        if ($user && $user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['Akun Anda tidak aktif. Silakan hubungi administrator.'],
            ]);
        }

        // Attempt to log the user in
        if ($this->attemptLogin($request)) {
            $request->session()->regenerate();

            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     */
    protected function attemptLogin(Request $request)
    {
        return Auth::attempt(
            $this->credentials($request),
            $request->boolean('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     */
    protected function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }

    /**
     * Send the response after the user was authenticated.
     */
    protected function sendLoginResponse(Request $request)
    {
        $user = Auth::user();

        // Redirect berdasarkan role
        $redirectPath = $this->redirectPath($user);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Login berhasil',
                'user' => $user,
                'redirect' => $redirectPath
            ]);
        }

        return redirect()->intended($redirectPath)->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    /**
     * Get the failed login response instance.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => ['Email atau password yang Anda masukkan salah.'],
        ]);
    }

    /**
     * Determine redirect path based on user role.
     */
    protected function redirectPath($user)
    {
        switch ($user->role) {
            case 'admin':
                return '/admin/dashboard';
            case 'editor':
                return '/editor/dashboard';
            case 'wartawan':
                return '/wartawan/dashboard';
            case 'user':
                return '/user/dashboard';
            default:
                return '/dashboard';
        }
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Logout berhasil']);
        }

        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Handle social login callback (for future OAuth integration).
     */
    public function handleSocialCallback($provider, Request $request)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            $user = User::where("{$provider}_id", $socialUser->getId())
                ->orWhere('email', $socialUser->getEmail())
                ->first();

            if (!$user) {
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'avatar' => $socialUser->getAvatar(),
                    'role' => 'wartawan', // default
                    'status' => 'active',
                    "{$provider}_id" => $socialUser->getId(),
                    'password' => Hash::make(str()->random(20)),
                ]);
            } else {
                if (!$user->{"{$provider}_id"}) {
                    $user->update(["{$provider}_id" => $socialUser->getId()]);
                }
            }

            Auth::login($user, true);

            return redirect($this->redirectPath($user))
                ->with('success', 'Login berhasil melalui ' . ucfirst($provider));
        } catch (\Exception $e) {
            return redirect('/login')
                ->with('error', 'Terjadi kesalahan saat login dengan ' . ucfirst($provider));
        }
    }

    /**
     * Check if user account is active.
     */
    protected function checkUserStatus($email)
    {
        $user = User::where('email', $email)->first();

        return $user && $user->status === 'active';
    }

    /**
     * Get user by email for additional checks.
     */
    protected function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }
}
