<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Item;

/**
 * ItemSearch represents the model behind the search form about `common\models\Item`.
 */
class ItemSearch extends Item
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'market_price', 'member_price', 'cost_price', 'clicks_count', 'favorites_count', 'sales_count', 'stocks_count', 'default', 'enabled', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['sn', 'name', 'picture_path'], 'safe'],
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
        $query = Item::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
            'market_price' => $this->market_price,
            'member_price' => $this->member_price,
            'cost_price' => $this->cost_price,
            'clicks_count' => $this->clicks_count,
            'favorites_count' => $this->favorites_count,
            'sales_count' => $this->sales_count,
            'stocks_count' => $this->stocks_count,
            'default' => $this->default,
            'enabled' => $this->enabled,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'sn', $this->sn])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'picture_path', $this->picture_path]);

        return $dataProvider;
    }
}
