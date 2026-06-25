<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index()
    {
        Gate::authorize('is-admin');
        // Kullanıcıları en yeniye göre sıralayıp sayfalıyoruz
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        Gate::authorize('is-admin');
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('is-admin');
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,author,user'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('toast', __('User created successfully.'));
    }


    /**
     * Kullanıcı düzenleme sayfasını gösterir.
     * Bilgilerini görmesini engellemek için bu kontrolü edit metoduna da ekliyoruz.
     */
    public function edit(User $user)
    {
        Gate::authorize('is-admin');
        // İşlemi yapan kişi founder değilse ve düzenlenmek istenen kişi founder ise engelle
        if ($user->role === 'founder' && auth()->user()->role !== 'founder') {
            return redirect()->route('admin.users.index')->with('error', __('You do not have permission to view founder accounts!'));
            // Alternatif olarak 403 hatası fırlatabilirsin: abort(403, 'Bu sayfayı görüntüleme yetkiniz yok.');
        }

        return view('admin.users.edit', compact('user'));
    }


    public function update(Request $request, User $user)
    {
        Gate::authorize('is-admin');
        // Admin'in founder'ı düzenlemesini engelliyoruz
        if ($user->role === 'founder' && auth()->user()->role !== 'founder') {
            return redirect()->route('admin.users.index')->with('error', __('You do not have permission to edit founder accounts!'));
        }

        // YENİ KURAL: Giriş yapan kişi founder değilse ve birini founder yapmaya çalışıyorsa engelle
        if ($request->input('role') === 'founder' && auth()->user()->role !== 'founder') {
            return redirect()->back()->with('error', __('Only a founder can assign the founder role!'))->withInput();
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Kendi e-postasını hariç tutarak benzersizlik kontrolü yapıyoruz
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            // Validation kurallarına dinamik bir kontrol ekliyoruz
            'role' => [
                'required', 
                auth()->user()->role === 'founder' 
                    ? 'in:founder,admin,author,user' // Giriş yapan founder ise hepsini seçebilir
                    : 'in:admin,author,user'        // Giriş yapan admin ise founder seçeneğini seçemez
            ],
            // Şifre alanı zorunlu değil (nullable)
            'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Eğer kullanıcı yeni bir şifre girdiyse bunu hashleyip güncellenecek dataya ekliyoruz
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // --- AVATAR SİLME MANTIĞI BAŞLANGICI ---
        // Formdan delete_avatar checkbox'ı geldiyse ve kullanıcının avatarı varsa
        if ($request->has('delete_avatar') && $user->avatar) {
            // Disk üzerindeki eski dosyayı siliyoruz
            if (Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Veritabanında avatar alanını boşaltıyoruz
            $data['avatar'] = null;
        }
        // --- AVATAR SİLME MANTIĞI BİTİŞİ ---

        $user->update($data);

        return redirect()->route('admin.users.index')->with('toast', __('User information updated successfully.'));
    }

    /**
     * Kullanıcıyı sistemden siler.
     */
    public function destroy(User $user)
    {
        Gate::authorize('is-admin');
        // Güvenlik önlemi: Admin'in yanlışlıkla kendini silmesini engelliyoruz
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', __('You cannot delete your own account!'));
        }

        // Admin'in founder'ı silmesini engelliyoruz
        if ($user->role === 'founder' && auth()->user()->role !== 'founder') {
            return redirect()->route('admin.users.index')->with('error', __('You do not have permission to delete founder accounts!'));
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('toast', __('User deleted successfully.'));
    }
}