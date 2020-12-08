<?php

namespace App\Controllers;

use App\Models\Cart;
use ManaPHP\Rest\Controller;

class CartController extends Controller
{
    public function indexAction()
    {
        $user_id = $this->identity->getId();
        if ($user_id < 0) {
            return '用户未授权登录';
        }
        return Cart::where(['user_id' => $user_id])->all();
    }

    public function updateAction()
    {
        $user_id = $this->identity->getId();
        if ($user_id < 0) {
            return '用户未授权登录';
        }
        $product_id = input('product_id', ['int', 'default' => 0]);
        $count = input('count', ['int', 'default' => 0]);

        $cart = Cart::first(['product_id' => $product_id, 'user_id' => $user_id]);
        $cart->count = $count;
        return $cart->update();
    }
}
