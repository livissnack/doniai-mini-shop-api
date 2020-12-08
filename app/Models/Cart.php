<?php
namespace App\Models;

use ManaPHP\Db\Model;

/**
 * Class App\Models\Cart
 */
class Cart extends Model
{
    public $cart_id;
    public $user_id;
    public $product_id;
    public $image;
    public $price;
    public $count;
    public $updator_name;
    public $updated_time;
    public $creator_name;
    public $created_time;

    /**
     * @return string
     */
    public function getTable()
    {
        return 'cart';
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return 'cart_id';
    }
}