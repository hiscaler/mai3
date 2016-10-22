<?php

namespace app\models;

use app\modules\admin\components\ApplicationHelper;
use yadjet\behaviors\FileUploadBehavior;
use Yii;

/**
 * This is the model class for table "{{%download}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $path_type
 * @property string $url_path
 * @property string $file_path
 * @property string $cover_photo_path
 * @property string $keywords
 * @property string $description
 * @property integer $pay_credits
 * @property integer $clicks_count
 * @property integer $downloads_count
 * @property integer $enabled
 * @property integer $tenant_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property integer $deleted_at
 * @property integer $deleted_by
 */
class Download extends \yii\db\ActiveRecord
{

    use ActiveRecordHelperTrait;

    /**
     * 地址类型
     */
    const PATH_TYPE_URL = 0;
    const PATH_TYPE_FILE = 1;

    private $_fileUploadConfig;
    private $_coverPhotoUploadConfig;

    public function init()
    {
        $this->_fileUploadConfig = FileUploadConfig::getConfig(static::className2Id(), 'file_path');
        $this->_coverPhotoUploadConfig = FileUploadConfig::getConfig(static::className2Id(), 'cover_photo_path');
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%download}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'path_type'], 'required'],
            ['url_path', 'required',
                'when' => function ($model) {
                    return $model->path_type == self::PATH_TYPE_URL;
                },
                'whenClient' => "function (attribute, value) {
                    return $('#download-path_type').val() == 0;
                }",
            ],
            ['file_path', 'required',
                'when' => function ($model) {
                    return $model->path_type == self::PATH_TYPE_FILE;
                },
                'whenClient' => "function (attribute, value) {
                    return $('#download-path_type').val() == 1;
                }",
            ],
            [['url_path'], 'url'],
            [['description'], 'string'],
            [['path_type', 'pay_credits', 'clicks_count', 'downloads_count', 'enabled', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['title', 'keywords'], 'string', 'max' => 100],
            [['url_path'], 'string', 'max' => 200],
            ['file_path', 'file',
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
            ['cover_photo_path', 'file',
                'extensions' => $this->_coverPhotoUploadConfig['extensions'],
                'minSize' => $this->_coverPhotoUploadConfig['size']['min'],
                'maxSize' => $this->_coverPhotoUploadConfig['size']['max'],
                'tooSmall' => Yii::t('app', 'The file "{file}" is too small. Its size cannot be smaller than {limit}.', [
                    'limit' => ApplicationHelper::friendlyFileSize($this->_coverPhotoUploadConfig['size']['min']),
                ]),
                'tooBig' => Yii::t('app', 'The file "{file}" is too big. Its size cannot exceed {limit}.', [
                    'limit' => ApplicationHelper::friendlyFileSize($this->_coverPhotoUploadConfig['size']['max']),
                ]),
            ],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => FileUploadBehavior::className(),
                'attribute' => 'file_path'
            ],
            [
                'class' => FileUploadBehavior::className(),
                'attribute' => 'cover_photo_path'
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
            'title' => Yii::t('download', 'Title'),
            'path_type' => Yii::t('download', 'Path Type'),
            'url_path' => Yii::t('download', 'URL'),
            'file_path' => Yii::t('download', 'File'),
            'cover_photo_path' => Yii::t('download', 'Cover Photo'),
            'keywords' => Yii::t('app', 'Page Keywords'),
            'description' => Yii::t('app', 'Page Description'),
            'pay_credits' => Yii::t('download', 'Pay Credits'),
            'clicks_count' => Yii::t('download', 'Clicks Count'),
            'downloads_count' => Yii::t('download', 'Downloads Count'),
            'enabled' => Yii::t('app', 'Enabled'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    /**
     * 地址类型
     * @return array
     */
    public static function pathTypeOptions()
    {
        return [
            self::PATH_TYPE_URL => Yii::t('download', 'URL'),
            self::PATH_TYPE_FILE => Yii::t('download', 'File'),
        ];
    }

}
