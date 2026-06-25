<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            
            // Bizim yeni alanların kuralları:
            'bio' => ['nullable', 'string', 'max:1000'],
            
            // GÜNCELLEME: Esnek ve güvenli dosya yükleme kuralı (string hatası vermez, en-boy sınırı yoktur)
            'avatar' => [
                'nullable', 
                'image', 
                'mimes:jpeg,png,jpg,gif,webp,avif', 
                'max:2048'
            ],
            
            'github_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
        ];
    }
}