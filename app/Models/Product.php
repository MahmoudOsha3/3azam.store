<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title' , 'description' , 'image' ,
        'sku' , 'price' , 'compare_price' , 'stock' ,
        'status' , 'category_id', 'sales_count'
        ];

    protected $appends = ['image_url'];

    public static function booted()
    {
        static::creating(function (Product $product) {
            $product->sku = Product::getNextSku();
        });
    }


    public function scopeFilter(Builder $builder , $request)
    {
        $builder->when($request->title , function($builder , $title ){
            $builder->where('title','like','%'. $title .'%') ;
        }) ;
    }


    public function getImageUrlAttribute()
    {
        return asset(Storage::url('products/' . $this->image)) ;
    }

    public static function getNextSku()
    {
        $year = Carbon::now()->year ;
        $latest_sku = Product::whereYear('created_at' , $year )->max('sku') ;
        if($latest_sku){
            return $latest_sku + 1 ;
        }
        return $year . '0001' ;
        // general Sku = 'PRD-20260001'
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id') ;
    }
}
