<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'kode_peserta' => 'nullable|string',
        ]);

        $pengaturan = Pengaturan::where('key', 'token_peserta')->first();
        $validToken = $pengaturan ? $pengaturan->value : null; // Nilai default diganti null biar aman

        $assignedRole = 'pengunjung';

        // Hanya jadikan peserta jika token sudah diatur admin DAN input cocok
        if ($validToken && $request->kode_peserta === $validToken) {
            $assignedRole = 'peserta';
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $assignedRole,
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($assignedRole === 'peserta') {
            return redirect()->route('peserta.galeri');
        }

        return redirect(route('dashboard', absolute: false));
    }
}
