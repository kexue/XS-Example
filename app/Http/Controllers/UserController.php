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

class UserController extends BaseController
{
    protected $projectName = 'users';

    /**
     * 按昵称和店铺ID搜索
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function search(Request $request)
    {
        $this->validate($request, [
            'store_id'  => 'required|integer|min:1',
            'nickname'  => 'between:1,100',
            'mobile'    => 'integer|digits:11',
            'page'      => 'integer|min:1',
            'per_page'  => 'integer|min:10',
        ]);
        $storeId    = $request->input('store_id');
        $nickname   = $request->input('nickname');
        $mobile     = $request->input('mobile');
        $page       = $request->input('page', 1);
        $perPage    = $request->input('per_page', 20);
        if (!$nickname && !$mobile) {
            return ModRes::retFailed(ModRes::CODE_INVALID_PARAMS);
        }

        $keyword = $this->search->genKeyword([
            'store_id'  => $storeId,
            'nickname'  => $nickname,
            'mobile'    => $mobile
        ]);
        $sorts = ['user_id' => false];
        $data  = $this->search->searchAll($keyword, $sorts, $page, $perPage);

        $list  = [];
        foreach ($data['list'] as $doc) {
            $list[] = [
                'user_id'  => $doc->user_id,
                'store_id' => $doc->store_id
            ];
        }
        $data['list'] = $list;
        return ModRes::retSucceed($data);
    }

    /**
     * 更新或增加用户昵称索引
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function replace(Request $request)
    {
        $this->validate($request, [
            'user_id'   => 'required|integer|min:1',
            'nickname'  => 'required|between:1,100',
            'store_id'  => 'required|integer|min:1',
            'mobile'    => 'integer|digits:11',
        ]);
        $data = $request->only(['user_id', 'nickname', 'store_id', 'mobile']);
        $this->search->replaceIndex($data);
        return ModRes::retSucceed();
    }

    /**
     * 根据用户ID删除索引
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'user_ids'   => 'required|array|min:1',
            'user_ids.*' => 'required|integer|min:1',
        ]);
        $userIds = $request->input('user_ids');
        $this->search->delIndex($userIds);
        return ModRes::retSucceed();
    }
}