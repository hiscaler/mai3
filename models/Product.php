<?php

namespace app\models;

use app\modules\admin\components\ApplicationHelper;
use PDO;
use yadjet\behaviors\FileUploadBehavior;
use yadjet\helpers\StringHelper;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\validators\UrlValidator;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $type_id
 * @property integer $brand_id
 * @property string $sn
 * @property string $name
 * @property string $market_price
 * @property string $shop_price
 * @property string $member_price
 * @property string $picture_path
 * @property string $keywords
 * @property string $description
 * @property integer $online
 * @property integer $on_off_datetime
 * @property integer $view_require_credits
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
class Product extends BaseActiveRecord
{

    private $_category_id;
    private $_brand_id;
    private $_content = null;
    private $_fileUploadConfig;

    public function init()
    {
        $this->_fileUploadConfig = FileUploadConfig::getConfig(static::className2Id(), 'file_path');
        parent::init();
    }

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
    public $propertyItems;

    /**
     * 特权用户
     * @var array
     */
    public $privilegeUsers;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'brand_id', 'type_id', 'sn', 'name', 'market_price', 'shop_price', 'member_price', 'keywords', 'content', 'online'], 'required'],
            [['name', 'keywords', 'description'], 'trim'],
            ['sn', 'match', 'pattern' => '/^[a-zA-Z0-9]+[a-zA-Z0-9_][a-zA-Z0-9]$/'],
            [['status'], 'boolean'],
            [['category_id', 'type_id', 'brand_id', 'ordering', 'clicks_count', 'sales_count', 'on_off_datetime', 'view_require_credits', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['online'], 'boolean'],
            [['online'], 'default', 'value' => Constant::BOOLEAN_TRUE],
            [['brand_id'], 'default', 'value' => 0],
            [['market_price', 'shop_price', 'member_price'], 'number'],
            [['market_price', 'shop_price', 'member_price', 'view_require_credits'], 'default', 'value' => 0],
            [['description', 'content'], 'string'],
            [['sn'], 'string', 'max' => 16],
            ['sn', 'unique', 'targetAttribute' => ['sn', 'tenant_id']],
            [['name'], 'string', 'max' => 50],
            [['keywords'], 'string', 'max' => 100],
            [['imageFiles', 'skuItems', 'propertyItems', 'privilegeUsers'], 'safe'],
            ['picture_path', 'file',
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
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => FileUploadBehavior::className(),
                'attribute' => 'picture_path'
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
            'category_id' => Yii::t('product', 'Category'),
            'type_id' => Yii::t('product', 'Type'),
            'brand_id' => Yii::t('product', 'Brand'),
            'sn' => Yii::t('product', 'Sn'),
            'name' => Yii::t('product', 'Name'),
            'market_price' => Yii::t('product', 'Market Price'),
            'shop_price' => Yii::t('product', 'Shop Price'),
            'member_price' => Yii::t('product', 'Member Price'),
            'picture_path' => Yii::t('product', 'Picture'),
            'keywords' => Yii::t('app', 'Page Keywords'),
            'description' => Yii::t('app', 'Page Description'),
            'online' => Yii::t('product', 'Online'),
            'on_off_datetime' => Yii::t('product', 'On Off Datetime'),
            'view_require_credits' => Yii::t('product', 'View Require Credits'),
            'ordering' => Yii::t('app', 'Ordering'),
            'clicks_count' => Yii::t('app', 'Clicks Count'),
            'sales_count' => Yii::t('product', 'Sales Count'),
            'status' => Yii::t('app', 'Status'),
            'tenant_id' => Yii::t('app', 'Tenant ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'content' => Yii::t('product', 'Content'),
            'privilegeUsers' => Yii::t('product', 'Privilege Users'),
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
     * 所属类型
     * @return ActiveRecord
     */
    public function getType()
    {
        return $this->hasOne(Type::className(), ['id' => 'type_id']);
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
        return $this->hasMany(ProductImage::className(), ['product_id' => 'id']);
    }

    /**
     * 商品单品
     * @return array
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['product_id' => 'id']);
    }

    // 事件
    private $_online;

    public function afterFind()
    {
        parent::afterFind();
        if (!$this->isNewRecord) {
            $this->_online = $this->online;
            $this->_category_id = $this->category_id;
            $this->_brand_id = $this->brand_id;
            $db = Yii::$app->getDb();
            $this->content = $this->_content = $db->createCommand('SELECT [[content]] FROM {{%product_content}} WHERE [[product_id]] = :productId')->bindValue(':productId', $this->id, PDO::PARAM_INT)->queryScalar();
            $this->privilegeUsers = $db->createCommand('SELECT [[user_id]] FROM {{%product_privilege_user}} WHERE [[product_id]] = :productId')->bindValue(':productId', $this->id, PDO::PARAM_INT)->queryColumn();
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                if ($this->online) {
                    $this->on_off_datetime = time();
                }
            } else {
                if ($this->online && !$this->_online) {
                    $this->on_off_datetime = time();
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $userId = Yii::$app->getUser()->getId();
        $now = time();
        $db = Yii::$app->getDb();
        if ($insert) {
            $db->createCommand()->insert('{{%product_content}}', [
                'product_id' => $this->id,
                'content' => $this->content,
                'created_at' => $now,
                'created_by' => $userId,
                'updated_at' => $now,
                'updated_by' => $userId,
            ])->execute();
        } else {
            if ($this->content != $this->_content) {
                $db->createCommand()->update('{{%product_content}}', [
                    'content' => $this->content,
                    'updated_at' => $now,
                    'updated_by' => $userId
                    ], ['product_id' => $this->id])->execute();
            }

            // 如果 category_id 和 brand_id 发生变化，则需要同步更新 item 中的数据
            $updateItemsColumns = [];
            if ($this->_category_id != $this->category_id) {
                $updateItemsColumns['category_id'] = $this->category_id;
            }
            if ($this->_brand_id != $this->brand_id) {
                $updateItemsColumns['brand_id'] = $this->brand_id;
            }
            if ($updateItemsColumns) {
                $db->createCommand()->update('{{%item}}', $updateItemsColumns, ['prodcut_id' => $this->id])->execute();
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
                            'product_id' => $this->id,
                            'url' => $imgUrl,
                            'path' => $imgPath,
                            'description' => $imageDescriptions[$key] ? : ($file ? $file->getBaseName() : null),
                            'created_at' => $now,
                            'created_by' => $userId,
                            'updated_at' => $now,
                            'updated_by' => $userId
                        ];
                        $batchInsertRows[] = array_values($columns);
                    }
                }
            }

            if ($batchInsertRows) {
                $db->createCommand()->batchInsert('{{%product_image}}', array_keys($columns), $batchInsertRows)->execute();
            }
        }

        // SKU 处理
        $skuItems = $this->skuItems;
        $tenantId = Yad::getTenantId();
        $cmd = $db->createCommand();
        $skuCmd = $db->createCommand('SELECT [[id]] FROM {{%item}} WHERE [[sn]] = :sn AND product_id = ' . $this->id);
        if (isset($skuItems['id']) && $skuItems['id']) {
            foreach ($skuItems['id'] as $key => $id) {
                $id = (int) $id;
                $sn = trim(isset($skuItems['sn'][$key]) ? $skuItems['sn'][$key] : null);
                if (empty($sn)) {
                    if (!$insert && $id) {
                        $cmd->delete('{{%item_specification_value}}', ['item_id' => $id])->execute();
                        $cmd->delete('{{%item}}', ['id' => $id])->execute();
                    }
                    continue;
                }

                $columns = [
                    'product_id' => $this->id,
                    'category_id' => $this->category_id,
                    'brand_id' => $this->brand_id,
                    'sn' => $sn,
                    'name' => isset($skuItems['name'][$key]) ? $skuItems['name'][$key] : $this->name,
                    'market_price' => isset($skuItems['market_price'][$key]) ? $skuItems['market_price'][$key] : $this->market_price,
                    'shop_price' => isset($skuItems['shop_price'][$key]) ? $skuItems['shop_price'][$key] : $this->shop_price,
                    'member_price' => isset($skuItems['member_price'][$key]) ? $skuItems['member_price'][$key] : $this->member_price,
                    'cost_price' => 0,
                    'picture_path' => $this->picture_path,
                    'default' => isset($skuItems['default']) && in_array($id, $skuItems['default']) ? Constant::BOOLEAN_TRUE : Constant::BOOLEAN_FALSE,
                    'enabled' => isset($skuItems['online']) && in_array($key, $skuItems['online']) ? Constant::BOOLEAN_TRUE : Constant::BOOLEAN_FALSE,
                    'online' => isset($skuItems['online']) && in_array($key, $skuItems['online']) ? Constant::BOOLEAN_TRUE : Constant::BOOLEAN_FALSE,
                    'on_off_datetime' => in_array($key, $skuItems['online']) ? $now : null,
                    'view_require_credits' => $this->view_require_credits,
                    'tenant_id' => $tenantId,
                    'created_at' => $now,
                    'created_by' => $userId,
                    'updated_at' => $now,
                    'updated_by' => $userId,
                ];
                $itemId = $skuCmd->bindValue(':sn', $sn)->queryScalar();
                if ($itemId) {
                    // update
                    unset($columns['created_at'], $columns['created_by']);
                    $cmd->update('{{%item}}', $columns, ['id' => $itemId])->execute();
                } else {
                    $cmd->insert('{{%item}}', $columns)->execute();
                    $itemId = $db->getLastInsertID();
                }

                $existsSpecificationValueIds = $insert ? [] : $db->createCommand('SELECT [[specification_value_id]] FROM {{%item_specification_value}} WHERE [[item_id]] = :itemId', [':itemId' => $itemId])->queryColumn();
                $newSpecificationValueIds = explode(',', $skuItems['specification_value_ids'][$key]);
                if (empty($existsSpecificationValueIds) || array_diff($existsSpecificationValueIds, $newSpecificationValueIds)) {
                    if (!$insert) {
                        $cmd->delete('{{%item_specification_value}}', ['item_id' => $itemId])->execute();
                    }
                    $batchInsertRows = [];
                    foreach ($newSpecificationValueIds as $value) {
                        $value = abs((int) $value);
                        if ($value) {
                            $batchInsertRows[] = [
                                'item_id' => $itemId,
                                'specification_value_id' => $value,
                            ];
                        }
                    }
                    if ($batchInsertRows) {
                        $cmd->batchInsert('{{%item_specification_value}}', ['item_id', 'specification_value_id'], $batchInsertRows)->execute();
                    }
                }
            }
        }

        // 商品属性处理
        $propertyItems = $this->propertyItems;
        if (isset($propertyItems['id'], $propertyItems['value']) && $propertyItems['value'] && $propertyItems['id'] && count($propertyItems['id']) == count($propertyItems['value'])) {
            // @todo 需要优化
            if (!$insert) {
                $db->createCommand()->delete('{{%product_property}}', ['product_id' => $this->id])->execute();
            }
            $propertyItems = array_combine($propertyItems['id'], $propertyItems['value']);
            $batchInsertRows = [];
            foreach ($propertyItems as $id => $value) {
                $columns = [
                    'product_id' => $this->id,
                    'property_id' => $id,
                    'value' => $value,
                ];
                $batchInsertRows[] = array_values($columns);
            }
            if ($batchInsertRows) {
                $db->createCommand()->batchInsert('{{%product_property}}', array_keys($columns), $batchInsertRows)->execute();
            }
        }

        // 特权用户处理
        $privilegeUsers = $this->privilegeUsers;
        $db->createCommand()->delete('{{%product_privilege_user}}', ['product_id' => $this->id])->execute();
        if (!empty($privilegeUsers) && is_array($privilegeUsers)) {
            $batchInsertRows = [];
            foreach ($privilegeUsers as $userId) {
                $batchInsertRows [] = [
                    'product_id' => $this->id,
                    'user_id' => $userId,
                ];
            }
            if ($batchInsertRows) {
                $db->createCommand()->batchInsert('{{%product_privilege_user}}', ['product_id', 'user_id'], $batchInsertRows)->execute();
            }
        }
    }

}
