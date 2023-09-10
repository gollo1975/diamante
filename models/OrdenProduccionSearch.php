<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrdenProduccion;

/**
 * OrdenProduccionSearch represents the model behind the search form of `app\models\OrdenProduccion`.
 */
class OrdenProduccionSearch extends OrdenProduccion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_orden_produccion', 'numero_orden', 'id_almacen', 'id_grupo', 'numero_lote', 'subtotal', 'iva', 'total_orden', 'autorizado', 'cerrar_orden'], 'integer'],
            [['fecha_proceso', 'fecha_entrega', 'fecha_registro', 'user_name'], 'safe'],
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
        $query = OrdenProduccion::find();

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
            'id_orden_produccion' => $this->id_orden_produccion,
            'numero_orden' => $this->numero_orden,
            'id_almacen' => $this->id_almacen,
            'id_grupo' => $this->id_grupo,
            'numero_lote' => $this->numero_lote,
            'fecha_proceso' => $this->fecha_proceso,
            'fecha_entrega' => $this->fecha_entrega,
            'fecha_registro' => $this->fecha_registro,
            'subtotal' => $this->subtotal,
            'iva' => $this->iva,
            'total_orden' => $this->total_orden,
            'autorizado' => $this->autorizado,
            'cerrar_orden' => $this->cerrar_orden,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
