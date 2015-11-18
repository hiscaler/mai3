<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%specification}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property integer $ordering
 * @property integer $status
 * @property integer $tenant_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Specification extends BaseActiveRecord
{

    /**
     * 规格类型
     */
    const TYPE_TEXT = 0;
    const TYPE_ICON = 1;

    public $valuesData;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%specification}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['type', 'ordering', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['status'], 'boolean'],
            [['name'], 'string', 'max' => 20],
            [['valuesData'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('specification', 'Name'),
            'type' => Yii::t('specification', 'Type'),
        ];
    }

    public static function typeOptions()
    {
        return [
            static::TYPE_TEXT => '文字',
            static::TYPE_ICON => '图标',
        ];
    }

    public function getValues()
    {
        return $this->hasMany(SpecificationValue::className(), ['specification_id' => 'id'])->orderBy(['ordering' => SORT_ASC]);
    }

    /**
     * 获取商品规格列表数据
     * @param boolean $all
     * @return array
     */
    public static function getMap($all = false)
    {
        $list = [];
        $sql = 'SELECT [[id]], [[name]] FROM {{%specification}}';
        $bindValues = [];
        if (!$all) {
            $sql .= ' WHERE [[status]] = :status';
            $bindValues = [':status' => Constant::BOOLEAN_TRUE];
        }
        $sql .= ' ORDER BY [[ordering]] ASC';

        $rawData = Yii::$app->getDb()->createCommand($sql)->bindValues($bindValues)->queryAll();
        foreach ($rawData as $data) {
            $list[$data['id']] = "{$data['name']}";
        }

        return $list;
    }

    // 事件
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $db = Yii::$app->getDb();
        $values = $this->valuesData;
        $tenantId = Yad::getTenantId();
        $now = time();
        $userId = Yii::$app->getUser()->getId();
        $insertValues = [];
        if ($insert) {
            foreach ($values as $value) {
                $insertValues[] = array_merge($value, ['tenant_id' => $tenantId, 'created_at' => $now, 'created_by' => $userId, 'updated_at' => $now, 'updated_by' => $userId]);
            }
        } else {
            foreach ($values as $value) {
                $valueId = isset($value['id']) && $value['id'] ? $value['id'] : null;
                if ($valueId) {
                    // Update
                    $specificationValue = $db->createCommand('SELECT [[text]], [[icon_path]], [[ordering]], [[status]] FROM {{%specification_value}} WHERE [[id]] = :id AND [[tenant_id]] = :tenantId AND [[specification_id]] = :specificationId')->bindValues([':id' => $valueId, ':tenantId' => $tenantId, ':specificationId' => $this->id])->queryOne();
                    if ($specificationValue) {
                        if ($value['text'] != $specificationValue['text'] || $value['icon_path'] != $specificationValue['icon_path'] || $value['ordering'] != $specificationValue['ordering'] || $value['status'] != $specificationValue['status']) {
                            $updateColumns = [
                                'updated_at' => $now,
                                'updated_by' => $userId,
                            ];
                            if ($value['text'] != $specificationValue['text']) {
                                $updateColumns['text'] = $value['text'];
                            }
                            if ($value['icon_path'] != $specificationValue['icon_path']) {
                                $updateColumns['icon_path'] = $value['icon_path'];
                            }
                            if ($value['ordering'] != $specificationValue['ordering']) {
                                $updateColumns['ordering'] = $value['ordering'];
                            }
                            if ($value['status'] != $specificationValue['status']) {
                                $updateColumns['status'] = $value['status'];
                            }
                            $db->createCommand()->update('{{%specification_value}}', $updateColumns, ['id' => $valueId, 'tenant_id' => $tenantId, 'specification_id' => $this->id])->execute();
                        }
                    }
                } else {
                    // Insert
                    $insertColumns = array_merge($value, ['specification_id' => $this->id, 'tenant_id' => $tenantId, 'created_at' => $now, 'created_by' => $userId, 'updated_at' => $now, 'updated_by' => $userId]);
                    $insertValues[] = array_values($insertColumns);
                }
            }
        }
        if ($insertValues) {
            $db->createCommand()->batchInsert('{{%specification_value}}', array_keys($insertColumns), $insertValues)->execute();
        }
    }

}
