<?php

namespace app\models;

use app\models\FileUploadConfig;
use app\modules\admin\components\ApplicationHelper;
use yadjet\behaviors\ImageUploadBehavior;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%friendly_link}}".
 *
 * @property integer $id
 * @property integer $group_id
 * @property integer $type
 * @property string $title
 * @property string $description
 * @property string $url
 * @property string $url_open_target
 * @property string $logo_path
 * @property integer $ordering
 * @property integer $enabled
 * @property integer $tenant_id
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 */
class FriendlyLink extends BaseActiveRecord
{

    /**
     * 友情链接类型
     */
    const TYPE_TEXT = 0;
    const TYPE_PICTURE = 1;

    /**
     * 链接打开窗口
     */
    const URL_OPEN_TARGET_SELF = '_self';
    const URL_OPEN_TARGET_BLANK = '_blank';

    private $_oldType;
    public $_fileUploadConfig;

    public function init()
    {
        $this->_fileUploadConfig = FileUploadConfig::getConfig(static::className2Id(), 'logo_path');
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%friendly_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'title', 'url', 'url_open_target', 'ordering'], 'required'],
            [['title', 'url', 'description'], 'trim'],
            ['group_id', 'default', 'value' => 0],
            [['url'], 'url'],
            ['url', 'unique', 'targetAttribute' => ['url']],
            [['enabled'], 'boolean'],
            [['group_id', 'type', 'tenant_id', 'ordering', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['title', 'description', 'url', 'url_open_target'], 'string', 'max' => 255],
            ['logo_path', 'image',
                'extensions' => $this->_fileUploadConfig['extensions'],
                'minSize' => $this->_fileUploadConfig['size']['min'],
                'maxSize' => $this->_fileUploadConfig['size']['max'],
                'skipOnEmpty' => false,
                'on' => 'isPictureLink',
                'tooSmall' => Yii::t('app', 'The file "{file}" is too small. Its size cannot be smaller than {limit}.', [
                    'limit' => ApplicationHelper::friendlyFileSize($this->_fileUploadConfig['size']['min']),
                ]),
                'tooBig' => Yii::t('app', 'The file "{file}" is too big. Its size cannot exceed {limit}.', [
                    'limit' => ApplicationHelper::friendlyFileSize($this->_fileUploadConfig['size']['max']),
                ]),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'type' => Yii::t('friendlyLink', 'Type'),
            'title' => Yii::t('friendlyLink', 'Title'),
            'description' => Yii::t('friendlyLink', 'Description'),
            'url' => Yii::t('friendlyLink', 'URL'),
            'url_open_target' => Yii::t('friendlyLink', 'Open Target'),
            'logo_path' => Yii::t('friendlyLink', 'Logo'),
        ]);
    }

    public function behaviors()
    {
        return [
            [
                'class' => ImageUploadBehavior::className(),
                'attribute' => 'logo_path',
                'thumb' => $this->_fileUploadConfig['thumb']
            ],
        ];
    }

    public static function typeOptions()
    {
        return [
            self::TYPE_TEXT => Yii::t('friendlyLink', 'Text'),
            self::TYPE_PICTURE => Yii::t('friendlyLink', 'Picture'),
        ];
    }

    public static function groupOptions()
    {
        return Lookup::getValue('system.models.friendly-link.group', []);
    }

    public static function urlOpenTargetOptions()
    {
        return [
            self::URL_OPEN_TARGET_SELF => Yii::t('friendlyLink', 'Self'),
            self::URL_OPEN_TARGET_BLANK => Yii::t('friendlyLink', 'Blank'),
        ];
    }

    // Events
    public function afterFind()
    {
        parent::afterFind();
        $this->_oldType = $this->type;
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if ($this->type == self::TYPE_PICTURE) {
                if ($this->isNewRecord) {
                    $this->setScenario('isPictureLink');
                } else {
                    if ($this->_oldType == self::TYPE_TEXT) {
                        $this->setScenario('isPictureLink');
                    }
                    $file = UploadedFile::getInstance($this, 'logo_path');
                    if ($file instanceof UploadedFile && $file->error != UPLOAD_ERR_NO_FILE) {
                        $this->setScenario('isPictureLink');
                    }
                }
            }

            return true;
        } else {
            return false;
        }
    }

}
