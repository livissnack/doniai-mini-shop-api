<?php

namespace App\Controllers;

use App\Models\Review;
use ManaPHP\Rest\Controller;

class ReviewController extends Controller
{
    public function indexAction()
    {
        $product_id = input('product_id', ['int', 'default' => 0]);
        return Review::where(['product_id' => $product_id])->with(['user' => ['user_id', 'nickName']])->paginate();
    }

    public function createAction()
    {
        $user_id = $this->identity->getId();
        if ($user_id < 0) {
            return '用户未授权登录';
        }
        $product_id = input('product_id', ['int']);
        $content = input('content', ['string', 'default' => '']);
        $images = input('images', ['string', 'default' => '']);
        if (is_null($content)) {
            return '评价内容不能为空';
        }

        try {
            $success = false;
            $this->db->begin();

            $review = new Review();

            $review->user_id = $user_id;
            $review->product_id = $product_id;
            $review->content = $content;
            $review->images = $images;
            $review->create();

            $success = true;

        } catch (\Throwable $throwable) {
            $this->logger->error($throwable);
            return $throwable;
        } finally {
            if ($success) {
                $this->db->commit();
                return ['code' => 0, 'message' => '评论成功'];
            } else {
                $this->db->rollback();
                return '评论失败';
            }
        }
    }
}
