<?php

namespace App\Repositories\Api ;

use App\Models\Product;
use App\Services\Meals\ImageServices;
use Exception;
use Illuminate\Support\Facades\Cache;

// deal with DB
class ProductRepository
{
    protected $imageServices ;
    public function __construct(ImageServices $imageServices) {
        $this->imageServices = $imageServices;
    }

    public function getAll($request)
    {
        $products = Product::with('category:id,name')->filter($request)->latest()->paginate(15) ;
        return $products ;
    }

    public function create($data , $token)
    {
        if (Cache::has('product_' . $token )) {
            throw new Exception("Request duplicated" , 409) ;
        }
        Cache::put('product_'. $token, true, 30) ;
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $this->imageServices->upload($data['image']);
        }
        $product = Product::create($data);
        return $product ;
    }


    public function update(Product $product , $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $this->imageServices->update($product->image, $data['image']);
        }
        $product->update($data) ;
        return $product ;
    }

    public function delete(Product $product)
    {
        $this->imageServices->delete($product->image) ;
        $product->delete() ;
    }

    public function countProducts()
    {
        return Product::count() ;
    }

    public function checkStock($productId , $quantity)
    {
        $product = Product::where('id', $productId)
                    ->lockForUpdate()->firstOrfail() ;

        if($product->stock < $quantity ?? 1){
            throw new Exception("الكمية المطلوبة غير متاحة في المخزون منتج : $product->title") ;
        }
        return $product ;
    }

}




