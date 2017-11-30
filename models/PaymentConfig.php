<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%payment_config}}".
 *
 * @property integer $id
 * @property string $key
 * @property string $name
 * @property string $config
 * @property string $description
 * @property integer $ordering
 * @property integer $enabled
 * @property integer $tenant_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class PaymentConfig extends BaseActiveRecord
{

    public $configs = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'name', 'description'], 'required'],
            [['config', 'description'], 'string'],
            [['ordering', 'enabled', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['key'], 'string', 'max' => 16],
            ['key', 'unique', 'targetAttribute' => ['key', 'tenant_id']],
            [['name'], 'string', 'max' => 100],
            [['configs'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'key' => Yii::t('app', 'Key'),
            'name' => Yii::t('app', 'Name'),
            'config' => Yii::t('app', 'Config'),
            'description' => Yii::t('app', 'Description'),
            'ordering' => Yii::t('app', 'Ordering'),
            'enabled' => Yii::t('app', 'Enabled'),
            'tenant_id' => Yii::t('app', 'Tenant ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * 支付方式
     *
     * @return array
     */
    public static function keyOptions()
    {
        $options = [];
        $rawData = isset(Yii::$app->params['pay']) ? Yii::$app->params['pay'] : [];
        foreach ($rawData as $key => $value) {
            $options[$key] = $key;
        }

        return $options;
    }

    /**
     * 检测支付配置是否有效
     *
     * @param string $key
     * @return boolean
     */
    public static function validateConfig($key)
    {
        $valid = true;
        if (isset(Yii::$app->params['pay'][$key]) && $config = Yii::$app->params['pay'][$key]) {
            foreach ($config as $conf) {
                if (
                    !isset($conf['name']) ||
                    !isset($conf['type']) ||
                    !in_array($conf['type'], ['text', 'select', 'radio']) ||
                    ($conf['type'] == 'select' && !isset($conf['items']) || ($conf['type'] == 'select' && !$conf['items']))
                ) {
                    $valid = false;
                    break;
                }
            }
        } else {
            $valid = false;
        }

        return $valid;
    }

    // 事件
    public function afterFind()
    {
        parent::afterFind();
        if ($this->config) {
            $config = unserialize($this->config);
            if (is_array($config)) {
                $this->configs = $config;
            }
        } else {
            $this->configs = [];
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->key = strtolower($this->key);
            $paymentDataFormat = isset(Yii::$app->params['pay'][$this->key]) ? Yii::$app->params['pay'][$this->key] : [];
            foreach ($paymentDataFormat as $key => $value) {
                if (isset($this->configs[$key])) {
                    $paymentDataFormat[$key]['value'] = $this->configs[$key];
                }
            }
            $this->config = serialize($paymentDataFormat);

            return true;
        } else {
            return false;
        }
    }

}
