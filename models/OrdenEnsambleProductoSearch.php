<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrdenEnsambleProducto;

/**
 * OrdenEnsambleProductoSearch represents the model behind the search form of `app\models\OrdenEnsambleProducto`.
 */
class OrdenEnsambleProductoSearch extends OrdenEnsambleProducto
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ensamble', 'id_orden_produccion', 'numero_orden_ensamble', 'id_grupo', 'numero_lote', 'id_etapa', 'peso_neto'], 'integer'],
            [['fecha_proceso', 'fecha_hora_registro', 'user_name', 'observacion', 'responsable'], 'safe'],
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
        $query = OrdenEnsambleProducto::find();

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
            'id_ensamble' => $this->id_ensamble,
            'id_orden_produccion' => $this->id_orden_produccion,
            'numero_orden_ensamble' => $this->numero_orden_ensamble,
            'id_grupo' => $this->id_grupo,
            'numero_lote' => $this->numero_lote,
            'id_etapa' => $this->id_etapa,
            'fecha_proceso' => $this->fecha_proceso,
            'fecha_hora_registro' => $this->fecha_hora_registro,
            'peso_neto' => $this->peso_neto,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andFilterWhere(['like', 'responsable', $this->responsable]);

        return $dataProvider;
    }
}
