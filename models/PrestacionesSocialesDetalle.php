<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prestaciones_sociales_detalle".
 *
 * @property int $id
 * @property int $id_prestacion
 * @property int $codigo_salario
 * @property string $fecha_inicio
 * @property string $fecha_final
 * @property int $nro_dias
 * @property int $dias_ausentes
 * @property int $salario_promedio_prima
 * @property int $auxilio_transporte
 * @property int $total_dias
 * @property int $valor_pagar
 *
 * @property PrestacionesSociales $prestacion
 * @property ConceptoSalarios $codigoSalario
 */
class PrestacionesSocialesDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prestaciones_sociales_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_prestacion', 'codigo_salario', 'nro_dias', 'dias_ausentes', 'salario_promedio_prima', 'auxilio_transporte', 'total_dias', 'valor_pagar'], 'integer'],
            [['fecha_inicio', 'fecha_final'], 'safe'],
            ['abreviatura', 'string'],
            [['id_prestacion'], 'exist', 'skipOnError' => true, 'targetClass' => PrestacionesSociales::className(), 'targetAttribute' => ['id_prestacion' => 'id_prestacion']],
            [['codigo_salario'], 'exist', 'skipOnError' => true, 'targetClass' => ConceptoSalarios::className(), 'targetAttribute' => ['codigo_salario' => 'codigo_salario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_prestacion' => 'Id Prestacion',
            'codigo_salario' => 'Codigo Salario',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_final' => 'Fecha Final',
            'nro_dias' => 'Nro Dias',
            'dias_ausentes' => 'Dias Ausentes',
            'salario_promedio_prima' => 'Salario Promedio Prima',
            'auxilio_transporte' => 'Auxilio Transporte',
            'total_dias' => 'Total Dias',
            'valor_pagar' => 'Valor Pagar',
            'abreviatura' => 'abreviatura',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrestacion()
    {
        return $this->hasOne(PrestacionesSociales::className(), ['id_prestacion' => 'id_prestacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoSalario()
    {
        return $this->hasOne(ConceptoSalarios::className(), ['codigo_salario' => 'codigo_salario']);
    }
}
