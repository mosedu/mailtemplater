<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Listelement;
use app\models\Listelgr;

/**
 * ListelementSearch represents the model behind the search form about `app\models\Listelement`.
 */
class ListelementSearch extends Listelement
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['le_id', '_allgroups', ], 'integer'],
//            [['_allgroups'], 'in', 'range' => array_keys(Listgroup::getList()), 'allowArray' => true, ],
            [['le_createtime', 'le_email', 'le_fam', 'le_name', 'le_otch', 'le_org', ], 'safe'],
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
        $query = Listelement::find();

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

        if( !empty($this->_allgroups) ) {
            $query->leftJoin(Listelgr::tableName(), Listelgr::tableName() . '.elgr_le_id = le_id' );
            $query->andFilterWhere([Listelgr::tableName() . '.elgr_lg_id' => $this->_allgroups]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'le_id' => $this->le_id,
            'le_createtime' => $this->le_createtime,
        ]);

        $query->andFilterWhere(['like', 'le_email', $this->le_email])
            ->andFilterWhere(['like', 'le_fam', $this->le_fam])
            ->andFilterWhere(['like', 'le_name', $this->le_name])
            ->andFilterWhere(['like', 'le_otch', $this->le_otch])
            ->andFilterWhere(['like', 'le_org', $this->le_org]);

        return $dataProvider;
    }
}
