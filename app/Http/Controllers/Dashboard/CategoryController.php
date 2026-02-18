<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CategoryRequest;
use App\Models\Category;
use App\Traits\ManageApiTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use ManageApiTrait ;

    public function __construct() {
    }

    public function view(){
        $this->authorize('viewAny' , Category::class ) ;
        $categories = Category::all() ; // for select form
        return view('pages.dashboard.categories.index' , compact('categories'));
    }

    public function index()
    {
        $this->authorize('viewAny' , Category::class ) ;
        $categories = Category::with('parent')->latest()->get() ;
        return $this->successApi($categories , 'Categories fetched successfully') ;
    }


    public function store(CategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $imageName = Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('', $imageName, 'categories');
            $data['image'] = $imageName;
        }
        $category = Category::create($data);

        return $this->createApi($category, 'Category created successfully');
    }


    public function show(Category $category)
    {
        $category = $category->load('meals');
        return response()->json(['data' => $category] , 200) ;
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {

            if ($category->image && Storage::disk('categories')->exists($category->image)) {
                Storage::disk('categories')->delete($category->image);
            }

            $imageName = Str::uuid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('', $imageName, 'categories');

            $data['image'] = $imageName;
        }

        $category->update($data);

        return $this->successApi($category, 'Category updated successfully');
    }


    public function destroy(Category $category)
    {
        $category->delete();
        return $this->successApi(null , 'Category deleted successfully') ;
    }

}
