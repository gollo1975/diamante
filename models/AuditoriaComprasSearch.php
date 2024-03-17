<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AuditoriaCompras;

/**
 * AuditoriaComprasSearch represents the model behind the search form of `app\models\AuditoriaCompras`.
 */
class AuditoriaComprasSearch extends AuditoriaCompras
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_auditoria', 'id_orden_compra', 'id_tipo_orden', 'id_proveedor', 'numero_orden', 'cerrar_auditoria'], 'integer'],
            [['fecha_proceso_compra', 'user_name'], 'safe'],
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
        $query = AuditoriaCompras::find();

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
            'id_auditoria' => $this->id_auditoria,
            'id_orden_compra' => $this->id_orden_compra,
            'id_tipo_orden' => $this->id_tipo_orden,
            'id_proveedor' => $this->id_proveedor,
            'fecha_proceso_compra' => $this->fecha_proceso_compra,
            'numero_orden' => $this->numero_orden,
            'cerrar_auditoria' => $this->cerrar_auditoria,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
