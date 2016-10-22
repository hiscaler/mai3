<?php

namespace app\models;

use app\models\ItemComment;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * ItemCommentSearch represents the model behind the search form about `app\models\ItemComment`.
 */
class ItemCommentSearch extends ItemComment
{

    /**
     * 单品品名
     * @var string
     */
    public $item_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level', 'item_id', 'return_user_id', 'return_datetime', 'enabled', 'ip_address', 'created_at', 'created_by'], 'integer'],
            [['username', 'tel', 'email', 'item_name'], 'safe'],
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
        $query = ItemComment::find()->with(['item', 'creater'])->where(['tenant_id' => Yad::getTenantId()]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'level' => $this->level,
            'item_id' => $this->item_id,
            'return_user_id' => $this->return_user_id,
            'return_datetime' => $this->return_datetime,
            'enabled' => $this->enabled,
            'ip_address' => $this->ip_address,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'email', $this->email]);

        if ($this->item_name) {
            $query->andWhere(['in', 'item_id', (new Query())->select('id')->from('{{%item}}')->where(['like', 'name', $this->item_name])]);
        }

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'item_name' => Yii::t('item', 'Name'),
        ]);
    }

}
