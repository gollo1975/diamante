<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EntregaSolicitudKits;

/**
 * EntregaSolicitudKitsSearch represents the model behind the search form of `app\models\EntregaSolicitudKits`.
 */
class EntregaSolicitudKitsSearch extends EntregaSolicitudKits
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_entrega_kits', 'id_solicitud', 'id_presentacion', 'id_solicitud_armado', 'total_unidades_entregadas', 'proceso_cerrado', 'autorizado', 'numero_entrega', 'cantidad_despachada'], 'integer'],
            [['fecha_solicitud', 'fecha_hora_proceso', 'observacion', 'user_name'], 'safe'],
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
        $query = EntregaSolicitudKits::find();

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
            'id_entrega_kits' => $this->id_entrega_kits,
            'id_solicitud' => $this->id_solicitud,
            'id_presentacion' => $this->id_presentacion,
            'id_solicitud_armado' => $this->id_solicitud_armado,
            'total_unidades_entregadas' => $this->total_unidades_entregadas,
            'fecha_solicitud' => $this->fecha_solicitud,
            'fecha_hora_proceso' => $this->fecha_hora_proceso,
            'proceso_cerrado' => $this->proceso_cerrado,
            'autorizado' => $this->autorizado,
            'numero_entrega' => $this->numero_entrega,
            'cantidad_despachada' => $this->cantidad_despachada,
        ]);

        $query->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
