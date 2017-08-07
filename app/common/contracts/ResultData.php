<?php
/**
 * Created by PhpStorm.
 * User: helei
 * Date: 2017/8/7
 * Time: 下午3:36
 */

namespace XApi\Contracts;

use XApi\Utils\ArrayUtil;

/**
 * 定义返回的数据结构
 * Class ResultData
 * @package XApi\Contracts
 */
class ResultData
{
    /**
     *  返回的消息信息
     * @var string $msg
     */
    private $msg;

    /**
     * 返回的业务码
     * @var number $code
     */
    private $code;

    /**
     * 返回的业务数据
     * @var array|object $data
     */
    private $data;

    /**
     * ResultData constructor.
     * @param int $code
     * @param string $msg
     * @param null $data
     */
    public function __construct(int $code, string $msg, $data = null)
    {
        $this->code = strval($code);
        $this->msg = strval(trim($msg));
        $this->data = $data;
    }

    /**
     * 通过该方法，完成了数据协议的封装
     * @return array
     */
    public function getResult()
    {
        // 这里主要是 phalcon 有时返回的是个对象，并且提供了 toarray 方法
        if (! is_array($this->data) && is_object($this->data) && method_exists($this->data, 'toArray')) {
            $this->data = $this->data->toArray();
        }

        $result = [
            'code' => $this->code,
            'msg' => $this->msg,
            'data' => $this->data ? ArrayUtil::valueToString($this->data) : []
        ];

        return $result;
    }

    /**
     * 得到处理后的json数据
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->getResult(), JSON_UNESCAPED_UNICODE);
    }
}