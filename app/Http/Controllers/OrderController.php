<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\OrderRequest;
use App\Models\{Order, Product, User};
use App\Traits\ResponsesTrait;
use Illuminate\Support\Facades\{Auth, DB};
use App\Events\OrderCreated;


use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ResponsesTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();
        $this->authorize('viewAny', Order::class);
        $user_ip = $request->ip();
        $status = $request->status;
        if ($user)
            $order = $user->orders;
        elseif ($user = User::query()->where('ip', $user_ip)->firstOrFail()) {
            $order = $user->ordersByIP;
        }
        if ($status)
            return $order->where('status', $status);
        return $order->groupBy('status');
    }

    /**
     * Store a newly created resource in storage.
     */
    function store(OrderRequest $request)
    {
        try {
            // I use this form because DB::transaction form doesn't work for me
            DB::beginTransaction();
            $selected_product = Product::findOrFail($request->product_id);
            $user_ip = $request->ip();
            $user = auth('sanctum')->user();

            $existed_item = Order::where('product_id', $request->product_id)
                ->where(function ($query) use ($user, $user_ip) {
                    if ($user) {
                        $query->where('user_id', $user->id);
                    } else {
                        $query->where('user_ip', $user_ip);
                    }
                })
                ->where('status', 'waiting')
                ->first();

            //if the item exist previously
            if ($existed_item) {
                $existed_item->increment('order_quantity', $request->order_quantity);
                $message = 'Order updated successfully';
            } else {
                $existed_item = Order::create([
                    'product_id' => $request->product_id,
                    'order_quantity' => $request->order_quantity,
                    'created_at' => now()
                    //no need to 'status'=>waiting because it is waiting by default
                ]);
                if ($user)
                    $existed_item->user_id = $user->id;
                else
                    $existed_item->user_ip = $user_ip;
                $existed_item->save();
                $message = 'Order created successfully';

                // Dispatch the event
                event(new OrderCreated($existed_item));
            }
            $selected_product->decrement('product_quantity', $request->order_quantity);

            DB::commit();

            // Return the success response
            return $this->sendSuccess($existed_item, $message, $existed_item->wasRecentlyCreated ? 201 : 200);
        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollback();

            // Handle the exception or log it
            return $this->sendFail('Failed to create/update order: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        $user = auth()->user();
        $this->authorize('view', $order);
        return $this->showData($order, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        $user = auth()->user();
        $this->authorize('update', $order);
        $request->validate(
            [
                'status' => 'required|string',
                // Add other validation rules as needed


                /*$request->validate([
            'order_quantity' => [
                'required',
                'integer',
                'min:1',*/
                //it is not work
                /*function ($attribute, $value, $fail) use ($request) {
                    $order = Order::findOrFail($request);
                    $product = Product::findOrFail($order->product_id);

                    if ($value > $product->product_quantity) {
                        $fail("The $attribute must be less than or equal to $product->product_quantity.");
                    }
                },*/
            ],
        );
        try {
            DB::beginTransaction();
            /*$user_ip = $request->ip();
            $user = auth('sanctum')->user();
            $order = Order::where('id', $id)
                ->where(function ($query) use ($user, $user_ip) {
                    if ($user) {
                        $query->where('user_id', $user->id);
                    } else {
                        $query->where('user_ip', $user_ip);
                    }
                })
                ->where('status', 'waiting')
                ->first();
            $old_order_quantity = $order->order_quantity;
            $new_order_quantity = $request->order_quantity;
            $order->update(['order_quantity' => $new_order_quantity]);
            $order->save();
            // Calculate the difference in order quantity
            $newOrderQuantity = $request->order_quantity - $old_order_quantity;

            // Update the product quantity
            $product = Product::findOrFail($order->product_id);
            $product->product_quantity -= $newOrderQuantity;
            $product->save();*/
            $order->status = $request->input('status');
            $order->save();
            DB::commit();

            // Return the updated order in the response
            return $this->sendSuccess($order, 'Order status updated successfully', 200);
        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions or log errors
            return $this->sendFail('Failed to update order status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $user = auth()->user();
        $this->authorize('delete', $order);
        try {
            DB::beginTransaction();
            $order = Order::findOrFail($id);

            if ($order->status == 'waiting') {
                $order->delete();
                return $this->sendSuccess(null, 'Order deleted successfully', 200);
            }
            DB::commit();
            return $this->sendFail("You cannot delete an order with status: {$order->status}");
        } catch (\Exception) {
            DB::rollBack();

            // Handle exceptions or log errors
            return $this->sendFail('Failed to delete order');
        }
    }
}
