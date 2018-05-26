<?php
/**
 * Created by PhpStorm.
 * User: kexue
 * Date: 2018/5/14
 * Time: 上午9:43
 */
namespace App\Console\Commands;

use App\Libs\Xunsearch\Search;
use App\Models\Goods;
use App\Models\Order;
use App\Models\User;
use Illuminate\Console\Command;

/**
 * 迅搜索引平滑重建工具
 * Class RefreshIndex
 * @package App\Console\Commands
 */
class RefreshIndex extends Command
{

    /**
     * 执行命令
     * @var string
     */
    protected $signature = 'refresh:index {action}';


    /**
     * 任务描述
     * @var string
     */
    protected $description = '刷新索引';

    /**
     * 获取结果集条数
     * @var int
     */
    protected $limit = 1000;


    /**
     * @throws \XSException
     */
    public function handle()
    {
        $action  =  $this->argument('action');
        if (!in_array($action, ['orders', 'users', 'goods', 'all'])) {
            $this->error('错误的参数');
            return;
        }

        if ($action != 'all') {
            $this->$action();
            return;
        }

        $this->users();
        $this->orders();
        $this->goods();
    }

    /**
     * 用户
     * @throws \XSException
     */
    protected function users()
    {
        $projectName = explode('::',__METHOD__)[1];
        $search = new Search($projectName);
        $search->beginRebuild();

        $count  = User::count();
        $loops  = ceil($count / $this->limit);
        $lastId = 0;

        for ($i = 0; $i < $loops; $i++) {
            $users = User::select(['id', 'nickname', 'mobile', 'store_id'])
                ->where('id', '>', $lastId)
                ->limit($this->limit)
                ->get();

            $data = [];
            foreach ($users as $user) {
                $nickname = $this->decodeNickname($user->nickname);
                if (empty($nickname)) {
                    continue;
                }
                $data[] = [
                    'user_id'   => $user->id,
                    'nickname'  => $nickname,
                    'mobile'    => $user->mobile,
                    'store_id'  => $user->store_id
                ];
            }

            if ($data) {
                $search->addIndex($data);
            }
            $lastId = $user->id;
            sleep(1);
        }
        $search->endRebuild();
    }

    /**
     * 订单
     * @throws \XSException
     */
    protected function orders()
    {
        $projectName = explode('::',__METHOD__)[1];
        $search = new Search($projectName);
        $search->beginRebuild();

        $count = Order::count();
        $loops  = ceil($count / $this->limit);
        $lastId = 0;
        for ($i = 0; $i < $loops; $i++) {
            $orders = Order::select(['id', 'order_no', 'accept_name', 'mobile', 'store_id', 'user_id'])
                ->where('id', '>', $lastId)
                ->with('goods')
                ->limit($this->limit)
                ->get();

            $data = [];
            foreach ($orders as $order) {
                $goodsName = [];
                foreach ($order->goods as $goodsInfo) {
                    $goodsName[] = $goodsInfo->name;
                }
                $goodsName = implode(' ', $goodsName);
                if (!$goodsName) {
                    continue;
                }
                $data[] = [
                    'order_id'  => $order->id,
                    'user_id'   => $order->nickname,
                    'mobile'    => $order->mobile,
                    'store_id'  => $order->store_id,
                    'consignee' => $order->accept_name,
                    'user_id'   => $order->user_id,
                    'goods_name'=> $goodsName,
                ];
            }

            if ($data) {
                $search->addIndex($data);
            }
            $lastId = $order->id;
            sleep(1);
        }
        $search->endRebuild();
    }

    /**
     * 商品
     * @throws \XSException
     */
    protected function goods()
    {
        $projectName = explode('::',__METHOD__)[1];
        $search = new Search($projectName);
        $search->beginRebuild();

        $count = Goods::onSale()->count();
        $loops = ceil($count / $this->limit);
        $lastId = 0;

        for ($i = 0; $i < $loops; $i++) {
            $goodsList = Goods::select(['id', 'name', 'store_id'])
                ->where('id', '>', $lastId)
                ->onSale()
                ->limit($this->limit)
                ->get();

            $data = [];
            foreach ($goodsList as $goods) {
                if (empty($goods->name)) {
                    continue;
                }
                $data[] = [
                    'goods_id' => $goods->id,
                    'goods_name' => $goods->name,
                    'store_id' => $goods->store_id
                ];
            }

            if ($data) {
                $search->addIndex($data);
            }
            $lastId = $goods->id;
            sleep(1);
        }
        $search->endRebuild();
    }

    /**
     * decode带有表情的昵称
     * @param $nickname
     * @return mixed'
     */
    protected function decodeNickname($nickname)
    {
        $tmp = json_decode($nickname);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $tmp;
        } else {
            return $nickname;
        }
    }
}