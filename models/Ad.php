<?php

namespace app\models;

use app\modules\admin\components\ApplicationHelper;
use PDO;
use yadjet\behaviors\FileUploadBehavior;
use yadjet\helpers\DatetimeHelper;
use Yii;
use yii\db\ActiveQueryInterface;

/**
 * This is the model class for table "{{%ad}}".
 *
 * @property integer $id
 * @property integer $space_id
 * @property string $name
 * @property string $url
 * @property integer $type
 * @property string $file_path
 * @property string $text
 * @property integer $begin_datetime
 * @property integer $end_datetime
 * @property string $message
 * @property integer $views_count
 * @property integer $clicks_count
 * @property integer $enabled
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 */
class Ad extends BaseActiveRecord
{

    public $_fileUploadConfig;

    public function init()
    {
        $this->_fileUploadConfig = FileUploadConfig::getConfig(static::className2Id(), 'file_path');
        parent::init();
    }

    /**
     * AD types
     */
    const TYPE_PICTURE = 0;
    const TYPE_FLASH = 1;
    const TYPE_TEXT = 2;

    private $_oldType;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ad}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['space_id', 'name', 'type', 'begin_datetime', 'end_datetime'], 'required'],
            [['name', 'url', 'message'], 'trim'],
            ['url', 'url'],
            [['file_path'], 'required', 'on' => ['picture', 'flash']],
            ['enabled', 'boolean'],
            [['begin_datetime'], 'date', 'type' => 'datetime', 'format' => Yii::$app->getFormatter()->datetimeFormat, 'timestampAttribute' => 'begin_datetime', 'timestampAttributeFormat' => Yii::$app->getFormatter()->datetimeFormat, 'timestampAttributeTimeZone' => 'PRC'],
            [['end_datetime'], 'date', 'type' => 'datetime', 'format' => Yii::$app->getFormatter()->datetimeFormat, 'timestampAttribute' => 'end_datetime'],
            [['space_id', 'type', 'views_count', 'clicks_count', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['text'], 'string'],
            [['name', 'url', 'message'], 'string', 'max' => 255],
            ['file_path', 'file',
                'on' => 'picture',
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
            ['file_path', 'file',
                'on' => 'flash',
                'extensions' => 'swf',
                'minSize' => 1,
                'maxSize' => 409600, // 400KB
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
                'attribute' => 'file_path'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'space_id' => Yii::t('ad', 'Space'),
            'name' => Yii::t('ad', 'Name'),
            'url' => Yii::t('ad', 'URL'),
            'type' => Yii::t('ad', 'Type'),
            'type_text' => Yii::t('ad', 'Type'),
            'file_path' => Yii::t('ad', 'File'),
            'text' => Yii::t('ad', 'Text'),
            'begin_datetime' => Yii::t('ad', 'Begin Datetime'),
            'end_datetime' => Yii::t('ad', 'End Datetime'),
            'message' => Yii::t('ad', 'Message'),
            'views_count' => Yii::t('ad', 'Views Count'),
            'clicks_count' => Yii::t('ad', 'Clicks Count'),
        ]);
    }

    public static function typeOptions()
    {
        return [
            self::TYPE_PICTURE => Yii::t('ad', 'Picture Type'),
            self::TYPE_FLASH => Yii::t('ad', 'Flash Type'),
            self::TYPE_TEXT => Yii::t('ad', 'Text Type'),
        ];
    }

    public function getType_text()
    {
        $options = self::typeOptions();

        return isset($options[$this->type]) ? $options[$this->type] : null;
    }

    /**
     * Space relational
     *
     * @return ActiveQueryInterface the relational query object.
     */
    public function getSpace()
    {
        return $this->hasOne(AdSpace::className(), ['id' => 'space_id'])->select(['id', 'name', 'alias']);
    }

    // Events
    public function afterFind()
    {
        parent::afterFind();
        $this->_oldType = $this->type;
        if (!$this->isNewRecord) {
            $formatter = Yii::$app->getFormatter();
            $this->begin_datetime = $formatter->asDate($this->begin_datetime);
            $this->end_datetime = $formatter->asDate($this->end_datetime);
        }
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                if (in_array($this->type, [static::TYPE_PICTURE, static::TYPE_FLASH])) {
                    if ($this->type == static::TYPE_PICTURE) {
                        $this->setScenario('picture');
                    } else {
                        $this->setScenario('flash');
                    }
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->views_count = 0;
                $this->clicks_count = 0;
            }
            if ($this->type == static::TYPE_TEXT) {
                $this->file_path = null;
            } else {
                $this->text = null;
            }

            $this->begin_datetime = DatetimeHelper::mktime($this->begin_datetime);
            $this->end_datetime = DatetimeHelper::mktime($this->end_datetime);

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            Yii::$app->getDb()->createCommand('UPDATE {{%ad_space}} SET [[ads_count]] = [[ads_count]] + 1 WHERE [[id]] = :id')->bindValue(':id', $this->space_id, PDO::PARAM_INT)->execute();
        }
    }

}
