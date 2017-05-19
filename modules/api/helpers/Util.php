<?php

namespace app\modules\api\helpers;

use yii\helpers\Inflector;

/**
 * 共用处理函数
 *
 * @author hiscaler <hiscale@gmail.com>
 */
class Util
{

    /**
     * 只保留 $fields 提供的字段内容
     *
     * @param array $selectColumns // 所有查询的字段名称
     * @param array $fields // 请求查询的字段名称
     * @param array $relatedFields // 请求查询的字段需要的附加字段（比如查询 shortTitle 需要 title 属性，根据 model 的 fields 设定）
     * @return array
     */
    public static function filterQuerySelectColumns($selectColumns, $fields, $relatedFields = [])
    {
        if (!empty($fields)) {
            $fields = explode(',', Inflector::camel2id($fields, '_'));
            foreach ($selectColumns as $key => $columnName) {
                $columnName = trim($columnName);
                if (in_array($columnName, $fields)) {
                    continue;
                }

                if ($relatedFields && in_array($columnName, $relatedFields)) {
                    foreach ($fields as $field) {
                        if (isset($relatedFields[$field])) {
                            break 2;
                        }
                    }
                }

                foreach ([' ', '.'] as $value) {
                    if (($pos = strrpos($columnName, $value)) !== false) {
                        $columnName = substr($columnName, $pos + 1);
                    }
                }

                if (!in_array($columnName, $fields)) {
                    unset($selectColumns[$key]);
                }
            }
        }

        return $selectColumns;
    }

    /**
     * 调整数组的键名称（created_at => createdAt）
     *
     * @param array $rawData
     * @return array
     */
    public static function adjustFieldNames($rawData)
    {
        $items = [];
        if (is_array($rawData)) {
            foreach ($rawData as $row) {
                $data = [];
                foreach ($row as $key => $value) {
                    $data[lcfirst(Inflector::id2camel($key, '_'))] = $value;
                }
                $items[] = $data;
            }
        }

        return $items;
    }

    /**
     * 清理掉字符串中的无效字符，默认清理掉空格（全角和半角空格）
     *
     * @param string $string
     * @param boolean $toLower
     * @param array $replacePairs
     * @return string
     */
    public static function cleanString($string, $toLower = true, $replacePairs = [' ' => '', '　' => ''])
    {
        if ($replacePairs) {
            $string = strtr($string, $replacePairs);
        }

        return $toLower ? strtolower($string) : $string;
    }

    /**
     * 清理传入的整型值（非数字和零将全部清理掉）
     *
     * 0,1,2,3,3,abc 返回 1,2,3
     * @param string $string
     * @return array
     */
    public static function cleanIntegerNumbers($string)
    {
        return array_filter(array_unique(array_map('intval', explode(',', $string))));
    }

    /**
     * 格式化日期，获得日期的开始和结束时间戳
     *
     * @param string $date
     * @return array|mixed
     */
    public static function parseDate($date)
    {
        switch (strlen($date)) {
            case 4: // year
                $result = [
                    mktime(0, 0, 0, 1, 1, $date),
                    mktime(0, 0, 0, 12, 31, $date)
                ];
                break;

            case 6: // year + month
                $t = str_split($date, 4);
                $beginTime = mktime(0, 0, 0, $t[1], 1, $t[0]);
                $result = [$beginTime, mktime(23, 59, 59, $t[1], date("t", $beginTime), $t[0])];
                break;

            case 8: // year + month + day
                $year = substr($date, 0, 4);
                $month = substr($date, 4, 2);
                $day = substr($date, 6, 2);
                $result = [
                    mktime(0, 0, 0, $month, $day, $year),
                    mktime(23, 59, 59, $month, $day, $year)
                ];
                break;

            default:
                $result = null;
        }

        return $result;
    }

}
