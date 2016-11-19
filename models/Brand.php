<?php

namespace app\models;

use app\modules\admin\components\ApplicationHelper;
use yadjet\behaviors\FileUploadBehavior;
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
 * @property integer $enabled
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Brand extends BaseActiveRecord
{

    use ActiveRecordHelperTrait;

    private $_fileUploadConfig;

    public function init()
    {
        $this->_fileUploadConfig = FileUploadConfig::getConfig(static::className2Id(), 'file_path');
        parent::init();
    }

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
            [['ordering', 'tenant_id', 'enabled', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['ordering'], 'default', 'value' => 1],
            [['alias', 'name'], 'string', 'max' => 20],
            ['alias', 'match', 'pattern' => '/^[a-zA-Z0-9]+[-]?[a-zA-Z-]+[a-zA-Z0-9]$/'],
            [['alias'], 'unique', 'targetAttribute' => ['alias', 'tenant_id']],
            [['name'], 'unique', 'targetAttribute' => ['name', 'tenant_id']],
            ['icon_path', 'file',
                'extensions' => $this->_fileUploadConfig['extensions'],
                'minSize' => $this->_fileUploadConfig['size']['min'],
                'maxSize' => $this->_fileUploadConfig['size']['max'],
                'tooSmall' => Yii::t('app', 'The file "{file}" is too small. Its size cannot be smaller than {limit}.', [
                    'limit' => ApplicationHelper::friendlyFileSize($this->_fileUploadConfig['size']['min']),
                ]),
                'tooBig' => Yii::t('app', 'The file "{file}" is too big. Its size cannot exceed {limit}.', [
                    'limit' => ApplicationHelper::friendlyFileSize($this->_fileUploadConfig['size']['max']),
                ]),
            ],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => FileUploadBehavior::className(),
                'attribute' => 'icon_path'
            ],
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
            'name' => Yii::t('brand', 'Name'),
            'icon_path' => Yii::t('brand', 'Icon Path'),
            'description' => Yii::t('brand', 'Description'),
            'ordering' => Yii::t('app', '排序'),
            'tenant_id' => Yii::t('app', 'Tenant ID'),
            'enabled' => Yii::t('app', 'Enabled'),
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
            $sql .= ' WHERE [[enabled]] = :enabled';
            $bindValues = [':enabled' => Constant::BOOLEAN_TRUE];
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
            if (empty($this->alias)) {
                $this->alias = Inflector::slug($this->name);
            }

            return true;
        } else {
            return false;
        }
    }

}
