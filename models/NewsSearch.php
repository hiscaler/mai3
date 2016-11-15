<?php

namespace app\models;

use app\models\News;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * NewsSearch represents the model behind the search form about `common\models\News`.
 */
class NewsSearch extends News
{

    public $entityAttributeId;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'enabled', 'enabled_comment', 'published_at'], 'integer'],
            [['category_id', 'entityAttributeId', 'title', 'author', 'source'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = News::find()->with(['category', 'creater', 'updater', 'deleter', 'relatedLabels'])->asArray(true);
        $query->where('[[tenant_id]] = :tenantId AND [[category_id]] IN (SELECT [[category_id]] FROM {{%user_auth_category}} WHERE [[user_id]] = :userId)', [
            ':tenantId' => Yad::getTenantId(),
            ':userId' => Yii::$app->getUser()->getId()
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'enabled' => $this->enabled,
            'enabled_comment' => $this->enabled_comment,
            'published_at' => $this->published_at,
        ]);
        if ($this->category_id) {
            $nodeId = $this->category_id;
            if (is_array($nodeId)) {
                $allIdList = [];
                foreach ($nodeId as $id) {
                    $allIdList += Category::getChildrenIds($id);
                    $allIdList[] = $id;
                }
            } else {
                $allIdList = Category::getChildrenIds($this->category_id);
                $allIdList[] = $this->category_id;
            }

            $query->andWhere([
                'category_id' => $allIdList
            ]);
        }

        if ($this->entityAttributeId) {
            $query->andWhere(['in', 'id', (new Query())->select(['entity_id'])->from('{{%entity_attribute}}')->where(['attribute_id' => $this->entityAttributeId, 'entity_name' => self::className2Id(get_parent_class())])]);
        }

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'source', $this->source]);

        return $dataProvider;
    }

}
