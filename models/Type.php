<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%type}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $ordering
 * @property integer $enabled
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
     * 自定义属性
     * @var array
     */
    public $propertiesList;

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
            [['name'], 'trim'],
            [['enabled'], 'boolean'],
            [['enabled'], 'default', 'value' => 0],
            [['ordering', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['brandIdList', 'specificationIdList', 'propertiesList'], 'safe'],
            ['name', 'unique', 'targetAttribute' => ['name', 'tenant_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => Yii::t('Type', 'Name'),
        ]);
    }

    public function getBrands()
    {
        return $this->hasMany(TypeBrand::className(), ['type_id' => 'id']);
    }

    public function getSpecifications()
    {
        return $this->hasMany(TypeSpecification::className(), ['type_id' => 'id']);
    }

    public function getProperties()
    {
        return $this->hasMany(TypeProperty::className(), ['type_id' => 'id']);
    }

    public static function getList()
    {
        return (new Query())->select('name')->from(static::tableName())->where(['tenant_id' => Yad::getTenantId()])->indexBy('id')->column();
    }

    /**
     * 获取类型关联数据
     * @param string $id
     * @return array
     */
    public static function getRawData($id)
    {
        $tenantId = Yad::getTenantId();
        $db = Yii::$app->getDb();
        $brands = $db->createCommand('SELECT [[id]], [[name]] FROM {{%brand}} WHERE [[tenant_id]] = :tenantId AND [[id]] IN (SELECT [[brand_id]] FROM {{%type_brand}} WHERE [[type_id]] = :typeId)')->bindValues([':tenantId' => $tenantId, ':typeId' => $id])->queryAll();
        $specifications = $db->createCommand('SELECT [[id]], [[name]], [[type]] FROM {{%specification}} WHERE [[tenant_id]] = :tenantId AND [[id]] IN (SELECT [[specification_id]] FROM {{%type_specification}} WHERE [[type_id]] = :typeId)')->bindValues([':tenantId' => $tenantId, ':typeId' => $id])->queryAll();

        $rawSpecificationValues = (new Query())
            ->select(['t.id', 't.specification_id', 't.text', 't.icon_path AS icon'])
            ->from('{{%specification_value}} t')
            ->where(['t.tenant_id' => $tenantId])
            ->andWhere(['in', 't.specification_id', (new Query())->select('specification_id')->from('{{%type_specification}}')->where(['type_id' => $id])])
            ->all();
        $specificationValues = [];
        foreach ($rawSpecificationValues as $spec) {
            $t = $spec;
            unset($t['specification_id']);
            if (isset($specificationValues[$spec['specification_id']])) {
                $specificationValues[$spec['specification_id']][] = $t;
            } else {
                $specificationValues[$spec['specification_id']] = [$t];
            }
        }

        $rawData = [
            'brands' => $brands,
            'specifications' => $specifications,
        ];
        foreach ($rawData['specifications'] as $key => $spec) {
            if (isset($specificationValues[$spec['id']])) {
                $rawData['specifications'][$key]['values'] = $specificationValues[$spec['id']];
            } else {
                $rawData['specifications'][$key]['values'] = [];
            }
        }

        return $rawData;
    }

    // 事件
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $db = Yii::$app->getDb();
        $cmd = $db->createCommand();
        $transaction = $db->beginTransaction();
        try {
            // 关联品牌处理
            $brandIdList = $this->brandIdList;
            if (!is_array($brandIdList)) {
                $brandIdList = [];
            }
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
                    $cmd->batchInsert('{{%type_brand}}', ['type_id', 'brand_id'], $batchRows)->execute();
                }
                if ($deleteBrands) {
                    $cmd->delete('{{%type_brand}}', ['brand_id' => $deleteBrands])->execute();
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
                    $cmd->batchInsert('{{%type_specification}}', ['type_id', 'specification_id'], $batchRows)->execute();
                }
                if ($deleteSpecifications) {
                    $cmd->delete('{{%type_specification}}', ['specification_id' => $deleteSpecifications])->execute();
                }
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

}
