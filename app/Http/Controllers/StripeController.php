<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Stripe\StripeClient;

class StripeController extends Controller
{


    public function index()
    {
        return view('index');
    }

    public function handle(Request $request) //checkout
    {
        $user = auth('sanctum')->user();
        $OrdersId = Order::where('user_id', $user->id)
            ->orwhere('user_ip', $request->ip())->where('status', 'waiting')
            ->pluck('id');

        $stripe = new StripeClient(config(key: 'stripe.sk'));
        $checkout_session = $stripe->checkout->sessions->create(
            [
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'order_ids' => $OrdersId,
                        'amount' => 2000,
                    ],
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.confirm'),
                'cancel_url' =>  route('payment.index'),
            ]
        );
        //return Redirect::away($session->url);
        return redirect($checkout_session->url);
    }
    public function confirm(Request $request) //success
    {
        try {
            DB::beginTransaction();
            $sessionId = $request->get('session_id');
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if ($session) {
                // Payment was successful
                // Retrieve order details from session metadata or other source
                $orderIds = json_decode($session->metadata->order_ids);
                $amount = $session->amount_total;
                $currency = $session->currency;
                $transactionId = $session->payment_intent;

                // Store the payment details in the database
                $payment = new Payment();
                $payment->amount = $amount / 100; // Stripe amounts are in cents
                $payment->currency = $currency;
                $payment->order_ids = implode(',', $orderIds);
                $payment->transaction_id = $transactionId;
                $payment->save();

                // Update order statuses or perform other actions as needed
                Order::whereIn('id', $orderIds)->update(['status' => 'paid']);
                DB::commit();
                return response()->json(['status' => 'Payment confirmed successfully']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'Payment not confirmed'], 400);
        }
    }
}
