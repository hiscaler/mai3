<?php

namespace app\models;

use app\models\User;
use app\models\Yad;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * TenantUserSearch represents the model behind the search form about `app\models\TenantUser`.
 */
class TenantUserSearch extends TenantUser
{

    public $username;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role', 'rule_id', 'rule_id', 'user_group_id'], 'integer'],
            ['enabled', 'boolean'],
            [['username'], 'safe'],
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
        $tenantId = Yad::getTenantId();
        $query = User::find()
            ->select(['t.id', 'tup.name AS user_group_name', 't.username', 't.nickname', 't.email', 'tu.role', 'tu.enabled'])
            ->from(['{{%user}} t'])
            ->leftJoin('{{%tenant_user}} tu', '[[t.id]] = tu.user_id AND [[tu.tenant_id]] = :tenantId', [':tenantId' => $tenantId])
            ->leftJoin('{{%tenant_user_group}} tup', '[[tu.user_group_id]] = tup.id')
            ->asArray(true);
        $query->andWhere([
            't.id' => (new Query)
                ->select('user_id')
                ->from('{{%tenant_user}}')
                ->where('tenant_id = :tenantId', [':tenantId' => $tenantId])
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'username' => SORT_ASC,
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tu.role' => $this->role,
            'tu.rule_id' => $this->rule_id,
            'tu.user_group_id' => $this->user_group_id,
            'tu.enabled' => $this->enabled,
        ]);

        $query->andFilterWhere(['like', 't.username', $this->username]);

        return $dataProvider;
    }

}
