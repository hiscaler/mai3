<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%lookup}}".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $group
 * @property string $key
 * @property string $label
 * @property string $description
 * @property string $value
 * @property integer $return_type
 * @property string $input_method
 * @property string $input_value
 * @property integer $enabled
 * @property integer $tenant_id
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 */
class Lookup extends BaseActiveRecord
{

    /**
     * Types, private or public
     */
    const TYPE_PRIVATE = 0; // 私有配置
    const TYPE_PUBLIC = 1; // 共有配置

    /**
     * Groups
     */
    const GROUP_CUSTOM = 0;
    const GROUP_SYSTEM = 1;
    const GROUP_SEO = 2;

    /**
     * Return types
     */
    const RETURN_TYPE_INTEGER = 0;
    const RETURN_TYPE_STRING = 1;
    const RETURN_TYPE_ARRAY = 2;
    const RETURN_TYPE_BOOLEAN = 3;

    /**
     * Input methods
     */
    const INPUT_METHOD_TEXT = 'text';
    const INPUT_METHOD_TEXTAREA = 'textarea';
    const INPUT_METHOD_DROPDOWNLIST = 'dropdownlist';
    const INPUT_METHOD_CHECKBOX = 'checkbox';
    const INPUT_METHOD_RADIO = 'radio';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lookup}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'label', 'value', 'return_type', 'input_method', 'enabled'], 'required'],
            [['label', 'value', 'description'], 'trim'],
            [['group'], 'integer'],
            [['group'], 'default', 'value' => self::GROUP_CUSTOM],
            [['type'], 'boolean'],
            [['type'], 'default', 'value' => self::TYPE_PUBLIC],
            ['key', 'match', 'pattern' => '/^[a-z][a-z.].*[a-z]$/'],
            ['label', 'unique'],
            ['enabled', 'default', 'value' => 0],
            ['enabled', 'boolean'],
            [['return_type', 'enabled', 'tenant_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['key', 'label'], 'string', 'max' => 60],
            [['input_method'], 'string', 'max' => 12],
            [['description', 'value', 'input_value'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'key' => Yii::t('lookup', 'Key'),
            'label' => Yii::t('lookup', 'Label'),
            'description' => Yii::t('lookup', 'Description'),
            'value' => Yii::t('lookup', 'Value'),
            'return_type' => Yii::t('lookup', 'Return Type'),
            'return_type_text' => Yii::t('lookup', 'Return Type'),
            'input_method' => Yii::t('lookup', 'Input Method'),
            'input_value' => Yii::t('lookup', 'Input Value'),
        ]);
    }

    public static function getTypeOptions()
    {
        return [
            self::TYPE_PRIVATE => '私有配置',
            self::TYPE_PUBLIC => '公有配置',
        ];
    }

    /**
     * 分组选项
     *
     * @return array
     */
    public static function getGroupOptions()
    {
        return [
            self::GROUP_SYSTEM => '系统',
            self::GROUP_SEO => 'SEO',
            self::GROUP_CUSTOM => '自定义',
        ];
    }

    /**
     * 分组名称
     *
     * @return string|mixed
     */
    public function getGroup_text()
    {
        $options = self::getGroupOptions();

        return isset($options[$this->group]) ? $options[$this->group] : null;
    }

    public static function returnTypeOptions()
    {
        return [
            self::RETURN_TYPE_INTEGER => '整形',
            self::RETURN_TYPE_STRING => '字符型',
            self::RETURN_TYPE_ARRAY => '数组',
            self::RETURN_TYPE_BOOLEAN => '布尔值',
        ];
    }

    public function getReturn_type_text()
    {
        $options = self::returnTypeOptions();

        return isset($options[$this->return_type]) ? $options[$this->return_type] : null;
    }

    public static function inputMethodOptions()
    {
        return [
            self::INPUT_METHOD_TEXT => Yii::t('lookup', 'Text'),
            self::INPUT_METHOD_TEXTAREA => Yii::t('lookup', 'Textarea'),
            self::INPUT_METHOD_DROPDOWNLIST => Yii::t('lookup', 'DropdownList'),
            self::INPUT_METHOD_CHECKBOX => Yii::t('lookup', 'Checkbox'),
            self::INPUT_METHOD_RADIO => Yii::t('lookup', 'Radio'),
        ];
    }

    /**
     * 刷新缓存
     *
     * @return array
     */
    public static function refreshCache()
    {
        $keyValues = [];
        $rawData = Yii::$app->getDb()->createCommand('SELECT [[key]], [[value]], [[return_type]], [[input_method]], [[input_value]] FROM ' . static::tableName() . ' WHERE [[tenant_id]] = :tenantId AND [[enabled]] = :enabled', [':tenantId' => Yad::getTenantId(), ':enabled' => Constant::BOOLEAN_TRUE])->queryAll();
        foreach ($rawData as $data) {
            $value = unserialize($data['value']);
            switch ($data['return_type']) {
                case self::RETURN_TYPE_INTEGER:
                    $value = (int) $value;
                    break;

                case self::RETURN_TYPE_ARRAY:
                    // 返回的数组数据有定长和变长数组的区别，如果是定长数组，则写入到 value 字段中，直接返回。但是变长数组的话，则不能直接写入 value 字段，而应该保存到 input_methd 中，一行表示一个元素，键值以“:”进行分隔。
                    if ($data['input_method'] == self::INPUT_METHOD_DROPDOWNLIST) {
                        $value = [];
                        foreach (explode(PHP_EOL, $data['input_value']) as $key) {
                            $v = explode(':', $key);
                            if (count($v) == 2 && $v[0] != '' && $v[1] != '') {
                                $value[$v[0]] = $v[1];
                            }
                        }
                    }
                    if (!is_array($value)) {
                        $value = (array) $value;
                    }
                    break;

                case self::RETURN_TYPE_BOOLEAN:
                    $value = $value ? true : false;
                    break;

                default :
                    $value = is_string($value) ? $value : (string) $value;
            }
            $keyValues[$data['key']] = $value;
        }

        Yii::$app->getCache()->set('cache.model.lookup.refresh-cache', $keyValues);

        return $keyValues;
    }

    /**
     * 根据设定的标签获取值
     *
     * @param string $key
     * @param string $defaultValue
     * @return mixed
     */
    public static function getValue($key, $defaultValue = null)
    {
        $labelValues = Yii::$app->getCache()->get('cache.mode.lookup.refresh-cache');
        $labelValues === false && $labelValues = self::refreshCache();

        return isset($labelValues[$key]) ? $labelValues[$key] : $defaultValue;
    }

    // Events
    public function afterFind()
    {
        parent::afterFind();
        if (!$this->isNewRecord) {
            $this->value = unserialize($this->value);
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->value = serialize($this->value);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @throws \yii\web\HttpException
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        self::refreshCache();
    }

    public function afterDelete()
    {
        parent::afterDelete();
        self::refreshCache();
    }

}
