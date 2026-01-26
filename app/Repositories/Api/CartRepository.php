<?php

namespace App\Repositories\Api ;

use App\Interfaces\CartRepositoryInterface;
use App\Models\Cart;
use Illuminate\Support\Facades\{Auth , DB};
use App\Models\Product;
use Exception;

class CartRepository implements CartRepositoryInterface
{
    public $productRepository ;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository ;
    }
    public function getCarts($user_id = null)
    {
        $query = Cart::with('product:id,title,price,image,sku');
        if ($user_id) {
            return $query->where('user_id', $user_id)->get();
        }
        return $query->where('cookie_id', Cart::getCookieId())->get();
    }


    public function create($data)
    {
        return DB::transaction(function () use ($data) {

            $product = $this->productRepository->checkStock($data['product_id'] , $data['quantity']) ;

            $cart = Cart::where('product_id', $data['product_id'])
                    ->where(function ($q) use ($data) {
                        if (auth()->check()) {
                            $q->where('user_id',auth()->user()->id);
                        } else {
                            $q->where('cookie_id', Cart::getCookieId());
                        }
                    })
                    ->first();

            if ($cart) {
                $newQuantity = $cart->quantity + ($data['quantity'] ?? 1);
                if ($newQuantity > $product->stock) {
                    throw new Exception("الكمية المطلوبة تتجاوز المخزون المتاح");
                }
                $cart->increment('quantity', $data['quantity'] ?? 1);
                return $cart;
            }
            return Cart::create($data);
        });
    }

    public function update($request , $cart)
    {
        return DB::transaction(function () use ($request , $cart) {
            $product =$this->productRepository->checkStock($cart->product_id , $cart->quantity) ;
            $request->quantity == 1 ? $cart->increment('quantity',1) : $cart->decrement('quantity' , 1 ) ;
            if($cart->quantity <= 0 ){
                $cart->delete($cart) ;
            }
            return $cart ;
        });
    }

    public function delete(Cart $cart)
    {
        $cart->delete() ;
    }

    public function deleteAll($carts)
    {
        Cart::whereIn('id' , $carts->pluck('id'))->delete() ;
    }
}




