<?php

namespace app\modules\admin\controllers;

use app\models\Specification;
use app\models\TypeProperty;
use app\models\Yad;
use Yii;
use yii\db\Query;
use yii\web\Response;

/**
 * 接口
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class ApiController extends \yii\rest\Controller
{

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $formatter = Yii::$app->getFormatter();
            $language = Yad::getLanguage();
            if ($language) {
                Yii::$app->language = $language;
            }
            $timezone = Yad::getTimezone();
            if ($timezone) {
                Yii::$app->timeZone = $timezone;
            }

            $formatter->defaultTimeZone = Yii::$app->timeZone;
            $dateFormat = Yad::getTenantValue('dateFormat', 'php:Y-m-d');
            if ($dateFormat) {
                $formatter->dateFormat = $dateFormat;
            }
            $timeFormat = Yad::getTenantValue('timeFormat', 'php:H:i:s');
            if ($timeFormat) {
                $formatter->timeFormat = $timeFormat;
            }
            $datetimeFormat = Yad::getTenantValue('datetimeFormat', 'php:Y-m-d H:i:s');
            if ($datetimeFormat) {
                $formatter->datetimeFormat = $datetimeFormat;
            }

            Yii::$app->getResponse()->format = 'json';

            return true;
        }

        return false;
    }

    /**
     * 商品接口
     * @param integer $id
     * @return Response
     */
    public function actionProduct($id)
    {
        $db = Yii::$app->getDb();
        $res = $db->createCommand('SELECT [[id]], [[name]] FROM {{%product}} WHERE id = :id', [':id' => (int) $id])->queryOne();
        if ($res) {
            $items = $db->createCommand('SELECT [[id]], [[sku_sn]], [[name]], [[market_price]], [[shop_price]], [[member_price]], [[picture_path]], [[clicks_count]], [[favorites_count]], [[sales_count]], [[stocks_count]], [[default]] FROM {{%item}} WHERE [[product_id]] = :productId', [':productId' => $res['id']])->queryAll();
            $res['items'] = $items;
        }

        return new Response([
            'format' => Response::FORMAT_JSON,
            'data' => $res,
        ]);
    }

    /**
     * 类型接口
     * @param integer $id 商品类型id
     * @param integer $productId 商品id
     * @return Response
     */
    public function actionType($id, $productId = null)
    {
        $db = Yii::$app->getDb();
        $res = $db->createCommand('SELECT [[id]], [[name]] FROM {{%type}} WHERE id = :id', [':id' => (int) $id])->queryOne();
        if ($res) {
            // 品牌
            $brands = $db->createCommand('SELECT [[id]], [[alias]], [[name]], [[icon_path]], [[description]] FROM {{%brand}} WHERE id IN (SELECT [[brand_id]] FROM {{%type_brand}} WHERE [[type_id]] = :typeId)', [':typeId' => $res['id']])->queryAll();
            $res['brands'] = $brands;

            // 规格
            $productId = (int) $productId;
            $checkedSpecificationValues = [];
            if ($productId) {
                $itemSpecificationValues = $db->createCommand('SELECT [[specification_value_id]] FROM {{%item_specification_value}} WHERE [[item_id]] IN (SELECT [[id]] FROM {{%item}} WHERE [[product_id]] = :productId)', [':productId' => $productId])->queryColumn();
            } else {
                $itemSpecificationValues = [];
            }
            $specifications = [];
            $specificationsRawData = $db->createCommand('SELECT [[t.id]], [[t.name]], [[t.type]], [[t.ordering]], [[sv.id]] AS [[value_id]], [[sv.text]], [[sv.icon_path]] FROM {{%specification}} t LEFT JOIN {{%specification_value}} sv ON [[t.id]] = [[sv.specification_id]] WHERE [[t.id]] IN (SELECT [[specification_id]] FROM {{%type_specification}} WHERE [[type_id]] = :typeId)', [':typeId' => $res['id']])->queryAll();
            foreach ($specificationsRawData as $data) {
                $checked = $itemSpecificationValues && in_array($data['value_id'], $itemSpecificationValues) ? true : false;
                if (!isset($specifications[$data['id']])) {
                    $specifications[$data['id']] = [
                        'id' => $data['id'],
                        'name' => $data['name'],
                        'type' => $data['type'] == Specification::TYPE_TEXT ? 'text' : 'icon',
                    ];
                }
                $specifications[$data['id']]['values'][] = [
                    'id' => $data['value_id'],
                    'value' => $data['type'] == Specification::TYPE_TEXT ? $data['text'] : $data['icon_path'],
                    'checked' => $checked,
                ];
                if ($checked) {
                    if (!isset($checkedSpecificationValues[$data['id']])) {
                        $checkedSpecificationValues[$data['id']] = [
                            'id' => $data['id'],
                            'values' => []
                        ];
                    }
                    $checkedSpecificationValues[$data['id']]['values'][] = [
                        'id' => $data['value_id'],
                        'name' => $data['type'] == Specification::TYPE_TEXT ? $data['text'] : $data['icon_path'],
                    ];
                }
            }
            $res['specifications'] = $specifications;
            $res['checkedSpecificationValues'] = $checkedSpecificationValues;

            if ($productId) {
                $items = (new Query())
                    ->select(['id', 'sn', 'name', 'market_price', 'shop_price', 'member_price', 'picture_path', 'default', 'enabled', 'online', 'status'])
                    ->from('{{%item}}')
                    ->where(['product_id' => $productId])
                    ->indexBy('id')
                    ->all();

                $itemSpecificationTexts = [];
                $itemSpecificationValues = $db->createCommand('SELECT [[t.item_id]], [[t.specification_value_id]], [[v.text]] FROM {{%item_specification_value}} t LEFT JOIN {{%specification_value}} v ON [[t.specification_value_id]] = [[v.id]] WHERE [[t.item_id]] IN (SELECT [[id]] FROM {{%item}} WHERE [[product_id]] = :productId)', [':productId' => $productId])->queryAll();
                foreach ($itemSpecificationValues as $v) {
                    if (!isset($items[$v['item_id']]['values'])) {
                        $items[$v['item_id']]['values'] = [];
                    }
                    $items[$v['item_id']]['values'][] = $v['specification_value_id'];
                    $itemSpecificationTexts[$v['item_id']][] = $v['text'];
                }

                // 处理格式
                foreach ($items as $key => $item) {
                    $items[$key]['_isNew'] = false;
                    $items[$key]['text'] = implode('、', $items[$key]['values']);
                    $items[$key]['specificationValueString'] = implode(',', $items[$key]['values']);
                    $items[$key]['price'] = [
                        'market' => $item['market_price'],
                        'shop' => $item['shop_price'],
                        'member' => $item['member_price'],
                    ];
                    $default = $item['default'] ? true : false;
                    $items[$key]['default'] = $default;
                    $items[$key]['_default_id'] = $default ? $key : null;
                    $items[$key]['enabled'] = $item['enabled'] ? true : false;
                    $items[$key]['online'] = $item['online'] ? true : false;
                    unset($items[$key]['market_price'], $items[$key]['member_price']);

                    if (!isset($items[$key]['text'])) {
                        $items[$key]['text'] = '';
                    } elseif (isset($itemSpecificationTexts[$key])) {
                        $items[$key]['text'] = implode('、', $itemSpecificationTexts[$key]);
                    }
                }
            } else {
                $items = [];
            }
            $res['items'] = $items;
        }

        return new Response([
            'format' => Response::FORMAT_JSON,
            'data' => $res,
        ]);
    }

    /**
     * 获取商品类型属性值
     * @param integer $typeId
     * @return Response
     */
    public function actionTypeProperties($typeId)
    {
        $data = Yii::$app->getDb()->createCommand('SELECT * FROM {{%type_property}} WHERE [[type_id]] = :typeId', [':typeId' => (int) $typeId])->queryAll();
        foreach ($data as $key => $item) {
            $item['value'] = null;
            if ($item['input_method'] == TypeProperty::INPUT_METHOD_DROPDOWNLIST) {
                $inputValues = [];
                foreach (explode(PHP_EOL, $item['input_values']) as $row) {
                    $row = explode(':', $row);
                    if (count($row) == 2) {
                        $inputValues[$row[0]] = $row[1];
                    }
                }
                $item['input_values'] = $inputValues;
            }
            $data[$key] = $item;
        }

        return new Response([
            'format' => Response::FORMAT_JSON,
            'data' => $data,
        ]);
    }

    public function actionProductProperties($productId, $typeId)
    {
        $typeProperties = (new Query())->select('*')->from('{{%type_property}}')
            ->where(['type_id' => (int) $typeId])
            ->indexBy('id')
            ->all();
        $productPropertyValues = (new Query())->select('value')->from('{{%product_property}}')
            ->where(['product_id' => (int) $productId])
            ->indexBy('property_id')
            ->column();
        foreach ($typeProperties as $key => $item) {
            $item['value'] = isset($productPropertyValues[$key]) ? $productPropertyValues[$key] : null;
            if ($item['input_method'] == TypeProperty::INPUT_METHOD_DROPDOWNLIST) {
                $inputValues = [];
                foreach (explode(PHP_EOL, $item['input_values']) as $row) {
                    $row = explode(':', $row);
                    if (count($row) == 2 && !empty($row[0]) && !empty($row[1])) {
                        $inputValues[$row[0]] = $row[1];
                    }
                }
                $item['input_values'] = $inputValues;
            }
            $typeProperties[$key] = $item;
        }

        return new Response([
            'format' => Response::FORMAT_JSON,
            'data' => $typeProperties,
        ]);
    }

    /**
     * 数据验证规则
     * @return Response
     */
    public function actionValidators()
    {
        $validators = [
            'required' => [
                'class' => '\yii\validators\RequiredValidator',
                'label' => Yii::t('meta', 'Required Validator'),
            ],
            'integer' => [
                'class' => '\yii\validators\IntegerValidator',
                'label' => Yii::t('meta', 'Integer Validator'),
                'options' => [
                    'min' => null,
                    'max' => null,
                    'message' => null,
                ]
            ],
            'string' => [
                'class' => '\yii\validators\StringValidator',
                'label' => Yii::t('meta', 'String Validator'),
                'options' => [
                    'length' => null,
                    'min' => null,
                    'max' => null,
                    'message' => null,
                    'encoding' => Yii::$app->charset
                ]
            ],
            'email' => [
                'class' => '\yii\validators\EmailValidator',
                'label' => Yii::t('meta', 'Email Validator'),
            ],
            'url' => [
                'class' => '\yii\validators\UrlValidator',
                'label' => Yii::t('meta', 'Url Validator'),
            ],
            'date' => [
                'class' => '\yii\validators\DateValidator',
                'label' => Yii::t('meta', 'Date Validator'),
                'options' => [
                    'format' => null,
                    'timeZone' => Yii::$app->getTimeZone(),
                ]
            ],
        ];

        foreach ($validators as $name => $config) {
            if (!isset($config['options']) || empty($config['options'])) {
                $config['messages'] = $config['options'] = new \stdClass();
            } else {
                $messages = [];
                foreach ($config['options'] as $opt => $value) {
                    $messages[$opt] = Yii::t('meta', ucwords($name) . ' ' . ucwords($opt));
                }
                $config['messages'] = $messages;
            }
            $validators[$name] = $config;
        }

        return $validators;
    }

    /**
     * 指定数据的验证规则
     * @param integer $metaId
     * @return yii\web\Response
     */
    public function actionMetaValidators($metaId)
    {
        $metaValidators = Yii::$app->getDb()->createCommand('SELECT [[name]], [[options]] FROM {{%meta_validator}} WHERE [[meta_id]] = :metaId', [':metaId' => (int) $metaId])->queryAll();
        foreach ($metaValidators as $key => $item) {
            $options = unserialize($item['options']);
            if (!$options) {
                $options = new \stdClass();
            }
            $metaValidators[$key]['options'] = $options;
        }

        return $metaValidators;
    }

}
