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
 * @property integer $enabled
 * @property integer $ordering
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
            [['name', 'ordering'], 'required'],
            [['type', 'parent_id', 'level', 'enabled', 'ordering', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['type', 'parent_id', 'level'], 'default', 'value' => 0],
            [['enabled'], 'boolean'],
            [['enabled'], 'default', 'value' => Constant::BOOLEAN_TRUE],
            [['description'], 'string'],
            [['alias'], 'string', 'max' => 20],
            ['alias', 'match', 'pattern' => '/^[a-z]+[a-z-]+[a-z]$/'],
            [['name'], 'string', 'max' => 30],
            [['parent_ids', 'icon_path'], 'string', 'max' => 100],
            [['parent_names'], 'string', 'max' => 255],
            ['alias', 'unique', 'targetAttribute' => ['alias', 'tenant_id']],
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
            'icon_path' => Yii::t('category', 'Icon'),
            'description' => Yii::t('category', 'Description'),
            'ordering' => Yii::t('app', 'Ordering'),
        ]);
    }

    /**
     * 类别选项
     * @return array
     */
    public static function typeOptions()
    {
        return Lookup::getValue('m.models.category.type', []);
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
        $bindValues = [':tenantId' => Yad::getTenantId()];
        $sql = 'SELECT [[id]], [[name]], [[parent_id]] FROM {{%category}} WHERE [[tenant_id]] = :tenantId';
        if (!$all) {
            $sql .= ' AND enabled = :enabled';
            $bindValues[':enabled'] = Constant::BOOLEAN_TRUE;
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

    public static function sortItems($tree)
    {
        $ret = [];
        if (isset($tree['children']) && is_array($tree['children'])) {
            $children = $tree['children'];
            unset($tree['children']);
            $ret[] = $tree;
            foreach ($children as $child) {
                $ret = array_merge($ret, self::sortItems($child, 'children'));
            }
        } else {
            unset($tree['children']);
            $ret[] = $tree;
        }

        return $ret;
    }

    /**
     * 获取所有父节点数据
     * @param mixed|integer $id
     * @return array
     */
    public static function getParents($id)
    {
        $parents = [];
        $row = Yii::$app->getDb()->createCommand('SELECT * FROM {{%category}} WHERE [[id]] = :id', [':id' => $id])->queryOne();
        $parents[] = $row;
        if ($row['parent_id']) {
            $parents = array_merge($parents, static::getParents($row['parent_id']));
        }

        return ArrayHelper::sortByCol($parents, 'parent_id');
    }

    /**
     * 判断是否有子节点
     * @param integer $id
     * @return boolean
     */
    private static function hasChildren($id)
    {
        return Yii::$app->getDb()->createCommand('SELECT COUNT(*) FROM {{%category}} WHERE parent_id = :parentId', [':parentId' => (int) $id])->queryScalar();
    }

    /**
     * 获取所有子节点数据
     * @param mixed|integer $parent
     * @return array
     */
    public static function getChildren($parent = null)
    {
        $children = [];
        $sql = 'SELECT * FROM {{%category}} WHERE [[tenant_id]] = :tenantId';
        $bindValues = [':tenantId' => Yad::getTenantId()];
        if ($parent) {
            $sql.= ' AND [[parent_id]] = :parentId';
            $bindValues[':parentId'] = $parent;
        }
        $rawData = Yii::$app->getDb()->createCommand($sql, $bindValues)->queryAll();
        foreach ($rawData as $data) {
            $children[] = $data;
            if (static::hasChildren($data['id'])) {
                $children = array_merge($children, static::getChildren($data['id']));
            }
        }

        return $children;
    }

    /**
     * 获取所有子节点 id 集合
     * @param mixed|integer $parent
     * @return array
     */
    public static function getChildrenIds($parent = null)
    {
        $children = static::getChildren($parent);

        return $children ? \yii\helpers\ArrayHelper::getColumn($children, 'id') : [];
    }

    // 事件
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->alias) && !empty($this->name)) {
                $this->alias = Inflector::slug($this->name);
            }
            if ($this->parent_id && strpos($this->alias, '/') === false) {
                $parentAlias = Yii::$app->getDb()->createCommand('SELECT [[alias]] FROM {{%category}} WHERE [[id]] = :id', [':id' => $this->parent_id])->queryScalar();
                $this->alias = "{$parentAlias}/{$this->alias}";
            }

            return true;
        } else {
            return false;
        }
    }

}
