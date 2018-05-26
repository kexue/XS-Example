<?php
/**
 * Created by PhpStorm.
 * User: kexue
 * Date: 2018/5/10
 * Time: 下午6:50
 */
namespace App\Http\Controllers;

use App\Libs\ModRes;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    protected $projectName = 'orders';

    /**
     * 按名称和店铺ID搜索
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function search(Request $request)
    {
        $this->validate($request, [
            'goods_name'  => 'between:1,100',
            'consignee'   => 'between:1,100',
            'store_id'    => 'integer|min:1',
            'mobile'      => 'integer|digits:11',
            'user_id'     => 'integer|min:1',
            'page'        => 'integer|min:1',
            'per_page'    => 'integer|min:10',
        ]);
        $storeId    = $request->input('store_id');
        $userId     = $request->input('user_id');
        $page       = $request->input('page', 1);
        $perPage    = $request->input('per_page', 20);
        $goodsName  = $request->input('goods_name');
        $consignee  = $request->input('consignee');
        $mobile     = $request->input('mobile');
        if (!$userId && !$storeId) {
            return ModRes::retFailed(ModRes::CODE_INVALID_PARAMS, 'user_id, store_id 参数需必填一项');
        }
        if (!$mobile && !$consignee && !$goodsName) {
            return ModRes::retFailed(ModRes::CODE_INVALID_PARAMS, 'mobile, consignee, goods_name 参数需必填一项');
        }

        $where = [
            'store_id'      => $storeId,
            'goods_name'    => $goodsName,
            'user_id'       => $userId,
            'consignee'     => $consignee,
            'mobile'        => $mobile,
        ];
        $keyword = $this->search->genKeyword($where);
        $sorts   = ['order_id' => false];
        $data    = $this->search->searchAll($keyword, $sorts, $page, $perPage);

        $list  = [];
        foreach ($data['list'] as $doc) {
            $list[] = [
                'store_id' => $doc->store_id,
                'order_id' => $doc->order_id,
                'user_id'  => $doc->user_id,
            ];
        }
        $data['list'] = $list;

        return ModRes::retSucceed($data);
    }

    /**
     * 更新或增加索引
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function replace(Request $request)
    {
        $this->validate($request, [
            'order_id'      => 'required|integer|min:1',
            'store_id'      => 'required|integer|min:1',
            'user_id'       => 'required|integer|min:1',
            'consignee'     => 'required',
            'mobile'        => 'integer|digits:11',
            'goods_list'                => 'required|array',
            'goods_list.*.goods_id'     => 'required|integer|min:1',
            'goods_list.*.goods_name'   => 'required|min:1',
        ]);
        $goodsList  = $request->input('goods_list');

        $goodsName = [];
        foreach ($goodsList as $goodsInfo) {
            $goodsName[] = $goodsInfo['goods_name'];
        }
        $goodsName = implode(' ', $goodsName);

        $data = $request->only(['order_id', 'store_id', 'user_id', 'consignee', 'mobile']);
        $data['goods_name'] = $goodsName;
        $this->search->replaceIndex($data);
        return ModRes::retSucceed();
    }

    /**
     * 根据订单ID删除索引
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'order_ids'   => 'required|array|min:1',
            'order_ids.*'   => 'required|integer|min:1',
        ]);
        $userIds = $request->input('order_ids');
        $this->search->delIndex($userIds);
        return ModRes::retSucceed();
    }
}