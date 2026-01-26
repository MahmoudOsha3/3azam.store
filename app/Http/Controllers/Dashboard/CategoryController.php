<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CategoryRequest;
use App\Models\Category;
use App\Traits\ManageApiTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ManageApiTrait ;

    public function __construct() {
    }

    public function view(){
        $categories = Category::all() ; // for select form
        return view('pages.dashboard.categories.index' , compact('categories'));
    }

    public function index()
    {
        $categories = Category::with('parent')->latest()->get() ;
        return $this->successApi($categories , 'Categories fetched successfully') ;
    }


    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated()) ;
        return $this->createApi($category ,'Category created successfully');
    }

    public function show(Category $category)
    {
        $category = $category->load('meals');
        return response()->json(['data' => $category] , 200) ;
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category = $category->update($request->validated()) ;
        return $this->successApi($category , 'Category updated successfully') ;
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return $this->successApi(null , 'Category deleted successfully') ;
    }

}
