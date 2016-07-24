<?php

namespace backend\controllers;

use common\models\Specification;
use Yii;
use yii\web\Response;

/**
 * 接口
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class ApiController extends \yii\rest\Controller
{

    /**
     * 商品接口
     * @param integer $id
     * @return Response
     */
    public function actionItem($id)
    {
        $db = Yii::$app->getDb();
        $res = $db->createCommand('SELECT [[id]], [[name]] FROM {{%item}} WHERE id = :id', [':id' => (int) $id])->queryOne();
        if ($res) {
            $skuItems = $db->createCommand('SELECT [[id]], [[sku_sn]], [[name]], [[market_price]], [[member_price]], [[picture_path]], [[clicks_count]], [[favorites_count]], [[sales_count]], [[stocks_count]], [[default]] FROM {{%item_sku}} WHERE [[item_id]] = :itemId', [':itemId' => $res['id']])->queryAll();
            $res['sku'] = $skuItems;
        }

        return new Response([
            'format' => Response::FORMAT_JSON,
            'data' => $res,
        ]);
    }

    /**
     * 类型接口
     * @param integer $id 商品类型id
     * @param integer $itemId 商品id
     * @return Response
     */
    public function actionType($id, $itemId = null)
    {
        $db = Yii::$app->getDb();
        $res = $db->createCommand('SELECT [[id]], [[name]] FROM {{%type}} WHERE id = :id', [':id' => (int) $id])->queryOne();
        if ($res) {
            // 品牌
            $brands = $db->createCommand('SELECT [[id]], [[alias]], [[name]], [[icon_path]], [[description]] FROM {{%brand}} WHERE id IN (SELECT [[brand_id]] FROM {{%type_brand}} WHERE [[type_id]] = :typeId)', [':typeId' => $res['id']])->queryAll();
            $res['brands'] = $brands;

            // 规格
            $itemId = (int) $itemId;
            $checkedSpecificationValues = [];
            if ($itemId) {
                $itemSpecificationValues = $db->createCommand('SELECT [[specification_value_id]] FROM {{%item_sku_specification_value}} WHERE [[sku_id]] IN (SELECT [[id]] FROM {{%item_sku}} WHERE [[item_id]] = :itemId)', [':itemId' => $itemId])->queryColumn();
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

            if ($itemId) {
                $skuList = (new \yii\db\Query())
                    ->select(['id', 'sku_sn AS sn', 'name', 'market_price', 'member_price', 'picture_path', 'enabled', 'status'])
                    ->from('{{%item_sku}}')
                    ->where(['item_id' => $itemId])
                    ->indexBy('id')
                    ->all();

                $itemSpecificationTexts = [];
                $itemSpecificationValues = $db->createCommand('SELECT [[t.sku_id]], [[t.specification_value_id]], [[v.text]] FROM {{%item_sku_specification_value}} t LEFT JOIN {{%specification_value}} v ON [[t.specification_value_id]] = [[v.id]] WHERE [[t.sku_id]] IN (SELECT [[id]] FROM {{%item_sku}} WHERE [[item_id]] = :itemId)', [':itemId' => $itemId])->queryAll();
                foreach ($itemSpecificationValues as $v) {
                    if (!isset($skuList[$v['sku_id']]['values'])) {
                        $skuList[$v['sku_id']]['values'] = [];
                    }
                    $skuList[$v['sku_id']]['values'][] = $v['specification_value_id'];
                    $itemSpecificationTexts[$v['sku_id']][] = $v['text'];
                }

                // 处理格式
                foreach ($skuList as $key => $item) {
                    $skuList[$key]['text'] = implode(',', $skuList[$key]['values']);
                    $skuList[$key]['specificationValueString'] = implode(',', $skuList[$key]['values']);
                    $skuList[$key]['price'] = [
                        'market' => $item['market_price'],
                        'member' => $item['member_price'],
                    ];
                    unset($skuList[$key]['market_price'], $skuList[$key]['member_price']);

                    if (!isset($skuList[$key]['text'])) {
                        $skuList[$key]['text'] = '';
                    } elseif (isset($itemSpecificationTexts[$key])) {
                        $skuList[$key]['text'] = implode(',', $itemSpecificationTexts[$key]);
                    }
                }
            } else {
                $skuList = [];
            }
            $res['sku'] = $skuList;
        }



        return new Response([
            'format' => Response::FORMAT_JSON,
            'data' => $res,
        ]);
    }

}
