<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        
        $user = Auth::user();
        
        // Debug: Tampilkan informasi user dan role
        \Log::info('=== LOGIN DEBUG ===');
        \Log::info('User Email: ' . $user->email);
        \Log::info('User Roles: ' . json_encode($user->getRoleNames()->toArray()));
        \Log::info('Has admin role: ' . ($user->hasRole('admin') ? 'YES' : 'NO'));
        \Log::info('Has supervisor role: ' . ($user->hasRole('supervisor') ? 'YES' : 'NO'));
        \Log::info('HasAnyRole result: ' . ($user->hasAnyRole(['admin','supervisor']) ? 'TRUE' : 'FALSE'));
        
        if (Auth::user()->hasAnyRole(['admin','supervisor'])) {
            \Log::info('REDIRECT: Going to admin dashboard');
            return redirect()->intended(route('admin.dashboard'));
        } else {
            \Log::info('REDIRECT: Going to user profile');
            return redirect()->intended(route('user.profile.index'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
