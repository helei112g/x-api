<?php
/**
 * @package Lottery\Contracts
 * @author  : helei
 * @date    : 2018/8/22 下午9:08
 * @version : 1.0.0
 * @desc    : 错误码对应的错误信息
 **/

namespace XApi\Contracts;


class ApiMsg
{
    private static $codeMapLang = [
        ApiCode::SUC => 'OK',
        ApiCode::SYS_ERR => 'System is busy',
        ApiCode::CONFIG_ERR => 'Please check config'
    ];

    /**
     * 返回码对应的错误信息
     * @param int $code
     * @return mixed
     */
    public static function getMsg(int $code)
    {
        return self::$codeMapLang[$code] ?? $code;
    }
}
