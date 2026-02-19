<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('customer')->attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::guard('customer')->user();
            // Eager load the 'detail' relationship to ensure it's available.


            // Merge session cart with database cart
            $sessionCart = session()->get('cart', []);
            if (!empty($sessionCart)) {
                $cart = Cart::firstOrCreate(
                    ['iduser' => $user->id, 'status' => 1],
                    ['total' => 0.00]
                );

                foreach ($sessionCart as $cartKey => $itemData) {
                    $item = CartItem::firstOrCreate(
                        [
                            'idcart' => $cart->id,
                            'idproduk' => $itemData['idproduk'],
                            'id_jenis' => $itemData['id_jenis'],
                            'id_ukuran' => $itemData['id_ukuran'],
                        ],
                        [
                            'qty' => 0,
                            'harga' => $itemData['harga'],
                            'weight' => $itemData['weight'],
                            'length' => $itemData['length'],
                            'width' => $itemData['width'],
                            'height' => $itemData['height'],
                            'gros' => $itemData['gros'],
                        ]
                    );

                    $item->qty += $itemData['qty'];
                    $item->save();
                }

                // Clear session cart
                session()->forget('cart');
            }

            if ($user->detail && $user->detail->idcountry == 1) {
                return redirect()->intended('/id');
            } else {
                return redirect()->intended('/en');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->flush();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
