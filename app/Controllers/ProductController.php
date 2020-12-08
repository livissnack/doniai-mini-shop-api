<?php

namespace App\Controllers;

use App\Models\Product;
use ManaPHP\Rest\Controller;

class ProductController extends Controller
{
    public function indexAction()
    {
        return Product::where(['is_up' => 1])->paginate();
    }

    public function detailAction()
    {
        $product_id = input('product_id', ['int', 'default' => 0]);
        return Product::first(['product_id' => $product_id]);
    }
}
