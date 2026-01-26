<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['invoice'  , 'order_id' , 'user_id'] ;

    protected $appends = ['invoice_url'];

    public function scopeFilter(Builder $builder , $request)
    {
        $builder->when($request->number_order , function ($builder, $number_order){
            $builder->whereHas('order' , function($q) use ($number_order){
                $q->where('number_order','LIKE', "%{$number_order}%");
            }) ;
        });
    }

    public function getInvoiceUrlAttribute()
    {
        return asset(Storage::url('payments/' . $this->invoice )) ;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id') ;
    }
}
