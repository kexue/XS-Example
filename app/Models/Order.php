<?php
/**
 * Created by PhpStorm.
 * User: kexue
 * Date: 2018/5/14
 * Time: 上午11:33
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //protected $connection = 'mysql_user';

    protected $table = 'orders';

    protected $primaryKey = 'id';

    public function goods()
    {
        return $this->hasMany(OrderGoods::class, 'order_id', 'id')
            ->select(['id', 'goods_id', 'order_id', 'name']);
    }
}