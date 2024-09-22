<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EntidadPension;

/**
 * EntidadPensionSearch represents the model behind the search form of `app\models\EntidadPension`.
 */
class EntidadPensionSearch extends EntidadPension
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_entidad_pension', 'estado'], 'integer'],
            [['entidad'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = EntidadPension::find();

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
            'id_entidad_pension' => $this->id_entidad_pension,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'entidad', $this->entidad]);

        return $dataProvider;
    }
}
