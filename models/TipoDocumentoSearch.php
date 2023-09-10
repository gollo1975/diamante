<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TipoDocumento;

/**
 * TipoDocumentoSearch represents the model behind the search form of `app\models\TipoDocumento`.
 */
class TipoDocumentoSearch extends TipoDocumento
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tipo_documento', 'proceso_nomina', 'proceso_cliente', 'proceso_proveedor'], 'integer'],
            [['tipo_documento', 'documento', 'codigo_interfaz', 'fecha_registro'], 'safe'],
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
        $query = TipoDocumento::find();

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
            'id_tipo_documento' => $this->id_tipo_documento,
            'proceso_nomina' => $this->proceso_nomina,
            'proceso_cliente' => $this->proceso_cliente,
            'proceso_proveedor' => $this->proceso_proveedor,
            'fecha_registro' => $this->fecha_registro,
        ]);

        $query->andFilterWhere(['like', 'tipo_documento', $this->tipo_documento])
            ->andFilterWhere(['like', 'documento', $this->documento])
            ->andFilterWhere(['like', 'codigo_interfaz', $this->codigo_interfaz]);

        return $dataProvider;
    }
}
