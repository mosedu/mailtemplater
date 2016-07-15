<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Letter;

/**
 * LetterSearch represents the model behind the search form about `app\models\Letter`.
 */
class LetterSearch extends Letter
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['let_id', 'let_mt_id', 'let_us_id', 'let_send_id', 'let_state', 'let_send_num'], 'integer'],
            [['let_createtime', 'let_sendtime', 'let_text', 'let_subject',], 'safe'],
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
        $query = Letter::find();

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
            'let_id' => $this->let_id,
            'let_createtime' => $this->let_createtime,
            'let_sendtime' => $this->let_sendtime,
            'let_mt_id' => $this->let_mt_id,
            'let_us_id' => $this->let_us_id,
            'let_send_id' => $this->let_send_id,
            'let_state' => $this->let_state,
            'let_send_num' => $this->let_send_num,
        ]);

        $query->andFilterWhere(['like', 'let_text', $this->let_text]);
        $query->andFilterWhere(['like', 'let_subject', $this->let_subject]);

        return $dataProvider;
    }
}
