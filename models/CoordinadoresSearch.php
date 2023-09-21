<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Coordinadores;

/**
 * CoordinadoresSearch represents the model behind the search form of `app\models\Coordinadores`.
 */
class CoordinadoresSearch extends Coordinadores
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_coordinador', 'id_tipo_documento'], 'integer'],
            [['documento', 'nombres', 'apellidos', 'nombre_completo', 'celular', 'email', 'user_name'], 'string'],
            ['fecha_registro', 'safe'],
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
        $query = Coordinadores::find();

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
            'id_coordinador' => $this->id_coordinador,
            'id_tipo_documento' => $this->id_tipo_documento,
             'nombre_completo' => $this->nombre_completo,
            'fecha_registro' => $this->fecha_registro,
            
        ]);

        $query->andFilterWhere(['like', 'documento', $this->documento])
            ->andFilterWhere(['like', 'nombres', $this->nombres])
            ->andFilterWhere(['like', 'apellidos', $this->apellidos])
            ->andFilterWhere(['like', 'nombre_completo', $this->nombre_completo])
            ->andFilterWhere(['like', 'celular', $this->celular])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
