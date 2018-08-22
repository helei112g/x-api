<?php
/**
 * Created by PhpStorm.
 * User: helei
 * Date: 2017/8/7
 * Time: 下午4:04
 */

namespace XApi\Modules\V1\Controllers;


use XApi\Base\BaseController;
use XApi\Contracts\ApiCode;
use XApi\Contracts\ResultData;

class IndexController extends BaseController
{
    /**
     * 首页数据
     * @return ResultData
     */
    public function indexAction(): ResultData
    {
        return new ResultData(ApiCode::SUC, 'ok', 'v1 controllers');
    }
}
