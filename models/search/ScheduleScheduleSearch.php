<?php

namespace halumein\schedule\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use halumein\schedule\models\ScheduleSchedule;

/**
 * ScheduleScheduleSearch represents the model behind the search form of `app\vendor\halumein\schedule\models\ScheduleSchedule`.
 */
class ScheduleScheduleSearch extends ScheduleSchedule
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'owner_id', 'target_id'], 'integer'],
            [['target_model', 'name', 'date'], 'safe'],
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
        $query = ScheduleSchedule::find();

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
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'target_id' => $this->target_id,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'target_model', $this->target_model])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
