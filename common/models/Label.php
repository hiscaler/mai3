<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\helpers\Inflector;

/**
 * This is the model class for table "{{%label}}".
 *
 * @property integer $id
 * @property string $alias
 * @property string $name
 * @property integer $frequency
 * @property integer $status
 * @property integer $ordering
 * @property integer $tenant_id
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at 
 */
class Label extends BaseActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%label}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'name', 'ordering'], 'required'],
            ['alias', 'match', 'pattern' => '/^[a-z]+[.]?[a-z-]+[a-z]$/'],
            ['alias', 'unique', 'targetAttribute' => ['alias', 'tenant_id']],
            [['status'], 'boolean'],
            [['frequency', 'ordering', 'tenant_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['alias', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => Yii::t('attribute', 'Name'),
            'frequency' => Yii::t('attribute', 'Frequency'),
        ]);
    }

    /**
     * 获取自定义属性列表
     * @param boolean $all // 是否查询出所有数据
     * @param boolean $group // 是否分组
     * @return array
     */
    public static function getItems($all = false, $group = false)
    {
        $items = [];
        $sql = 'SELECT [[id]], [[alias]], [[name]] FROM {{%label}} WHERE [[tenant_id]] = :tenantId';
        $params = [':tenantId' => Yad::getTenantId()];
        if (!$all) {
            $sql .= ' AND [[status]] = :status';
            $params[':status'] = Option::BOOLEAN_TRUE;
        }
        $sql .= ' ORDER BY [[alias]] ASC, [[ordering]] ASC';
        $rawData = \Yii::$app->db->createCommand($sql)->bindValues($params)->queryAll();
        $groupPrefix = null;
        foreach ($rawData as $data) {
            if ($group) {
                $index = strpos($data['alias'], '.');
                $groupPrefix = $index !== false ? substr($data['alias'], 0, $index) : '*';
                $items[$groupPrefix][$data['id']] = "{$data['alias']}: {$data['name']}";
            } else {
                $items[$data['id']] = "{$data['alias']}: {$data['name']}";
            }
        }

        return $items;
    }

    /**
     * 根据实体编号和实体名称获取关联的自定义属性列表
     * @param integer $entityId
     * @param string $entityName
     * @return array
     */
    public static function getEntityItems($entityId, $entityName)
    {
        $items = (new Query())->select('a.name')->from('{{%entity_lable}} t')
            ->leftJoin('{{%label}} a', '[[t.label_id]] = [[a.id]]')
            ->where([
                't.entity_id' => (int) $entityId,
                't.entity_name' => trim($entityName)
            ])
            ->indexBy('a.id')
            ->column();

        return $items;
    }

    /**
     * 根据实体编号和实体名称获取关联的自定义属性内容（文本）
     * @param integer $entityId
     * @param string $entityName
     * @return string
     */
    public static function getEntityItemSentence($entityId, $entityName)
    {
        $sentence = Inflector::sentence(static::getEntityItems($entityId, $entityName), '、', null, '、');
        if (!empty($sentence)) {
            $sentence = "<span class=\"attributes\">{$sentence}</span>";
        }

        return $sentence;
    }

    /**
     * 根据实体编号和实体名称获取关联的自定义属性编号列表
     * @param integer $entityId
     * @param string $entityName
     * @return array
     */
    public static function getEntityAttributeIds($entityId, $entityName)
    {
        return \Yii::$app->getDb()->createCommand('SELECT [[label_id]] FROM {{%entity_label}} WHERE [[entity_id]] = :entityId AND [[entity_name]] = :entityName')->bindValues([':entityId' => (int) $entityId, ':entityName' => $entityName])->queryColumn();
    }

    /**
     * 根据自定义属性 id 和 模型名称获取关联的数据 id
     * @param integer $labelId
     * @param string $entityName
     * @return array
     */
    public static function getEntityIds($labelId, $entityName)
    {
        return \Yii::$app->db->createCommand('SELECT [[entity_id]] FROM {{%entity_label}} WHERE [[label_id]] = :labelId AND [[entity_name]] = :entityName')->bindValues([':labelId' => (int) $labelId, ':entityName' => $entityName])->queryColumn();
    }

    // Events
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->frequency = 0;
            }

            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        Yii::$app->db->createCommand()->delete('{{%entity_label}}', 'label_id = :labelId', [':labelId' => $this->id])->execute();
    }

}
