<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%item_type}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $ordering
 * @property integer $status
 * @property integer $tenant_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class ItemType extends BaseActiveRecord
{

    public $brandList;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%item_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status'], 'boolean'],
            [['status'], 'default', 'value' => 0],
            [['ordering', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['brandList'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('itemType', 'Name'),
        ];
    }

    // 事件
    private $_brandIds = [];

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $db = Yii::$app->getDb();
        $brands = $this->brandList;
        $insertBrands = $deleteBrands = [];
        if ($insert) {
            foreach ($brands as $brandId) {
                $insertBrands[] = ['item_type_id' => $this->id, 'brand_id' => $brandId];
            }
        } else {
            $this->_brandIds = $db->createCommand('SELECT [[brand_id]] FROM {{%item_type_brand}} WHERE [[item_type_id]] = :itemTypeId')->bindValue(':itemTypeId', $this->id, \PDO::PARAM_INT)->queryColumn();
            $insertBrands = array_diff($brands, $this->_brandIds);
            $deleteBrands = array_diff($this->_brandIds, $brands);
        }

        if ($insertBrands || $deleteBrands) {
            if ($insertBrands) {
                $batchRows = [];
                foreach ($insertBrands as $brandId) {
                    $batchRows[] = [$this->id, $brandId];
                }
                $db->createCommand()->batchInsert('{{%item_type_brand}}', ['item_type_id', 'brand_id'], $batchRows)->execute();
            }
            if ($deleteBrands) {
                $db->createCommand()->delete('{{%item_type_brand}}', ['item_type_id' => $deleteBrands])->execute();
            }
        }
    }

}
