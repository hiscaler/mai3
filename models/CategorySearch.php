<?php

namespace app\models;

use app\models\Category;
use yadjet\helpers\ArrayHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/**
 * CategorySearch represents the model behind the search form about `app\models\Category`.
 */
class CategorySearch extends Category
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'parent_id', 'level', 'enabled'], 'integer'],
            [['alias', 'name'], 'safe'],
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
        $query = Category::find()->where(['tenant_id' => Yad::getTenantId()])->asArray();

        if ($this->load($params)) {
            $query->andFilterWhere([
                'id' => $this->id,
                'type' => $this->type,
                'parent_id' => $this->parent_id,
                'level' => $this->level,
                'enabled' => $this->enabled,
            ]);

            $query->andFilterWhere(['like', 'alias', $this->alias])
                ->andFilterWhere(['like', 'name', $this->name]);
        }

        $rawData = $query->all();
        if ($rawData) {
            $rawData = static::sortItems(['children' => ArrayHelper::toTree($rawData, 'id')]);
            unset($rawData[0]);
        }

        return new ArrayDataProvider([
            'allModels' => $rawData,
            'key' => 'id',
            'pagination' => false,
        ]);
    }

}
