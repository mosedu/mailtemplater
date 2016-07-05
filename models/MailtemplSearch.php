<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mailtempl;

/**
 * MailtemplSearch represents the model behind the search form about `app\models\Mailtempl`.
 */
class MailtemplSearch extends Mailtempl
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mt_id'], 'integer'],
            [['mt_createtime', 'mt_name', 'mt_text'], 'safe'],
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
        $query = Mailtempl::find();

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
            'mt_id' => $this->mt_id,
            'mt_createtime' => $this->mt_createtime,
        ]);

        $query->andFilterWhere(['like', 'mt_name', $this->mt_name])
            ->andFilterWhere(['like', 'mt_text', $this->mt_text]);

        return $dataProvider;
    }
}
