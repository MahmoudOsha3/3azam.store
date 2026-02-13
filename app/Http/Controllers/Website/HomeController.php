<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Product;
use App\Repositories\Api\CategoriesRepository;

class HomeController extends Controller
{
    protected $categoryRepository ;

    public function __construct(CategoriesRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $products = Product::where('status' , 'active')->take(10)->get() ;
        $categories = $this->categoryRepository->getCategories() ;
        return view('pages.website.home.index', compact('products' , 'categories'));
    }
}
