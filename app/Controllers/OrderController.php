<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\PayLog;
use App\Models\PayLogType;
use App\Models\Product;
use App\Models\User;
use ManaPHP\Exception;
use ManaPHP\Rest\Controller;

class OrderController extends Controller
{
    public function indexAction()
    {
        $user_id = $this->identity->getId();
        if ($user_id < 0) {
            return '用户未授权登录';
        }
        return Order::where(['user_id' => $user_id])->paginate();
    }

    public function createAction()
    {
        $user_id = $this->identity->getId();
        if ($user_id < 0) {
            return '用户未授权登录';
        }
        $product_list = input('product_list', ['array', 'default' => []]);
        if (is_null($product_list)) {
            return '商品列表为空';
        }

        try {
            $success = false;
            $this->db->begin();

            $pay_amount = 0;

            foreach ($product_list as $key => $value) {
                $product = Product::get($value['product_id']);
                $product_list[$key]['name'] = $product->name;
                $product_list[$key]['price'] = $product->price;
                $pay_amount += $product->price * $value['count'];
            }

            $user = User::get($user_id);

            $log_type = PayLogType::first(['code' => 'wechat.pay']);

            if ($pay_amount < 1) {
                throw new Exception('支付金额异常');
            }

            $log = new PayLog();

            $log->type_id = $log_type->id;
            $log->type_name = $log_type->name;
            $log->user_id = $user_id;
            $log->amount = $pay_amount;
            $log->pay_info = json_encode($product_list);
            $log->create();

            $order = new Order();

            $order->status = Order::STATUS_RECKONED;
            $order->amount = $pay_amount;
            $order->total_amount = $pay_amount;
            $order->type = Order::TYPE_VIRTUAL;
            $order->is_show = Order::ENABLED_SHOW;
            $order->user_id = $user_id;
            $order->send_name = 'system';
            $order->delivery_time = time();
            $order->product_list = $product_list;
            $order->create();

            $success = true;
            $appid = param_get('wechat_mini_app_id');
            $str = "恭喜你支付成功<a data-miniprogram-appid='$appid' data-miniprogram-path='pages/index/index'>多尼爱商城欢迎你</a>";
            $send_data = [
                'touser' => $user->openid,
                'msgtype' => 'text',
                'text' => [
                    'content' => $str
                ],
            ];
            $this->wechatService->send_custom_msg($send_data);
        } catch (\Throwable $throwable) {
            $this->logger->error($throwable);
            return $throwable;
        } finally {
            if ($success && isset($data)) {
                $this->db->commit();
                return ['code' => 0, 'message' => '支付成功'];
            } else {
                $this->db->rollback();
                return '支付失败';
            }
        }
    }

    public function detailAction()
    {
        $user_id = $this->identity->getId();
        if ($user_id < 0) {
            return '用户未授权登录';
        }
        $order_id = input('order_id', ['int', 'default' => 0]);
        return Order::first(['order_id' => $order_id, 'user_id' => $user_id]);
    }
}
