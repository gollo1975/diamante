<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MateriaPrimas;

/**
 * MateriaPrimasSearch represents the model behind the search form of `app\models\MateriaPrimas`.
 */
class MateriaPrimasSearch extends MateriaPrimas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_materia_prima', 'id_medida', 'aplica_iva', 'valor_iva', 'total_cantidad', 'total_materia_prima', 'aplica_inventario', 'entrada_salida'], 'integer'],
            [['codigo_materia_prima', 'descripcion', 'fecha_entrada', 'fecha_vencimiento', 'fecha_registro', 'usuario_creador', 'usuario_editado', 'codigo_ean'], 'safe'],
            [['valor_unidad', 'porcentaje_iva'], 'number'],
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
        $query = MateriaPrimas::find();

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
            'id_materia_prima' => $this->id_materia_prima,
            'id_medida' => $this->id_medida,
            'valor_unidad' => $this->valor_unidad,
            'aplica_iva' => $this->aplica_iva,
            'porcentaje_iva' => $this->porcentaje_iva,
            'valor_iva' => $this->valor_iva,
            'total_cantidad' => $this->total_cantidad,
            'total_materia_prima' => $this->total_materia_prima,
            'fecha_entrada' => $this->fecha_entrada,
            'fecha_vencimiento' => $this->fecha_vencimiento,
            'fecha_registro' => $this->fecha_registro,
            'aplica_inventario' => $this->aplica_inventario,
            'entrada_salida' => $this->entrada_salida,
        ]);

        $query->andFilterWhere(['like', 'codigo_materia_prima', $this->codigo_materia_prima])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'usuario_creador', $this->usuario_creador])
            ->andFilterWhere(['like', 'usuario_editado', $this->usuario_editado])
            ->andFilterWhere(['like', 'codigo_ean', $this->codigo_ean]);

        return $dataProvider;
    }
}
