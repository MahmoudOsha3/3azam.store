<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($productId)
    {
        $product = Product::with('category:id,name')->where(['id' => $productId , 'status' => 'active'])
                        ->firstOrfail() ;
        return view('pages.website.products.show' , compact('product'));
    }
}