<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'id',
        'name',
        'description',
        'user_id'
    ];
    

    protected $dates = ['deleted_at'];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

public function user()
{
    return $this->belongsTo(User::class);
}
}
