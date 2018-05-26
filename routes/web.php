<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/user/replace', 'UserController@replace');
$router->get('/user/search', 'UserController@search');

$router->post('/goods/replace', 'GoodsController@replace');
$router->get('/goods/search', 'GoodsController@search');

$router->post('/order/replace', 'OrderController@replace');
$router->get('/order/search', 'OrderController@search');
$router->get('/order/delete', 'OrderController@delete');

$router->get('/search/filter', function () use ($router) {
    $str1 = 'Apple MacBook Pro 13.3英寸笔记本电脑 深空灰色（2017款Core i5处理器/8GB内存/128GB硬盘 MPXQ2CH/A） 😆';
    preg_match_all('/[\x{4e00}-\x{9fff}0-9a-zA-Z]+/u', $str1, $matches);
    $str2 = join(' ', $matches[0]);
    print_r($matches);
    echo "<br>";
    echo $str1;
    echo "<br>";
    echo $str2;
});

$router->get('/search/tokenizer', function () use ($router) {
    $xs = new \XS('users');
    $tokenizer = new XSTokenizerScws();
    $words = $tokenizer->getTokens('Apple MacBook Pro 13.3英寸笔记本电脑 深🌞空灰色（🌂2017款Core i5处理器/8GB内存/128GB硬盘 MPXQ2CH/A） 😆');
    print_r($words);
});

$router->get('/search/keyword', function () use ($router) {
    $xs = new \XS('users');
    //$docs1 = $xs->search->setFuzzy()->search('nickname:我的小肥猪');
    //$docs2 = $xs->search->setQuery('nickname:我的小肥猪')->getQuery();
    $docs2 = $xs->search->search('nickname:肥猪');
    echo "<pre>";
    //print_r($docs1);
    print_r($docs2);
});

$router->get('/search/add', function () use ($router) {
//    $data = array(
//        'user_id' => 10001, // 此字段为主键，是进行文档替换的唯一标识
//        'nickname' => '我的昵称很长，又被称呼为小肥猪奈非天大侠客天下无敌',
//        'store_id' => 20001,
//    );

    $data = [
        'goods_id' => 30002,
        'goods_name' => 'Apple MacBook Pro 13.3英寸笔记本电脑 深🌞空灰色（🌂2017款Core i5处理器/8GB内存/128GB硬盘 MPXQ2CH/A） 😆',
        'store_id'  => 40001,
    ];

    $doc = new \XSDocument();
    $doc->setFields($data);

    $xs = new \XS('goods');
    $res = $xs->index->update($doc);
    echo "<pre>";
    print_r($res);
});
