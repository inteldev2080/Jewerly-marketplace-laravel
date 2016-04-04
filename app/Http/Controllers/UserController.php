<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Country;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(int $id_user)
    {
        $user = User::findOrFail($id_user);
        $shipping_address = UserAddress::find(auth()->user()->address_shipping);
        $billing_address = UserAddress::find(auth()->user()->address_billing);
        $this->authorize('seeInfo', $user);
        return view('users.index', ['user'=>$user, 'shipping'=> $shipping_address, 'billing'=>$billing_address]);
    }

    public function edit()
    {
        $shipping_address = UserAddress::find(auth()->user()->address_shipping);
        $billing_address = UserAddress::find(auth()->user()->address_billing);
        $countries = Country::all(['name', 'code']);
        // dd($countries);
        return view('users.edit', ['shipping'=> $shipping_address, 'billing'=>$billing_address, 'countries' => $countries]);
    }

    public function editPassword()
    {
        return view('users.edit_password');
    }

    public function update(UpdateUserRequest $req)
    {
       auth()->user()->update($req->all());
       // Save or Update Shipping Address
       if (auth()->user()->address_shipping) {
           $address1 = UserAddress::find(auth()->user()->address_shipping);
        } else {
            $address1 = new UserAddress;
        }
        $address1->user_id = Auth()->user()->id;
        $address1->address = $req->shipping_address1;
        $address1->address2 = $req->shipping_address2;
        $address1->city = $req->shipping_city;
        $address1->state = $req->shipping_state;
        $address1->country = $req->shipping_country;
        $address1->postal_code = $req->shipping_pin_code;
        if (auth()->user()->address_shipping) {
            $address1->update();
        } else {
            $address1->save();
        }
        
        auth()->user()->update(['address_shipping' => $address1->id]);

        if (!$req->billing_address1 && !$req->billing_address2 && !$req->billing_city && !$req->billing_country && !$req->billing_state && !$req->billing_pin_code) {
            if (auth()->user()->address_billing) {
                $address2 = UserAddress::find(auth()->user()->address_billing)->delete();
                auth()->user()->update(['address_billing' => null]);
            }
            return redirect()->route('user.index', auth()->user()->id);
        }

        if (!$req->billing_address1 || !$req->billing_city || !$req->billing_country || !$req->billing_state || !$req->billing_pin_code) {
            return redirect()->route('user.index', auth()->user()->id);
        }

        if (auth()->user()->address_billing) {
            $address2 = UserAddress::find(auth()->user()->address_billing);
        } else {
            $address2 = new UserAddress;
        }
        $address2->user_id = auth()->user()->id;
        $address2->address = $req->billing_address1;
        $address2->address2 = $req->billing_address2;
        $address2->city = $req->billing_city;
        $address2->state = $req->billing_state;
        $address2->country = $req->billing_country;
        $address2->postal_code = $req->billing_pin_code;
        if (auth()->user()->address_billing) {
            $address2->update();
        } else {
            $address2->save();
        }
        auth()->user()->update(['address_billing' => $address2->id]);

        return redirect()->route('user.index', auth()->user()->id);
    }

    public function updatePassword(UpdateUserPasswordRequest $req)
    {
        auth()->user()->update([
            'password' => bcrypt($req->new_password)
        ]);
        return redirect()->route('user.index', auth()->user()->id)->with('message', 'Password was Successfully Changed!');
    }

    public function delete()
    {
        auth()->user()->delete();
        return redirect()->route('index');
    }
}