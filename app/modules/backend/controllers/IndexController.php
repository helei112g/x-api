<?php
/**
 * Created by PhpStorm.
 * User: helei
 * Date: 2017/8/7
 * Time: 下午4:27
 */

namespace XApi\Modules\Backend\Controllers;


use XApi\Base\BaseController;
use XApi\Contracts\ApiCode;
use XApi\Contracts\ResultData;

/**
 * 后端api
 * Class IndexController
 * @package XApi\Modules\Backend\Controllers
 */
class IndexController extends BaseController
{
    public function indexAction(): ResultData
    {
        return new ResultData(ApiCode::SUC, 'ok', []);
    }
}