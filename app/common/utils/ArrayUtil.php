<?php
/**
 * Created by PhpStorm.
 * User: helei
 * Date: 2017/8/7
 * Time: 下午3:40
 */

namespace XApi\Utils;

/**
 * 处理数组的工具类，静态方法
 * Class ArrayUtil
 * @package XApi\Utils
 */
class ArrayUtil
{
    /**
     * 将传入的数组的值，全部转化为字符串类型，注意检查多维数组的情况
     * @param array $data
     * @return array
     * @author helei
     */
    public static function valueToString(array $data)
    {
        if (empty($data) || ! is_array($data)) {
            return $data;
        }

        foreach ($data as $key => $value) {
            // 如果是数组，则进行递归调用。
            if (is_array($value)) {
                $data[$key] = self::valueToString($value);
                continue;
            }

            // 如果是bool值，则转化为对应字符串
            if (is_bool($value)) {
                $data[$key] = $value ? 'true' : 'false';
                continue;
            }

            // 如果是null则返回 空字符串
            if (is_null($value)) {
                $data[$key] = '';
                continue;
            }

            // 如果是对象，则不处理
            if (is_object($value)) {
                continue;
            }

            $data[$key] = (string) $value;
        }

        return $data;
    }
}