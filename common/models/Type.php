<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%type}}".
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
class Type extends BaseActiveRecord
{

    /**
     * 关联品牌
     * @var array
     */
    public $brandIdList;

    /**
     * 关联规格
     * @var array
     */
    public $specificationIdList;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%type}}';
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
            [['brandIdList', 'specificationIdList'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('Type', 'Name'),
        ];
    }

    public function getBrands()
    {
        return $this->hasMany(TypeBrand::className(), ['type_id' => 'id']);
    }

    public function getSpecifications()
    {
        return $this->hasMany(TypeSpecification::className(), ['type_id' => 'id']);
    }

    public static function getList()
    {
        return (new Query())->select('name')->from(static::tableName())->indexBy('id')->column();
    }

    // 事件
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $db = Yii::$app->getDb();
        $transaction = $db->beginTransaction();
        try {
            // 关联品牌处理
            $brandIdList = $this->brandIdList;
            $insertBrands = $deleteBrands = [];
            if ($insert) {
                $insertBrands = $brandIdList;
            } else {
                $_brandIdList = $db->createCommand('SELECT [[brand_id]] FROM {{%type_brand}} WHERE [[type_id]] = :typeId')->bindValue(':typeId', $this->id, \PDO::PARAM_INT)->queryColumn();
                $insertBrands = array_diff($brandIdList, $_brandIdList);
                $deleteBrands = array_diff($_brandIdList, $brandIdList);
            }

            if ($insertBrands || $deleteBrands) {
                if ($insertBrands) {
                    $batchRows = [];
                    foreach ($insertBrands as $brandId) {
                        $batchRows[] = [$this->id, $brandId];
                    }
                    $db->createCommand()->batchInsert('{{%type_brand}}', ['type_id', 'brand_id'], $batchRows)->execute();
                }
                if ($deleteBrands) {
                    $db->createCommand()->delete('{{%type_brand}}', ['brand_id' => $deleteBrands])->execute();
                }
            }

            // 关联规格处理
            $specificationIdList = $this->specificationIdList;
            $insertSpecifications = $deleteSpecifications = [];
            if ($insert) {
                $insertSpecifications = $specificationIdList;
            } else {
                $_spcificationIdList = $db->createCommand('SELECT [[specification_id]] FROM {{%type_specification}} WHERE [[type_id]] = :typeId')->bindValue(':typeId', $this->id, \PDO::PARAM_INT)->queryColumn();
                $insertSpecifications = array_diff($specificationIdList, $_spcificationIdList);
                $deleteSpecifications = array_diff($_spcificationIdList, $specificationIdList);
            }

            if ($insertSpecifications || $deleteSpecifications) {
                if ($insertSpecifications) {
                    $batchRows = [];
                    foreach ($insertSpecifications as $specificationId) {
                        $batchRows[] = [$this->id, $specificationId];
                    }
                    $db->createCommand()->batchInsert('{{%type_specification}}', ['type_id', 'specification_id'], $batchRows)->execute();
                }
                if ($deleteSpecifications) {
                    $db->createCommand()->delete('{{%type_specification}}', ['specification_id' => $deleteSpecifications])->execute();
                }
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

}
