<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Gloudemans\Shoppingcart\Facades\Cart;

class CheckoutCart extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public string $locale = 'cart', public $products = null, public string $instance = 'default')
    {
        $this->products = $products ?? Cart::instance($instance)->content();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.checkout-cart');
    }
}