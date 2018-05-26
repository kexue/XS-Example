<?php
/**
 * Created by PhpStorm.
 * User: kexue
 * Date: 2018/5/10
 * Time: 下午6:55
 */
namespace App\Libs;

use Illuminate\Http\Request;

class ModRes
{
    const CODE_SUCCEED  = 0;
    const CODE_FAILED   = 1001;
    const CODE_SYSERR   = 1002;
    const CODE_INVALID_PARAMS = 1003;
    const CODE_NOTFOUND = 1004;
    const CODE_DBERR    = 1005;
    const CODE_CURLERR  = 1006;
    const CODE_SIGNERR  = 1007;
    const CODE_APIERR   = 1008;
    const CODE_NOTDATA  = 1010;
    const CODE_METHOT_NOTALLOWED = 1011;
    const CODE_XS_ERROR = 2001;

    public static $arrMsg = [
        self::CODE_SUCCEED  => '请求成功',
        self::CODE_FAILED   => '请求失败',
        self::CODE_SYSERR   => '系统繁忙',
        self::CODE_INVALID_PARAMS => '无效的参数',
        self::CODE_NOTDATA   => '没有数据',
        self::CODE_DBERR     => '系统繁忙',
        self::CODE_CURLERR   => '系统繁忙',
        self::CODE_SIGNERR   => '签名错误',
        self::CODE_APIERR    => '系统繁忙',
        self::CODE_NOTFOUND  => '无效的URI',
        self::CODE_METHOT_NOTALLOWED => '错误请求方式',
        self::CODE_XS_ERROR  => '参数错误',
    ];

    public static function retSucceed($data = [])
    {
        return [
            'status'    => true,
            'code'      => static::CODE_SUCCEED,
            'msg'       => static::$arrMsg[static::CODE_SUCCEED],
            'data'      => $data
        ];
    }

    public static function retFailed($code, $msg = '', $errors = [])
    {
        if (!$msg) {
            $msg = static::$arrMsg[$code];
        }
        $retData = [
            'status'    => false,
            'code'      => $code,
            'msg'       => $msg,
        ];
        $errors && $retData['errors'] = $errors;
        return $retData;
    }
}