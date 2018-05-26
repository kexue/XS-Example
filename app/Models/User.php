<?php
/**
 * Created by PhpStorm.
 * User: kexue
 * Date: 2018/5/14
 * Time: 上午11:33
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //protected $connection = 'mysql_user';

    protected $table = 'users';

    protected $primaryKey = 'id';
}