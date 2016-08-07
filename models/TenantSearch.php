<?php

namespace app\models;

use app\models\Tenant;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TenantSearch represents the model behind the search form about `app\models\Tenant`.
 */
class TenantSearch extends Tenant
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enabled'], 'integer'],
            [['key', 'name', 'domain_name'], 'safe'],
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
        $query = Tenant::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'enabled' => $this->enabled,
        ]);

        $query->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'domain_name', $this->domain_name]);

        return $dataProvider;
    }

}
