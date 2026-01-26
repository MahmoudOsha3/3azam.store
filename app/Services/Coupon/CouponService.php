<?php

namespace App\Services\Coupon ;

use App\Models\Coupon;
use App\Models\CouponUsage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CouponService
{

    public function applyCoupon($coupon_code , $orderTotal)
    {
        $coupon = Coupon::where('code' , $coupon_code)
            ->where('start_at', '<=', Carbon::now())
            ->where('end_at', '>=', Carbon::now())->first();

        if(! $this->validateCoupon($coupon , $orderTotal)){
            return ['status' => false ,'discount' => 0, 'coupon' => null ] ;
        }

        $discount = 0 ;
        if($coupon->discount_type === 'fixed') {
            $discount =  min($coupon->discount_value, $orderTotal); // هاخد اقل قيمة عشان مينفعش يكون الخصم يكون اكبر من سعر منتج كله ممكن الخصم يبقي المنتج كله عادي
        }

        if($coupon->discount_type === 'percentage') {
            $discount =  round($orderTotal * ($coupon->discount_value / 100 ) , 2) ;
        }

        return ['status' => true , 'discount' => $discount, 'coupon' => $coupon];
        // برجع القيمة اللي هطرحها من التوتال بتاعه المنتج

    }

    public function validateCoupon($coupon , $orderTotal)
    {
        if(! $coupon || $coupon->status == 'inactive') return false ;

        if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) return false ;

        if ($coupon->min_order_amount && $orderTotal < $coupon->min_order_amount) return false;

        $alreadyUsed = CouponUsage::where([
                'coupon_id' => $coupon->id,
                'user_id' => auth()->id()
            ])->exists();
        if($alreadyUsed) return false ;
        
        return true ;
    }


    public function recordCouponUsage($coupon , $order , $totalBeforeCoupon , $discount )
    {
        $coupon->increment('used_count');
        CouponUsage::create([
            'coupon_id' => $coupon->id ,
            'order_id' => $order->id ,
            'user_id' => auth()->user()->id ,
            'total_order_before_discound' => $totalBeforeCoupon ,
            'value_discound' => $discount ,
        ]) ;

    }

}
