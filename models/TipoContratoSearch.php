<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TipoContrato;

/**
 * TipoContratoSearch represents the model behind the search form of `app\models\TipoContrato`.
 */
class TipoContratoSearch extends TipoContrato
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tipo_contrato', 'prorroga', 'numero_prorrogas', 'id_configuracion_prefijo', 'estado'], 'integer'],
            [['contrato', 'prefijo', 'abreviatura', 'user_name'], 'safe'],
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
        $query = TipoContrato::find();

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
            'id_tipo_contrato' => $this->id_tipo_contrato,
            'prorroga' => $this->prorroga,
            'numero_prorrogas' => $this->numero_prorrogas,
            'id_configuracion_prefijo' => $this->id_configuracion_prefijo,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'contrato', $this->contrato])
            ->andFilterWhere(['like', 'prefijo', $this->prefijo])
            ->andFilterWhere(['like', 'abreviatura', $this->abreviatura])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
