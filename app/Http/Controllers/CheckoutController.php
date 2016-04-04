<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelCheckoutRequest;
use App\Http\Requests\PlaceOrderRequest;
use App\Http\Requests\StorePaymentIntentRequest;
use App\Models\Country;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductsVariant;
use App\Models\ShippingOption;
use App\Models\ProductsTaxOption;
use App\Models\User;
use App\Models\UserAddress;
use Auth;
use Error;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function index()
    {
        $products = Cart::instance('default')->content();

        $isIncludeShipping = false;

        foreach ($products as $product) {
            if (!$product->model->is_digital && !$product->model->is_virtual) {
                $isIncludeShipping = true;
            }
        }

        if ($isIncludeShipping) {
            return redirect()->route('checkout.shipping.get');
        }

        return redirect()->route('checkout.billing.get');
    }

    public function store(Request $request)
    {
        try
        {
            $this->validate($request, (new PlaceOrderRequest)->rules());

            $orderId = $request->session()->get('order_id', '');

            $order = Order::where('order_id', $orderId)->first();

            if (!$order) {
                $order = new Order;

                if ($request->buy_now_mode) {
                    Cart::instance('buy_now');
                } else {
                    Cart::instance('default');
                }

                $cartItems = Cart::content();
                $total = 0;

                foreach ($cartItems as $item) {
                    $orderItem = new OrderItem;

                    $orderItem->order_id = $orderId;
                    $orderItem->product_id = $item->id;
                    $orderItem->product_name = $item->model->name;
                    $orderItem->price = $item->price * 100;
                    $orderItem->quantity = $item->qty;
                    $orderItem->product_variant = 0;

                    $total = $orderItem->price * $orderItem->quantity;

                    if (isset($item->options['id'])) {
                        $orderItem->product_variant = $item->options['id'];

                        // $productVariant = ProductsVariant::find($item->options['id']);
                        $orderItem->product_variant_name = $item->options['name'];;
                    }

                    $orderItem->save();
                }

                $order->total = $total;

                $shipping_option_id = $request->session()->get('shipping_option_id', 0);

                if ($shipping_option_id)
                    $total += ShippingOption::find($shipping_option_id)->price;

                $order->grand_total = $total;
            }

            $order->order_id = $orderId;
            $order->status_payment = 1;
            $order->user_id = Auth::user()->id;
            $order->billing_address1 = $request->session()->get('billing_address1', '');
            $order->billing_address2 = $request->session()->get('billing_address2', '');
            $order->billing_city = $request->session()->get('billing_city', '');
            $order->billing_state = $request->session()->get('billing_state', '');
            $order->billing_zipcode = $request->session()->get('billing_zipcode', '');
            $order->billing_country = $request->session()->get('billing_country', '');
            $order->billing_phonenumber = $request->session()->get('billing_phonenumber', '');
            $order->shipping_address1 = $request->session()->get('shipping_address1', '');
            $order->shipping_address2 = $request->session()->get('shipping_address2', '');
            $order->shipping_city = $request->session()->get('shipping_city', '');
            $order->shipping_state = $request->session()->get('shipping_state', '');
            $order->shipping_zipcode = $request->session()->get('shipping_zipcode', '');
            $order->shipping_country = $request->session()->get('shipping_country', '');
            $order->shipping_phonenumber = $request->session()->get('shipping_phonenumber', '');
            $order->shipping_option_id = $request->session()->get('shipping_option_id', 0);
            $order->save();

            Cart::erase(auth()->id());

            Cart::destroy();
        } catch (Exception | Error $e) {
            return response(['ok' => false, 'error' => $e->getMessage()], 401);
        }

        return response(['ok' => true], 200);
    }

    public function createPaymentIntent(StorePaymentIntentRequest $req)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        header('Content-Type: application/json');

        if ($req->buy_now_mode) {
            Cart::instance('buy_now');
        } else {
            Cart::instance('default');
        }

        try {

            $orderId = Auth::user()->id . strtoupper(uniqid());
            $req->session()->put('order_id', $orderId);

            $description = env('APP_NAME') . ' Order#' . $orderId;

            $total = Cart::total(2, '.', '') * 100;
            $shipping_option_id = $req->session()->get('shipping_option_id', 0);

            if ($shipping_option_id)
                $total += ShippingOption::find($shipping_option_id)->price;

            $taxPrice = 0;
            foreach (Cart::content() as $product) {
                $taxPrice += ($product->price * $product->qty * $product->model->taxPrice() / 100);
            }

            $total += floor($taxPrice + 0.5);

            // Create a PaymentIntent with amount and currency
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $total,
                'currency' => 'usd',
                'customer' => null,
                'description' => $description,
                'statement_descriptor' => substr($description, 0, 22),
                'shipping' => [
                    'address' => [
                        'city' => $req->session()->get('billing_city'),
                        'state' => $req->session()->get('billing_state'),
                        'country' => $req->session()->get('billing_country'),
                        'postal_code' => $req->session()->get('billing_zipcode'),
                        'line1' => $req->session()->get('billing_address1'),
                        'line2' => $req->session()->get('billing_address2'),
                    ],
                    'name' => Auth::user()->first_name . " " . Auth::user()->last_name,
                    'phone' => $req->session()->get('billing_phonenumber'),
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];

            return $output;
        } catch (Error $e) {
            http_response_code(500);
            return ['error' => $e->getMessage()];
        }
    }

    public function cancel(CancelCheckoutRequest $req)
    {
        $orderId = $req->session()->get('order_id');
        $error = $req->error;

        $order = Order::where('order_id',  $orderId)->first();

        if ($req->buy_now_mode) {
            Cart::instance('buy_now');
        } else {
            Cart::instance('default');
        }

        $order->restoreCartItems();
        Cart::store(auth()->id());

        if ($error['type'] == 'validation_error') {
            Order::where('order_id', $orderId)->delete();
            OrderItem::where('order_id', $orderId)->delete();

            return response(null, 204);
        }


        $order->status_payment = 3;
        $order->payment_intent = $error['payment_intent']['id'];
        $order->status_payment_reason = $error['code'];
        $order->save();

        return response(null, 204);
    }

    public function paymentFinished(Request $request)
    {

        $orderId = $request->session()->get('order_id');

        $request->session()->forget('order_id');

        $order = Order::where('order_id', $orderId)->first();

        $order->status_payment = 2; // paid
        $order->payment_intent = $request->get('payment_intent');
        $order->save();

        return redirect()->route('orders.show', $orderId);
    }

    public function getShipping()
    {

        $countries = Country::all(['name', 'code']);
        $shippings = ShippingOption::all();
        $products = Cart::instance('default')->content();
        $shipping_address = auth()->user()->address_shipping ?  UserAddress::find(auth()->user()->address_shipping) : "NULL";

        return view('checkout.shipping')->with(['countries' => $countries, 'shippings' => $shippings, 'products' => $products, 'locale' => 'checkout','shipping'=> $shipping_address ]);
    }

    public function postShipping(Request $request)
    {
        // store data to session
        $request->session()->put('shipping_address1', $request->address1);
        $request->session()->put('shipping_address2', $request->address2);
        $request->session()->put('shipping_city', $request->city);
        $request->session()->put('shipping_state', $request->state);
        $request->session()->put('shipping_country', $request->country);
        $request->session()->put('shipping_zipcode', $request->pin_code);
        $request->session()->put('shipping_phonenumber', $request->phone);
        $request->session()->put('shipping_option_id', $request->shipping_option);
        $request->session()->put('shipping_price', ShippingOption::find($request->shipping_option)->price);
        if ($request->isRemember) {

            $userAddress = UserAddress::where('user_id', Auth::user()->id)->first();

            if ($userAddress) {

                $userAddress = UserAddress::find($userAddress->id);
            } else {
                $userAddress = new UserAddress;
                $userAddressInfo = UserAddress::create([
                    'user_id' => Auth::user()->id,
                    'address' => $request->address1,
                    'address2' => $request->address2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'country' => $request->country,
                    'postal_code' =>  $request->pin_code,
                    'phone' => $request->phone,
                ]);
    
                $user = User::where('id', Auth::user()->id)->first();
                $user->address_shipping =  $userAddressInfo->id;
                $user->save();
            }

         
            $userAddress->address = $request->address1;
            $userAddress->address2 = $request->address2;
            $userAddress->city = $request->city;
            $userAddress->state = $request->state;
            $userAddress->country = $request->country;
            $userAddress->postal_code = $request->pin_code;
            $userAddress->phone = $request->phone;
            $userAddress->update();
        }
        
        return redirect()->route('checkout.billing.get');
    }

    public function getBilling()
    {
        $products = Cart::instance('default')->content();

        $isIncludeShipping = false;

        foreach ($products as $product) {
            if (!$product->model->is_digital && !$product->model->is_virtual) {
                $isIncludeShipping = true;
            }
        }

        $countries = Country::all(['name', 'code']);
        $products = Cart::instance('default')->content();
        $billing_address = auth()->user()->address_billing ?  UserAddress::find(auth()->user()->address_billing) : "NULL";

        return view('checkout.billing')->with(['countries' => $countries, 'products' => $products, 'locale' => 'checkout', 'isIncludeShipping' => $isIncludeShipping, 'billing'=> $billing_address,]);
    }

    public function postBilling(Request $request)
    {
        $request->session()->put('billing_address1', $request->address1);
        $request->session()->put('billing_address2', $request->address2);
        $request->session()->put('billing_city', $request->city);
        $request->session()->put('billing_state', $request->state);
        $request->session()->put('billing_country', $request->country);
        $request->session()->put('billing_zipcode', $request->pin_code);
        $request->session()->put('billing_phonenumber', $request->phone);

        if ($request->isRemember) {
            $userAddress = UserAddress::where('id', Auth::user()->address_billing)->first();
            if ($userAddress) {
                $userAddress = UserAddress::find($userAddress->id);
            } else {
                $userAddress = new UserAddress;
                $userAddress2Info = UserAddress::create([
                    'user_id' => Auth::user()->id,
                    'address' => $request->address1,
                    'address2' => $request->address2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'country' => $request->country,
                    'postal_code' =>  $request->pin_code,
                    'phone' => $request->phone,
                ]);
                $user = User::where('id', Auth::user()->id)->first();
                $user->address_billing =  $userAddress2Info->id;
                $user->save();
            }
          
            $userAddress->address = $request->address1;
            $userAddress->address2 = $request->address2;
            $userAddress->city = $request->city;
            $userAddress->state = $request->state;
            $userAddress->country = $request->country;
            $userAddress->postal_code = $request->pin_code;
            $userAddress->phone = $request->phone;

            $userAddress->update();
        }
          
        return redirect()->route('checkout.payment.get');
    }

    public function getPayment(Request $request)
    {
        $instance = isset($buy_now_mode) && $buy_now_mode == 1 ? 'buy_now' : 'default';

        $products = Cart::instance('default')->content();

        $isIncludeShipping = false;

        foreach ($products as $product) {
            if (!$product->model->is_digital && !$product->model->is_virtual) {
                $isIncludeShipping = true;
            }
        }

        $products = Cart::instance($instance)->content();
        return view('checkout.payment')->with(['products' => $products, 'locale' => 'checkout', 'isIncludeShipping' => $isIncludeShipping]);;
    }
}
