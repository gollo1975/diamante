<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "agrupar_concepto_salario".
 *
 * @property int $id_agrupado
 * @property string $concepto
 * @property int $tipo_movimiento
 *
 * @property ConceptoSalarios[] $conceptoSalarios
 * @property NominaElectronicaDetalle[] $nominaElectronicaDetalles
 */
class AgruparConceptoSalario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agrupar_concepto_salario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto'], 'required'],
            [['tipo_movimiento'], 'integer'],
            [['concepto'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_agrupado' => 'Id Agrupado',
            'concepto' => 'Concepto',
            'tipo_movimiento' => 'Tipo Movimiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConceptoSalarios()
    {
        return $this->hasMany(ConceptoSalarios::className(), ['id_agrupado' => 'id_agrupado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNominaElectronicaDetalles()
    {
        return $this->hasMany(NominaElectronicaDetalle::className(), ['id_agrupado' => 'id_agrupado']);
    }
}
