<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ConceptoSalarios;

/**
 * ConceptoSalariosSearch represents the model behind the search form of `app\models\ConceptoSalarios`.
 */
class ConceptoSalariosSearch extends ConceptoSalarios
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_salario', 'compone_salario', 'inicio_nomina', 'aplica_porcentaje', 'prestacional', 'ingreso_base_prestacional', 'ingreso_base_cotizacion', 'debito_credito', 'adicion', 'auxilio_transporte', 'concepto_incapacidad', 'concepto_pension', 'concepto_salud', 'concepto_vacacion', 'provisiona_vacacion', 'provisiona_indemnizacion', 'tipo_adicion', 'recargo_nocturno', 'hora_extra', 'concepto_comision', 'concepto_licencia', 'fsp', 'concepto_prima', 'concepto_cesantias', 'intereses'], 'integer'],
            [['nombre_concepto', 'fecha_creacion'], 'safe'],
            [['porcentaje', 'porcentaje_tiempo_extra'], 'number'],
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
        $query = ConceptoSalarios::find();

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
            'codigo_salario' => $this->codigo_salario,
            'compone_salario' => $this->compone_salario,
            'inicio_nomina' => $this->inicio_nomina,
            'aplica_porcentaje' => $this->aplica_porcentaje,
            'porcentaje' => $this->porcentaje,
            'porcentaje_tiempo_extra' => $this->porcentaje_tiempo_extra,
            'prestacional' => $this->prestacional,
            'ingreso_base_prestacional' => $this->ingreso_base_prestacional,
            'ingreso_base_cotizacion' => $this->ingreso_base_cotizacion,
            'debito_credito' => $this->debito_credito,
            'adicion' => $this->adicion,
            'auxilio_transporte' => $this->auxilio_transporte,
            'concepto_incapacidad' => $this->concepto_incapacidad,
            'concepto_pension' => $this->concepto_pension,
            'concepto_salud' => $this->concepto_salud,
            'concepto_vacacion' => $this->concepto_vacacion,
            'provisiona_vacacion' => $this->provisiona_vacacion,
            'provisiona_indemnizacion' => $this->provisiona_indemnizacion,
            'tipo_adicion' => $this->tipo_adicion,
            'recargo_nocturno' => $this->recargo_nocturno,
            'hora_extra' => $this->hora_extra,
            'concepto_comision' => $this->concepto_comision,
            'concepto_licencia' => $this->concepto_licencia,
            'fsp' => $this->fsp,
            'concepto_prima' => $this->concepto_prima,
            'concepto_cesantias' => $this->concepto_cesantias,
            'intereses' => $this->intereses,
            'fecha_creacion' => $this->fecha_creacion,
        ]);

        $query->andFilterWhere(['like', 'nombre_concepto', $this->nombre_concepto]);

        return $dataProvider;
    }
}
