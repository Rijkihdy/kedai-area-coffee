<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan',
        ]);

            // Ensure the role exists (tests may use a fresh DB without seeders)
            Role::firstOrCreate(['name' => 'pelanggan'], ['guard_name' => 'web']);
            $user->assignRole('pelanggan');

        Pelanggan::create([
            'id_user' => $user->id_user,
            'nama' => $user->name,
            'alamat' => null,
        ]);

        event(new Registered($user));

        Log::info('RegisteredUserController: before login', ['user_id' => $user->id_user, 'auth_before' => Auth::check()]);

        Auth::login($user);

        Log::info('RegisteredUserController: after login', ['user_id' => $user->id_user, 'auth_after' => Auth::check()]);

        return redirect(RouteServiceProvider::HOME);
    }
}
