<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%type_property}}".
 *
 * @property integer $id
 * @property integer $type_id
 * @property string $name
 * @property integer $return_type
 * @property string $input_method
 * @property string $input_values
 * @property string $input_default_value
 * @property integer $ordering
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class TypeProperty extends \yii\db\ActiveRecord
{

    /**
     * 返回值类型
     */
    const RETURN_TYPE_STRING = 0;
    const RETURN_TYPE_INTEGER = 1;

    /**
     * 输入方式
     */
    const INPUT_METHOD_TEXT = 0;
    const INPUT_METHOD_TEXTAREA = 1;
    const INPUT_METHOD_DROPDOWNLIST = 2;
    const INPUT_METHOD_CHECKBOX = 3;
    const INPUT_METHOD_RADIO = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%type_property}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'name', 'input_method'], 'required'],
            [['type_id', 'return_type', 'ordering', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['input_method'], 'string', 'max' => 12],
            [['input_values', 'input_default_value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => '商品类型 id',
            'name' => '属性名称',
            'return_type' => '返回值类型',
            'input_method' => '录入方式',
            'input_values' => '录入选项',
            'input_default_value' => '默认值',
            'ordering' => '排序',
            'status' => '状态',
            'created_at' => '添加时间',
            'created_by' => '添加人',
            'updated_at' => '更新时间',
            'updated_by' => '更新人',
        ];
    }

    public static function returnTypeOptions()
    {
        return [
            self::RETURN_TYPE_STRING => 'String',
            self::RETURN_TYPE_INTEGER => 'Integer',
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
            self::INPUT_METHOD_TEXT => '文本框',
            self::INPUT_METHOD_TEXTAREA => '大段文本框',
            self::INPUT_METHOD_DROPDOWNLIST => '下拉框',
            self::INPUT_METHOD_CHECKBOX => '多选框',
            self::INPUT_METHOD_RADIO => '单选框',
        ];
    }

    public function getInput_method_text()
    {
        $options = self::inputMethodOptions();

        return isset($options[$this->input_method]) ? $options[$this->input_method] : null;
    }

    // Events
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = $this->updated_at = time();
                $this->created_by = $this->updated_by = Yii::$app->getUser()->getId();
            } else {
                $this->updated_at = time();
                $this->updated_by = Yii::$app->getUser()->getId();
            }
            
            return true;
        } else {
            return false;
        }
    }

}
