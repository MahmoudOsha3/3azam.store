<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'title'];

    protected static function booted()
    {
        static::saved(function ($setting) {
            cache()->forget("setting_{$setting->key}");
        });

        static::deleted(function ($setting) {
            cache()->forget("setting_{$setting->key}");
        });
    }

}
