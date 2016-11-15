<?php

namespace app\models;

use app\modules\admin\components\ApplicationHelper;
use yadjet\behaviors\ImageUploadBehavior;
use yadjet\behaviors\TaggedBehavior;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $short_title
 * @property string $keywords
 * @property string $tags
 * @property string $author
 * @property string $source
 * @property string $description
 * @property integer $is_picture_news
 * @property string $picture_path
 * @property integer $status
 * @property integer $enabled
 * @property integer $enabled_comment
 * @property integer $comments_count
 * @property integer $clicks_count
 * @property integer $up_count
 * @property integer $down_count
 * @property integer $ordering
 * @property integer $published_at
 * @property integer $tenant_id
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 * @property integer $deleted_by
 * @property integer $deleted_at
 */
class News extends BaseActiveRecord
{

    public $_fileUploadConfig;

    public function init()
    {
        $this->_fileUploadConfig = FileUploadConfig::getConfig(static::className2Id(), 'picture_path');
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news}}';
    }

    public function getNewsContent()
    {
        return $this->hasOne(NewsContent::className(), ['news_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['category_id', 'title', 'author', 'source', 'ordering', 'published_at'], 'required'],
            [['title', 'short_title', 'author', 'source', 'description'], 'trim'],
            ['published_at', 'date', 'format' => Yii::$app->getFormatter()->datetimeFormat, 'timestampAttribute' => 'published_at'],
            [['enabled', 'up_count', 'down_count'], 'default', 'value' => 0],
            ['enabled', 'boolean'],
            [['status', 'comments_count', 'clicks_count', 'up_count', 'down_count', 'ordering', 'tenant_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'deleted_by', 'deleted_at'], 'integer'],
            [['is_picture_news', 'enabled_comment'], 'boolean'],
            ['clicks_count', 'default', 'value' => 0],
            [['description'], 'string'],
            [['title', 'short_title', 'author', 'source'], 'string', 'max' => 255],
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
            ],
        ]);
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => TaggedBehavior::className(),
                'attribute' => 'tags',
            ],
            [
                'class' => ImageUploadBehavior::className(),
                'attribute' => 'picture_path',
                'thumb' => $this->_fileUploadConfig['thumb']
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'author' => Yii::t('news', 'Author'),
            'source' => Yii::t('news', 'Source'),
            'is_picture_news' => Yii::t('news', 'Is Picture News'),
            'picture_path' => Yii::t('news', 'Picture'),
            'enabled_comment' => Yii::t('news', 'Enabled Comment'),
            'comments_count' => Yii::t('news', 'Comments Count'),
            'published_at' => Yii::t('news', 'Published At'),
        ]);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * 保存资讯正文内容
     * @param ActiveReocrd $newsContent
     * @return boolean
     */
    public function saveContent($newsContent)
    {
        $newsContent->news_id = $this->id;
        return $newsContent->save();
    }

    /**
     * 处理正文内容中的图片，如果没有上传附件图片并且设定了图片的获取位置才会进行解析操作
     * @param ActiveRecord $model
     */
    public function processPicturePath($model)
    {
        if (!(UploadedFile::getInstance($model, 'picture_path') instanceof UploadedFile) && $number = $model->content_image_number) {
            $picturePath = Yad::getTextImages($model->newsContent->content, $number);
            if (!empty($picturePath)) {
                Yii::$app->getDb()->createCommand()->update('{{%news}}', [
                    'is_picture_news' => Constant::BOOLEAN_TRUE,
                    'picture_path' => $picturePath,
                    ], '[[id]] = :id', [':id' => $model->id])->execute();
            }
        }
    }

    // Events
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $file = UploadedFile::getInstance($this, 'picture_path');
            if ($file instanceof UploadedFile && $file->error != UPLOAD_ERR_NO_FILE) {
                $this->is_picture_news = Constant::BOOLEAN_TRUE;
            }

            return true;
        } else {
            return false;
        }
    }

}
