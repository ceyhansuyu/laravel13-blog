<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\Setting;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Kayıtlar kapalıysa 403 hatası döndür
        if (!Setting::getVal('enable_registration', true)) {
            abort(403, __('Registration is currently closed.'));
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Form dışarıdan post edilmeye çalışılırsa diye güvenlik kontrolü
        if (!Setting::getVal('enable_registration', true)) {
            abort(403, __('Registration is currently closed.'));
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Veritabanında hiç kullanıcı var mı kontrol et
        $isFirstUser = User::count() === 0;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $isFirstUser ? 'founder' : 'user', 
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Yönlendirme mantığı:
        if ($user->role === 'founder') {
            return redirect(route('dashboard', absolute: false));
        }

        return redirect(route('admin.profile', absolute: false));
    }
}