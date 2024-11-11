<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DocumentoElectronico;

/**
 * DocumentoElectronicoSearch represents the model behind the search form of `app\models\DocumentoElectronico`.
 */
class DocumentoElectronicoSearch extends DocumentoElectronico
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_documento'], 'integer'],
            [['concepto', 'sigla', 'fecha_creacion'], 'safe'],
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
        $query = DocumentoElectronico::find();

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
            'id_documento' => $this->id_documento,
            'fecha_creacion' => $this->fecha_creacion,
        ]);

        $query->andFilterWhere(['like', 'concepto', $this->concepto])
            ->andFilterWhere(['like', 'sigla', $this->sigla]);

        return $dataProvider;
    }
}
