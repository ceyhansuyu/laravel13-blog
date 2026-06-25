<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function show(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validatedData = $request->validated();

        // Satır içi kontrol: Dosya gerçekten geliyor mu diye test edelim
        if ($request->hasFile('avatar')) {
            
            $file = $request->file('avatar');
            $extension = $file->extension(); 

            // Eğer dosya türü tespit edilemezse, işlemi durdur.
            if (!$extension) {
                throw ValidationException::withMessages([
                    'avatar' => 'Güvenilmeyen veya geçersiz bir dosya türü yüklendi.',
                ]);
            }

            // Eski resmi temizleme
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $filename = Str::slug($user->name) . '-' . $user->id . '.' . $extension;
            $path = 'avatars/' . $filename;

            // 2. EXIF temizleme işlemi (Resmi bellekte yeniden oluşturarak)
            // Not: ProfileUpdateRequest içerisinde 'dimensions' ve 'max' boyutu kurallarını 
            // mutlaka tanımladığından emin ol ki sunucu belleği (RAM) şişmesin.
            $imageContent = file_get_contents($file);
            $image = @imagecreatefromstring($imageContent);
            
            if ($image !== false) {
                ob_start();
                // Uzantıya göre resmi yeniden çıktıla (Bu işlem EXIF verilerini otomatik siler)
                switch (strtolower($extension)) {
                    case 'png':
                        imagepng($image);
                        break;
                    case 'webp':
                        imagewebp($image);
                        break;
                    case 'gif':
                        imagegif($image);
                        break;
                    case 'avif':
                        if (function_exists('imageavif')) {
                            imageavif($image);
                        } else {
                            imagejpeg($image, null, 90); // Sunucuda avif desteği yoksa fallback
                        }
                        break;                    
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($image, null, 90); // %90 kalite ile JPEG/JPG kaydet
                        break;
                    default:
                        imagejpeg($image, null, 90); // Beklenmeyen durumlarda da %90 kalite ile JPEG kaydet
                        break;
                }
                $cleanImageContent = ob_get_clean();
                imagedestroy($image);
                
                // Temizlenmiş resmi yükle ve yolu al
                Storage::disk('public')->put($path, $cleanImageContent);
                
                // Doğrulanmış verilere avatar yolunu ekle
                $validatedData['avatar'] = $path;
            } else {
                throw ValidationException::withMessages([
                    'avatar' => 'Yüklenen dosya geçerli veya okunabilir bir resim formatında değil.',
                ]);
            }
        }

        // Güvenlik: show_email değerini boolean olarak alıp katı bir şekilde dönüştürüyoruz.
        // Bu sayede saldırgan form dışından dizi (array) gönderse bile sistem çökmeyecektir.
        $validatedData['show_email'] = $request->boolean('show_email') ? '1' : '0';

        // Kullanıcıyı yeni verilerle doldur ve kaydet
        $user->fill($validatedData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('admin.profile.edit')
            ->with('status', 'profile-updated')
            ->with('toast', __('Profile updated successfully.'));
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('toast', __('Your account has been deleted successfully.'));
    }
}

//note: Daha güvenli