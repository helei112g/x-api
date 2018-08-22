<?php
/**
 * @package XApi\Utils
 * @author  : helei
 * @date    : 2018/4/21 下午2:25
 * @version : 1.0.0
 * @desc    :
 **/

namespace XApi\Utils;

use Phalcon\Di;
use Predis\Client;
use XApi\Contracts\ApiCode;

class XRedis
{
    /**
     * @var Client $instance
     */
    private static $instance;

    /**
     * @param array $config
     * @return Client
     * @throws \ErrorException
     */
    public static function getInstance(array $config)
    {
        if (!(self::$instance instanceof self)) {
            try {
                new self($config);
            } catch (\ErrorException $e) {
                throw $e;
            }
        }

        return self::$instance;
    }

    /**
     * 获取redis的链接
     * WRedis constructor.
     * @param array $config
     * @throws \ErrorException
     */
    private function __construct(array $config)
    {
        if (empty($config)) {
            throw new \ErrorException("请提供正确的redis配置", ApiCode::CONFIG_ERR);
        }

        self::$instance = new Client($config);
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
}
