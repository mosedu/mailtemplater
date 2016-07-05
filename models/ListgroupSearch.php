<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Listgroup;

/**
 * ListgroupSearch represents the model behind the search form about `app\models\Listgroup`.
 */
class ListgroupSearch extends Listgroup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lg_id'], 'integer'],
            [['lg_createtime', 'lg_name'], 'safe'],
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
        $query = Listgroup::find();

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
            'lg_id' => $this->lg_id,
            'lg_createtime' => $this->lg_createtime,
        ]);

        $query->andFilterWhere(['like', 'lg_name', $this->lg_name]);

        return $dataProvider;
    }
}
