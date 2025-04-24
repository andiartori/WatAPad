<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    // Tentukan nama tabel jika tidak mengikuti konvensi Laravel
    // protected $table = 'categories';

    // Tentukan kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'id',
        'name',
        'description',
        'user_id'
    ];
    

    // Tentukan kolom yang akan di-soft delete
    protected $dates = ['deleted_at'];

    // Relasi dengan artikel (jika ada)
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
