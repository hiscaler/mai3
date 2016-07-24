<?php

namespace common\models;

use PDO;
use yadjet\helpers\StringHelper;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\validators\UrlValidator;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%item}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $type_id
 * @property integer $brand_id
 * @property string $sn
 * @property string $name
 * @property integer $market_price
 * @property integer $shop_price
 * @property integer $member_price
 * @property string $picture_path
 * @property string $keywords
 * @property string $description
 * @property integer $ordering
 * @property integer $clicks_count
 * @property integer $sales_count
 * @property integer $status
 * @property integer $tenant_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Item extends BaseActiveRecord
{

    private $_content = null;

    /**
     * 商品描述
     * @var string
     */
    public $content;

    /**
     * 商品图片
     * @var array
     */
    public $imageFiles;

    /**
     * SKU 列表
     * @var array
     */
    public $skuItems;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'sn', 'name', 'market_price', 'shop_price', 'member_price', 'keywords', 'content'], 'required'],
            [['name', 'keywords', 'description'], 'trim'],
            ['sn', 'match', 'pattern' => '/^[a-zA-Z0-9]+[a-zA-Z0-9_][a-zA-Z0-9]$/'],
            [['status'], 'boolean'],
            [['category_id', 'type_id', 'brand_id', 'market_price', 'shop_price', 'member_price', 'ordering', 'clicks_count', 'sales_count', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['description', 'content'], 'string'],
            [['sn'], 'string', 'max' => 16],
            ['sn', 'unique', 'targetAttribute' => ['sn', 'tenant_id']],
            [['name'], 'string', 'max' => 50],
            [['picture_path', 'keywords'], 'string', 'max' => 100],
            [['imageFiles', 'skuItems'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category_id' => Yii::t('item', 'Category'),
            'type_id' => Yii::t('item', '商品类型'),
            'brand_id' => Yii::t('item', 'Brand'),
            'sn' => Yii::t('item', 'Sn'),
            'name' => Yii::t('item', 'Name'),
            'market_price' => Yii::t('app', '市场价'),
            'shop_price' => Yii::t('app', '店铺价'),
            'member_price' => Yii::t('app', '会员价'),
            'picture_path' => Yii::t('item', 'Picture Path'),
            'keywords' => Yii::t('app', 'Page Keywords'),
            'description' => Yii::t('app', 'Page Description'),
            'ordering' => Yii::t('app', '排序'),
            'clicks_count' => Yii::t('app', '点击量'),
            'sales_count' => Yii::t('app', '销售量'),
            'status' => Yii::t('app', 'Status'),
            'tenant_id' => Yii::t('app', 'Tenant ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'content' => Yii::t('item', 'Content'),
        ];
    }

    /**
     * 所属分类
     * @return ActiveRecord
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * 所属品牌
     * @return ActiveRecord
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * 商品图片
     * @return ActiveRecord
     */
    public function getImages()
    {
        return $this->hasMany(ItemImage::className(), ['item_id' => 'id']);
    }

    // 事件
    public function afterFind()
    {
        parent::afterFind();
        if (!$this->isNewRecord) {
            $this->content = $this->_content = Yii::$app->getDb()->createCommand('SELECT [[content]] FROM {{%item_content}} WHERE [[item_id]] = :itemId')->bindValue(':itemId', $this->id, PDO::PARAM_INT)->queryScalar();
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $userId = Yii::$app->getUser()->getId();
        $now = time();
        $db = Yii::$app->getDb();
        if ($insert) {
            $db->createCommand()->insert('{{%item_content}}', [
                'item_id' => $this->id,
                'content' => $this->content,
                'created_at' => $now,
                'created_by' => $userId,
                'updated_at' => $now,
                'updated_by' => $userId,
            ])->execute();
        } else {
            if ($this->content != $this->_content) {
                $db->createCommand()->update('{{%item_content}}', [
                    'content' => $this->content,
                    'updated_at' => $now,
                    'updated_by' => $userId
                    ], ['item_id' => $this->id])->execute();
            }
        }

        // 处理上传的图片
        $images = $this->imageFiles;
        if ($images) {
            $imageFiles = UploadedFile::getInstances($this, 'imageFiles');
            $imageUrls = $images['url'];
            $imageDescriptions = $images['description'];

            $batchInsertRows = [];
            $urlValidator = new UrlValidator();
            $imageSavePath = '/uploads/' . date('Ymd') . '/';
            $checkDirectory = true;
            foreach ($imageFiles as $key => $file) {
                $imgUrl = $imgPath = null;
                if ($file !== null || !empty($imageUrls[$key])) {
                    if (!empty($imageUrls[$key]) && $urlValidator->validate($imageUrls[$key])) {
                        $imgUrl = $imageUrls[$key];
                    } elseif ($file) {
                        if ($checkDirectory) {
                            if (!is_dir(Yii::getAlias('@webroot' . $imageSavePath))) {
                                FileHelper::createDirectory(Yii::getAlias('@webroot' . $imageSavePath));
                            } else {
                                $checkDirectory = false;
                            }
                        }
                        $imgPath = $imageSavePath . StringHelper::generateRandomString() . '.' . $file->getExtension();
                        if (!$file->saveAs(Yii::getAlias('@webroot') . $imgPath)) {
                            $imgPath = null;
                        }
                    }
                    if ($imgUrl || $imgPath) {
                        $columns = [
                            'item_id' => $this->id,
                            'url' => $imgUrl,
                            'path' => $imgPath,
                            'description' => $imageDescriptions[$key] ? : ($file ? $file->getBaseName() : null),
                            'created_at' => $now,
                            'created_by' => $userId
                        ];
                        $batchInsertRows[] = array_values($columns);
                    }
                }
            }

            if ($batchInsertRows) {
                $db->createCommand()->batchInsert('{{%item_image}}', array_keys($columns), $batchInsertRows)->execute();
            }
        }

        // SKU 处理
        $skuItems = $this->skuItems;
        $cmd = $db->createCommand();
        $skuCmd = $db->createCommand('SELECT [[id]] FROM {{%item_sku}} WHERE [[sku_sn]] = :sn AND item_id = ' . $this->id);
        if (isset($skuItems['id']) && $skuItems['id']) {
            foreach ($skuItems['id'] as $key => $id) {
                $skuSn = trim(isset($skuItems['sn'][$key]) ? $skuItems['sn'][$key] : null);
                if (empty($skuSn)) {
                    if (!$insert) {
                        $cmd->delete('{{%item_sku_specification_value}}', ['sku_id' => $id])->execute();
                        $cmd->delete('{{%item_sku}}', ['id' => $id])->execute();
                    }
                    continue;
                }

                $columns = [
                    'item_id' => $this->id,
                    'sku_sn' => $skuSn,
                    'name' => isset($skuItems['name'][$key]) ? $skuItems['name'][$key] : $this->name,
                    'market_price' => isset($skuItems['market_price'][$key]) ? $skuItems['market_price'][$key] : $this->market_price,
                    'member_price' => isset($skuItems['member_price'][$key]) ? $skuItems['member_price'][$key] : $this->member_price,
                    'cost_price' => 0,
                    'default' => isset($skuItems['default'][$key]) && $skuItems['default'][$key] ? Constant::BOOLEAN_TRUE : Constant::BOOLEAN_FALSE,
                    'created_at' => $now,
                    'created_by' => $userId,
                    'updated_at' => $now,
                    'updated_by' => $userId,
                ];
                $skuId = $skuCmd->bindValue(':sn', $skuSn)->queryScalar();
                if ($skuId) {
                    // update
                    unset($columns['created_at'], $columns['created_by']);
                    $cmd->update('{{%item_sku}}', $columns, ['id' => $skuId])->execute();
                } else {
                    $cmd->insert('{{%item_sku}}', $columns)->execute();
                    $skuId = $db->getLastInsertID();
                }

                $existsSpecificationValueIds = $insert ? [] : $db->createCommand('SELECT [[specification_value_id]] FROM {{%item_sku_specification_value}} WHERE [[sku_id]] = :skuId', [':skuId' => $skuId])->queryColumn();
                $newSpecificationValueIds = explode(',', $skuItems['specification_value_ids'][$key]);
                if (empty($existsSpecificationValueIds) || array_diff($existsSpecificationValueIds, $newSpecificationValueIds)) {
                    if (!$insert) {
                        $cmd->delete('{{%item_sku_specification_value}}', ['sku_id' => $skuId])->execute();
                    }
                    $batchInsertRows = [];
                    foreach ($newSpecificationValueIds as $value) {
                        $value = abs((int) $value);
                        if ($value) {
                            $batchInsertRows[] = [
                                'sku_id' => $skuId,
                                'specification_value_id' => $value,
                            ];
                        }
                    }
                    if ($batchInsertRows) {
                        $cmd->batchInsert('{{%item_sku_specification_value}}', ['sku_id', 'specification_value_id'], $batchInsertRows)->execute();
                    }
                }
            }
        }
    }

}
