<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property integer $id
 * @property string $alias
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $content
 * @property string $picture_path
 * @property integer $status
 * @property integer $tenant_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property integer $deleted_at
 * @property integer $deleted_by
 */
class Article extends BaseActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'title', 'content'], 'required'],
            [['description', 'content'], 'string'],
            [['status', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['alias'], 'string', 'max' => 30],
            [['title', 'keywords', 'picture_path'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => '别名',
            'title' => '标题',
            'keywords' => '关键词',
            'description' => '描述',
            'content' => '正文',
            'picture_path' => '图片',
            'status' => '状态',
            'tenant_id' => '所属站点',
            'created_at' => '添加时间',
            'created_by' => '添加人',
            'updated_at' => '更新时间',
            'updated_by' => '更新人',
            'deleted_at' => '删除时间',
            'deleted_by' => '删除人',
        ];
    }

}
