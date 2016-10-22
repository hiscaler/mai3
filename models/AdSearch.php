<?php

namespace app\models;

use yii\data\ActiveDataProvider;

/**
 * AdSearch represents the model behind the search form about `app\models\Ad`.
 */
class AdSearch extends Ad
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['space_id', 'type', 'begin_datetime', 'end_datetime', 'enabled'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['picture'] = ['file_path'];
        $scenarios['flash'] = ['file_path'];

        return $scenarios;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param integer $spaceId
     *
     * @return ActiveDataProvider
     */
    public function search($params, $spaceId)
    {
        $query = Ad::find()->with(['space', 'creater', 'updater'])->asArray(true);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC
                ]
            ]
        ]);

        if (!$this->space_id) {
            $query->andFilterWhere([
                'space_id' => (int) $spaceId
            ]);
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'space_id' => $this->space_id,
            'type' => $this->type,
            'begin_datetime' => $this->begin_datetime,
            'end_datetime' => $this->end_datetime,
            'enabled' => $this->enabled,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

}
