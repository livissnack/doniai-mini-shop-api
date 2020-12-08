<?php
namespace App\Models;

use ManaPHP\Db\Model;

/**
 * Class App\Models\Review
 */
class Review extends Model
{
    public $review_id;
    public $user_id;
    public $product_id;
    public $content;
    public $images;
    public $updator_name;
    public $updated_time;
    public $creator_name;
    public $created_time;

    /**
     * @return string
     */
    public function getTable()
    {
        return 'review';
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return 'review_id';
    }
}