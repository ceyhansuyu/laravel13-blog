<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {

        // 0. Yorum gönderimi ayarlardan kapalıysa işlemi durdur ve geri yönlendir
        $allowSubmitComments = \App\Models\Setting::getVal('allow_submit_comments', false);
        
        if ($allowSubmitComments == false) {
            return back()->withFragment('comments-container')
                         ->with('error', __('Yorum gönderimi şu anda kapalıdır.'));
        }


        // 1. İçeriği temizle (HTML etiketlerine izin verir ama zararlıları eler)
        $cleanContent = Purify::clean($request->input('content'));

        // 2. İsim alanını tamamen HTML'den arındır (İsimde HTML işimiz yok)
        $cleanName = trim(strip_tags(Purify::clean($request->input('name'))));

        // 3. Temizlenmiş verileri request içine geri enjekte et
        $request->merge([
            'name' => $cleanName,
            'content' => $cleanContent
        ]);

        // 4. Doğrulamayı tek bir Validator ile manuel yapıyoruz
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:50',
            'email' => 'required|email|max:100',
            'content' => 'required|string|min:5|max:2000',
            'h-captcha-response' => ['required', function ($attribute, $value, $fail) {
                
                // Veritabanından anahtarları çekiyoruz
                $secretKey = \App\Models\Setting::where('key', 'hcaptcha_secret_key')->value('value');

                $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
                    'secret' => $secretKey,
                    'response' => $value,
                    'remoteip' => request()->ip()
                ]);

                if (! $response->json('success')) {
                    $fail(__('Please verify that you are not a robot.'));
                }
            }],
        ]);

        // Eğer doğrulama başarısız olursa:
        if ($validator->fails()) {
            // Hata varsa URL'nin sonuna #comments-container ekler
            // withErrors: Hata mesajlarını taşır
            // withInput: Kullanıcının girdiği verilerin silinmemesini sağlar
            return redirect(url()->previous() . '#comments-container')
                        ->withErrors($validator)
                        ->withInput();
        }


        $isModerationActive = \App\Models\Setting::getVal('comment_moderation', false);

        $status = $isModerationActive ? 'pending' : 'approved';


        // 5. Güvenli veriyi kaydet
        Comment::create([
            'post_id' => $post->id,
            'name' => $request->input('name'), // Merge ile değişmişti
            'email' => $request->input('email'),
            'content' => $request->input('content'),
            'is_approved' => true,
            'status' => $status,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
        ]);

        // Statü durumuna göre toast mesajını belirliyoruz
        $message = $status === 'pending'
            ? __('Your comment has been received and is awaiting approval.')
            : __('Comment submitted successfully!');

        // İşlem başarılıysa belirlenen mesajla birlikte dön
        return back()->withFragment('comments-container')->with('toast', $message);
    }
}