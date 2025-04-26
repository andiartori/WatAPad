<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    // Pastikan Eloquent tahu bahwa ID tidak auto-increment
    public $incrementing = false;

    protected $fillable = [
        'id',
        'title',
        'overview',
        'content',
        'user_id',
        'category_id',
        'image_url',
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

