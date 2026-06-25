<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'name',
        'email',
        'content',
        'status',
        'is_approved',
        'ip_address',
        'user_agent',
        'user_id'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Guest User' // Eğer user_id NULL ise hata vermek yerine bunu döner.
        ]);
    }
    
}