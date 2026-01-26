<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id' , 'number_order' , 'type_payment',
        'status' , 'subtotal' , 'payment_status',
        'delivery_fee' , 'total_price'];


    public static function booted()
    {
        static::creating(function(Order $order){
            $order->number_order = Order::getNextNumberOrder();
        });
    }


    public function scopeFilter(Builder $builder, $request)
    {
        $builder->when($request->order_number, function ($builder, $search) {
            $builder->where(function ($q) use ($search) {
                $q->where('number_order', 'LIKE', "%{$search}%")
                ->orWhereHas('user', function ($u) use ($search) {
                    $u->where('name', 'LIKE', "%{$search}%");
                });
            });
        });
    }


    public static function getNextNumberOrder()
    {
        $year = Carbon::now()->year ;
        $latest_order = Order::whereYear('created_at' , $year )->max('number_order') ;
        if($latest_order){
            return $latest_order + 1 ;
        }
        return $year . '0001' ;
    }


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class , 'order_id') ;
    }


    public function user()
    {
        return $this->belongsTo(User::class , 'user_id') ;
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class , 'order_id') ;
    }

    // has on coupon useage
    public function couponUsage()
    {
        return $this->hasOne(CouponUsage::class, 'order_id');
    }


}
