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

class GoodsController extends BaseController
{
    protected $projectName = 'goods';

    /**
     * 按名称和店铺ID搜索
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function search(Request $request)
    {
        $this->validate($request, [
            'goods_name'  => 'required|between:1,100',
            'store_id'    => 'required|integer|min:1',
            'page'        => 'integer|min:1',
            'per_page'    => 'integer|min:10',
        ]);
        $storeId    = $request->input('store_id');
        $goodsName  = $request->input('goods_name');
        $page       = $request->input('page', 1);
        $perPage    = $request->input('per_page', 20);

        $keyword = $this->search->genKeyword(['store_id' => $storeId, 'goods_name' => $goodsName]);
        $data  = $this->search->searchAll($keyword, [], $page, $perPage);

        $list  = [];
        foreach ($data['list'] as $doc) {
            $list[] = [
                'goods_id' => $doc->goods_id,
                'store_id' => $doc->store_id
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
            'goods_id'   => 'required|integer|min:1',
            'goods_name' => 'required|between:1,100',
            'store_id'   => 'required|integer|min:1',
        ]);
        $data = $request->only(['goods_id', 'goods_name', 'store_id']);
        $this->search->replaceIndex($data);
        return ModRes::retSucceed();
    }

    /**
     * 根据商品ID删除索引
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'goods_ids'   => 'required|array|min:1',
            'goods_ids.*' => 'required|integer|min:1',
        ]);
        $goodsIds = $request->input('goods_ids');
        $this->search->delIndex($goodsIds);
        return ModRes::retSucceed();
    }
}