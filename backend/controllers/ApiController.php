<?php

namespace backend\controllers;

use common\models\Specification;
use Yii;
use yii\web\Response;

class ApiController extends \yii\rest\Controller
{

    public function actionType($id)
    {
        $db = Yii::$app->getDb();
        $res = $db->createCommand('SELECT [[id]], [[name]] FROM {{%type}} WHERE id = :id', [':id' => (int) $id])->queryOne();
        if ($res) {
            // 品牌
            $brands = $db->createCommand('SELECT [[id]], [[alias]], [[name]], [[icon_path]], [[description]] FROM {{%brand}} WHERE id IN (SELECT [[brand_id]] FROM {{%type_brand}} WHERE [[type_id]] = :typeId)', [':typeId' => $res['id']])->queryAll();
            $res['brands'] = $brands;

            // 规格
            $specifications = [];
            $specificationsRawData = $db->createCommand('SELECT [[t.id]], [[t.name]], [[t.type]], [[t.ordering]], [[sv.id]] AS [[value_id]], [[sv.text]], [[sv.icon_path]] FROM {{%specification}} t LEFT JOIN {{%specification_value}} sv ON [[t.id]] = [[sv.specification_id]] WHERE [[t.id]] IN (SELECT [[specification_id]] FROM {{%type_specification}} WHERE [[type_id]] = :typeId)', [':typeId' => $res['id']])->queryAll();
            foreach ($specificationsRawData as $data) {
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
                ];
            }
//            $specificationValues = $db->createCommand('SELECT [[t.id]], [[t.specification_id]], [[t.text]], [[t.icon_path]] FROM {{%specification_value}} t LEFT JION {{%specification}} s ON [[t.specification_id]] = [[s.id]] WHERE [[t.specification_id]] = :')->queryAll();
            $res['specifications'] = $specifications;                ;
        }

        return new Response([
            'format' => Response::FORMAT_JSON,
            'data' => $res,
        ]);
    }

}
