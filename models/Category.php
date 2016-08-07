<?php

namespace app\models;

use yadjet\helpers\TreeFormatHelper;
use Yii;
use yii\helpers\Inflector;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string $alias
 * @property string $name
 * @property integer $parent_id
 * @property integer $level
 * @property string $parent_ids
 * @property string $parent_names
 * @property string $icon_path
 * @property string $description
 * @property integer $status
 * @property integer $tenant_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Category extends BaseActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['type', 'parent_id', 'level', 'status', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['description'], 'string'],
            [['alias'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 30],
            [['parent_ids', 'icon_path'], 'string', 'max' => 100],
            [['parent_names'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'type' => Yii::t('category', 'Type'),
            'alias' => Yii::t('category', 'Alias'),
            'name' => Yii::t('category', 'Name'),
            'parent_id' => Yii::t('category', 'Parent ID'),
            'level' => Yii::t('category', 'Level'),
            'parent_ids' => Yii::t('category', 'Parent Ids'),
            'parent_names' => Yii::t('category', 'Parent Names'),
            'icon_path' => Yii::t('category', 'Icon Path'),
            'description' => Yii::t('category', 'Description'),
        ]);
    }

    /**
     * 获取分类项目
     * @return array
     */
    public static function getTree($top = null, $all = false)
    {
        $items = [];
        if ($top) {
            $items[] = $top;
        }
        $bindValues = [':tenantId' => 1];
        $sql = 'SELECT [[id]], [[name]], [[parent_id]] FROM {{%category}} WHERE [[tenant_id]] = :tenantId';
        if (!$all) {
            $sql .= ' AND status = :status';
            $bindValues[':status'] = Constant::BOOLEAN_TRUE;
        }
        $rawData = Yii::$app->getDb()->createCommand($sql)->bindValues($bindValues)->queryAll();
        if ($rawData) {
            $data = TreeFormatHelper::dumpArrayTree(\yadjet\helpers\ArrayHelper::toTree($rawData, 'id', 'parent_id'));
            foreach ($data as $value) {
                $items[$value['id']] = $value['levelstr'] . $value['name'];
            }
        }

        return $items;
    }

    // 事件
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
