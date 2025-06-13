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

        // === INI BAGIAN YANG DIPERBAIKI ===
        // Dapatkan data user yang baru saja login
        $user = Auth::user();

        // Tentukan URL tujuan berdasarkan peran (role) user
        $url = match ($user->role) {
            'admin'   => 'http://app.kukurukuy.test/admin', // Admin ke panel Filament
            'manager' => 'http://stok.kukurukuy.test',      // Manajer ke halaman stok
            'cashier' => 'http://kasir.kukurukuy.test',     // Kasir ke halaman POS
            default   => 'http://kasir.kukurukuy.test',     // Tujuan default jika ada peran lain
        };

        return redirect($url);
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
