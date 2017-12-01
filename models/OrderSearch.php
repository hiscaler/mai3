<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;

/**
 * OrderSearch represents the model behind the search form about `app\models\Order`.
 */
class OrderSearch extends Order
{

    /**
     * 下单人
     *
     * @var string
     */
    public $username;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'tenant_id', 'created_at', 'created_by'], 'integer'],
            [['sn', 'express_id', 'express_sn', 'username'], 'safe'],
            [['total_amount', 'discount_amount', 'express_fee', 'actual_amount'], 'number'],
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
        $query = Order::find()->with(['creater', 'address'])->where(['tenant_id' => Yad::getTenantId()]);

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
            'total_amount' => $this->total_amount,
            'discount_amount' => $this->discount_amount,
            'express_fee' => $this->express_fee,
            'actual_amount' => $this->actual_amount,
            'status' => $this->status,
            'tenant_id' => $this->tenant_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'sn', $this->sn])
            ->andFilterWhere(['like', 'express_id', $this->express_id])
            ->andFilterWhere(['like', 'express_sn', $this->express_sn]);

        // 根据姓名查询订单
        $username = trim($this->username);
        if ($username) {
            $query->andWhere(['IN', 'created_at', (new Query())->select(['id'])->from('{{%member}}')->where(['type' => User::TYPE_MEMBER])->andWhere(['LIKE', 'username', $username])]);
        }

        return $dataProvider;
    }

}
