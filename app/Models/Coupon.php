<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'discount_type' , 'discount_value', 'max_uses', 'used_count',
        'start_at', 'end_at', 'min_order_amount' , 'status'
    ];


    public function scopeFilter(Builder $builder , $request)
    {
        $builder->when($request->code , function ($builder , $code) {
            $builder->where('code','LIKE' ,"%{$code}%") ;
        });
    }



    // relationship
    public function orders()
    {
        return $this->hasMany(Order::class, 'order_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'user_id');
    }
}
