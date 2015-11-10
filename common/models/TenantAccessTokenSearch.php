<?php

namespace common\models;

use common\models\TenantAccessToken;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TenantAccessTokenSearch represents the model behind the search form about `common\models\TenantAccessToken`.
 */
class TenantAccessTokenSearch extends TenantAccessToken
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'tenant_id'], 'integer'],
            [['name', 'access_token'], 'safe'],
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
        $query = TenantAccessToken::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'tenant_id' => SORT_ASC,
                    'created_at' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'access_token', $this->access_token]);

        return $dataProvider;
    }

}
