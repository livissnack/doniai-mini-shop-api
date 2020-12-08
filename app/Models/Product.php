<?php
namespace App\Models;

use ManaPHP\Db\Model;

/**
 * Class App\Models\Product
 */
class Product extends Model
{
    public $product_id;
    public $name;
    public $image;
    public $price;
    public $source;
    public $count;
    public $content;
    public $is_up;
    public $updator_name;
    public $updated_time;
    public $creator_name;
    public $created_time;

    /**
     * @return string
     */
    public function getTable()
    {
        return 'product';
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return 'product_id';
    }
}