<?php
/**
 * Created by PhpStorm.
 * User: kexue
 * Date: 2018/5/14
 * Time: 上午11:33
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderGoods extends Model
{
    //protected $connection = 'mysql_user';

    protected $table = 'order_goods';

    protected $primaryKey = 'id';
}