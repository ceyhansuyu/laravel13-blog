<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostView extends Model
{
    protected $fillable = [
        'post_id', 
        'session_id', 
        'ip_address',
        'created_at' // Eğer manuel atamayacaksan bunu da fillable'a ekle
    ];

    // 1. Bunu sil (veya false yapma)
    // public $timestamps = false; 

    // 2. Updated_at'i tamamen devre dışı bırak
    const UPDATED_AT = null;
    
    // 3. Created_at'i aktif tut
    const CREATED_AT = 'created_at';
    
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}