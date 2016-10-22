<?php

namespace app\models;

use app\modules\admin\components\ApplicationHelper;
use yadjet\behaviors\ImageUploadBehavior;
use Yii;

/**
 * This is the model class for table "www_slide".
 *
 * @property integer $id
 * @property integer $group_id
 * @property string $title
 * @property string $url
 * @property string $url_open_target
 * @property string $picture_path
 * @property integer $ordering
 * @property integer $enabled
 * @property integer $tenant_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Slide extends \yii\db\ActiveRecord
{

    const GROUP_KEY = 'm.models.slide.group';

    use ActiveRecordHelperTrait;

    public $_fileUploadConfig;

    public function init()
    {
        $this->_fileUploadConfig = FileUploadConfig::getConfig(static::className2Id(), 'picture_path');
        parent::init();
    }

    /**
     * 链接打开方式
     */
    const URL_OPEN_TARGET_BLANK = '_blank';
    const URL_OPEN_TARGET_SLFE = '_self';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%slide}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'ordering', 'enabled', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['title', 'url'], 'required'],
            [['title'], 'string', 'max' => 60],
            [['url'], 'string', 'max' => 100],
            [['url_open_target'], 'string', 'max' => 6],
            [['url'], 'url', 'defaultScheme' => 'http'],
            ['group_id', 'default', 'value' => 0],
            ['picture_path', 'image',
                'extensions' => $this->_fileUploadConfig['extensions'],
                'minSize' => $this->_fileUploadConfig['size']['min'],
                'maxSize' => $this->_fileUploadConfig['size']['max'],
                'tooSmall' => Yii::t('app', 'The file "{file}" is too small. Its size cannot be smaller than {limit}.', [
                    'limit' => ApplicationHelper::friendlyFileSize($this->_fileUploadConfig['size']['min']),
                ]),
                'tooBig' => Yii::t('app', 'The file "{file}" is too big. Its size cannot exceed {limit}.', [
                    'limit' => ApplicationHelper::friendlyFileSize($this->_fileUploadConfig['size']['max']),
                ]),
            ],];
    }

    public function behaviors()
    {
        return [
            [
                'class' => ImageUploadBehavior::className(),
                'attribute' => 'picture_path',
                'thumb' => $this->_fileUploadConfig['thumb']
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
            'group_id' => Yii::t('app', 'Group'),
            'group_name' => Yii::t('app', 'Group'),
            'title' => Yii::t('slide', 'Title'),
            'url' => Yii::t('slide', 'URL'),
            'url_open_target' => Yii::t('slide', 'URL Open Target'),
            'url_open_target_text' => Yii::t('slide', 'URL Open Target'),
            'picture_path' => Yii::t('slide', 'Picture'),
            'ordering' => Yii::t('app', 'Ordering'),
            'enabled' => Yii::t('app', 'Enabled'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public static function urlOpenTargetOptions()
    {
        return [
            self::URL_OPEN_TARGET_BLANK => Yii::t('slide', 'Blank'),
            self::URL_OPEN_TARGET_SLFE => Yii::t('slide', 'Self'),
        ];
    }

    public function getUrl_open_target_text()
    {
        $options = self::urlOpenTargetOptions();
        return isset($options[$this->url_open_target]) ? $options[$this->url_open_target] : null;
    }

    public function getGroup_name()
    {
        return Lookup::getValue(static::GROUP_KEY);
    }

    // Events
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->tenant_id = Yad::getTenantId();
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
