<?php
/**
 * Created by PhpStorm.
 * User: helei
 * Date: 2017/8/7
 * Time: 下午3:57
 */

namespace XApi\Base;


use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use XApi\Contracts\ApiCode;
use XApi\Contracts\HttpCode;
use XApi\Contracts\ResultData;

/**
 * 所有控制器的基类
 * Class BaseController
 * @package XApi\Base
 */
class BaseController extends Controller
{
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        // 解决跨域时，预检问题
        if ($this->request->isOptions()) {
            $this->response->setStatusCode(HttpCode::OK);
            $this->response->setHeader('Access-Control-Max-Age', 1728000);
        }
    }

    /**
     * 请求完成后 将要返回客户端时，对数据处理的位置
     * @param Dispatcher $dispatcher
     */
    public function afterExecuteRoute(Dispatcher $dispatcher)
    {
        // 首先设置跨域问题
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Headers', 'X-Requested-With,D-Request-From,Content-Type,Authorization');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET,POST,OPTIONS,PUT,DELETE');


        // 返回数据
        /* @var ResultData $retData */
        $result = $dispatcher->getReturnedValue();
        if (! $result instanceof ResultData) {
            $result = new ResultData(ApiCode::SYS_ERR, '系统返回不可识别数据');
        }

        // 返回响应的数据
        $this->response->setJsonContent($result->getResult());
        $this->response->send();
    }
}