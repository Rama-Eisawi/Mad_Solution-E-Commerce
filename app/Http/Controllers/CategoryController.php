<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\{StoreCategoryRequest, UpdateCategoryRequest};
use App\Models\Category;
use App\Traits\ResponsesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    use ResponsesTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $this->authorize('viewAny', Category::class);
        $categories = Category::whereNull('parent_id')->with('allChildren.products', 'products')->get();
        return $this->showData($categories, 200);
    }
    //------------------------------------------------------------------------------------------------
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $user = auth()->user();
        $this->authorize('create', Category::class);
        $category = Category::create([
            'category_name' => $request->category_name,
            'parent_id' => $request->parent_id
        ]);
        return $this->sendSuccess($category, 'Category created successfully', 201);
    }
    //------------------------------------------------------------------------------------------------
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        $user = auth()->user();
        $this->authorize('view', $category);
        $category = Category::with('allChildren.products', 'products')->findOrFail($id);
        return $this->showData($category, 200);
    }
    //------------------------------------------------------------------------------------------------
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $user = auth()->user();
        $this->authorize('create', $category);
        $category->update([
            'category_name' => $request->category_name,
            'parent_id' => $request->parent_id,
        ]);
        return $this->sendSuccess($category, 'Category updated successfully', 200);
    }
    //------------------------------------------------------------------------------------------------
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $user = auth()->user();
        $this->authorize('create', $id);
        $category->delete();
        return $this->sendSuccess(null, 'Category deleted successfully', 200);
    }
}
