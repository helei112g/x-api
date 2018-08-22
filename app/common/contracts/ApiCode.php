<?php
/**
 * Created by PhpStorm.
 * User: helei
 * Date: 2017/8/7
 * Time: 下午4:06
 */

namespace XApi\Contracts;


class ApiCode
{
    const SUC = 0; // 请求成功
    const SYS_ERR = -1;// 系统错误

    # 重要错误：1000 ~ 3000
    const CONFIG_ERR = 1000;

    # 应用内部错误
}
