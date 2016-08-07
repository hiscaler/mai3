<?php

namespace app\models;

use Yii;
use yii\helpers\Inflector;

/**
 * This is the model class for table "{{%brand}}".
 *
 * @property integer $id
 * @property string $alias
 * @property string $name
 * @property string $icon_path
 * @property string $description
 * @property integer $ordering
 * @property integer $tenant_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Brand extends BaseActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%brand}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['ordering', 'tenant_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['ordering'], 'default', 'value' => 1],
            [['alias', 'name'], 'string', 'max' => 20],
            [['icon_path'], 'string', 'max' => 100],
            [['alias'], 'unique'],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'alias' => Yii::t('app', 'Alias'),
            'name' => Yii::t('app', '名称'),
            'icon_path' => Yii::t('app', '品牌标志'),
            'description' => Yii::t('app', '描述'),
            'ordering' => Yii::t('app', '排序'),
            'tenant_id' => Yii::t('app', 'Tenant ID'),
            'status' => Yii::t('app', '状态'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * 获取品牌列表数据
     * @param boolean $all
     * @return array
     */
    public static function getList($all = false)
    {
        $list = [];
        $sql = 'SELECT [[id]], [[name]] FROM {{%brand}}';
        $bindValues = [];
        if (!$all) {
            $sql .= ' WHERE [[status]] = :status';
            $bindValues = [':status' => Constant::BOOLEAN_TRUE];
        }
        $sql .= ' ORDER BY [[alias]] DESC';

        $rawData = Yii::$app->getDb()->createCommand($sql)->bindValues($bindValues)->queryAll();
        foreach ($rawData as $data) {
            $list[$data['id']] = $data['name'];
        }

        return $list;
    }

    // Events
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->alias) && !empty($this->name)) {
                $this->alias = Inflector::slug($this->name);
            }

            return true;
        } else {
            return false;
        }
    }

}
