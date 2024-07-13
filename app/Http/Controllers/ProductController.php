<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\{StoreProductRequest, UpdateProductRequest};
use App\Models\Product;
use App\Traits\ResponsesTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.(all products)
     */
    use ResponsesTrait;
    public function index()
    {
        $user = auth()->user();
        $this->authorize('viewAny', Product::class);
        $products = Product::all();
        return $this->showData($products, 200);
    }
    //------------------------------------------------------------------------------------------------
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // Handle the file upload
        //$imagePath = $request->file('product_image')->store('images', 'public');
        $user = auth()->user();
        $this->authorize('create', Product::class);
        try {
            DB::beginTransaction();
            // Create the new product
            $product = Product::create([
                'product_name' => $request->product_name,
                'description' => $request->description,
                'price' => $request->price,
                //'product_image' => $imagePath,
                'category_id' => $request->category_id,
            ]);
            DB::commit();
            return $this->sendSuccess($product, 'Product created successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions or log errors
            return $this->sendFail('Failed to create a new product: ' . $e->getMessage(), 500);
        }
    }
    //------------------------------------------------------------------------------------------------
    /**
     * Display the specified resource. (specified product)
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $user = auth()->user();
        $this->authorize('view', $product);
        return $this->showData($product, 200);
    }
    //------------------------------------------------------------------------------------------------
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $user = auth()->user();
        $this->authorize('update', $product);


        //$imagePath = $request->file('product_image')->store('images', 'public');
        //$product->product_image = $imagePath;
        try {
            DB::beginTransaction();
            $product->update([
                'product_name' => $request->product_name,
                'description' => $request->description,
                'price' => $request->price,
                'category_id' => $request->category_id,
            ]);
            DB::commit();
            return $this->sendSuccess($product, 'Product updated successfully', 200);
        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions or log errors
            return $this->sendFail('Failed to update the product: ' . $e->getMessage(), 500);
        }
    }
    //------------------------------------------------------------------------------------------------
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $user = auth()->user();
        $this->authorize('delete', $product);
        try {
            DB::beginTransaction();
            $product->delete();
            DB::commit();
            return $this->sendSuccess(null, 'Product deleted successfully', 200);
        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions or log errors
            return $this->sendFail('Failed to create a new product: ' . $e->getMessage(), 500);
        }
    }
}
