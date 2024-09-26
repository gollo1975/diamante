<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FormatoContenido;

/**
 * FormatoContenidoSearch represents the model behind the search form of `app\models\FormatoContenido`.
 */
class FormatoContenidoSearch extends FormatoContenido
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_formato_contenido', 'id_configuracion_prefijo'], 'integer'],
            [['nombre_formato', 'contenido', 'fecha_creacion', 'user_name'], 'safe'],
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
        $query = FormatoContenido::find();

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
            'id_formato_contenido' => $this->id_formato_contenido,
            'id_configuracion_prefijo' => $this->id_configuracion_prefijo,
            'fecha_creacion' => $this->fecha_creacion,
        ]);

        $query->andFilterWhere(['like', 'nombre_formato', $this->nombre_formato])
            ->andFilterWhere(['like', 'contenido', $this->contenido])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
