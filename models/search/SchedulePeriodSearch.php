<?php

namespace halumein\schedule\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use halumein\schedule\models\SchedulePeriod;

/**
 * SchedulePeriodSearch represents the model behind the search form of `halumein\schedule\models\SchedulePeriod`.
 */
class SchedulePeriodSearch extends SchedulePeriod
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'owner_id', 'target_id'], 'integer'],
            [['target_model', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'], 'safe'],
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
        $query = SchedulePeriod::find();

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
        ]);

        $query->andFilterWhere(['like', 'target_model', $this->target_model])
            ->andFilterWhere(['like', 'monday', $this->monday])
            ->andFilterWhere(['like', 'tuesday', $this->tuesday])
            ->andFilterWhere(['like', 'wednesday', $this->wednesday])
            ->andFilterWhere(['like', 'thursday', $this->thursday])
            ->andFilterWhere(['like', 'friday', $this->friday])
            ->andFilterWhere(['like', 'saturday', $this->saturday])
            ->andFilterWhere(['like', 'sunday', $this->sunday]);

        return $dataProvider;
    }
}
