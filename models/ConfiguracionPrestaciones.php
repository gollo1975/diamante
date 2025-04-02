<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion_prestaciones".
 *
 * @property int $id_prestacion
 * @property string $concepto
 * @property double $porcentaje_pago
 * @property int $aplicar_ausentismo
 * @property int $codigo_salario
 * @property string $fecha_creacion
 *
 * @property ConceptoSalarios $codigoSalario
 */
class ConfiguracionPrestaciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion_prestaciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto', 'codigo_salario'], 'required'],
            [['porcentaje_pago'], 'number'],
            [['aplicar_ausentismo', 'codigo_salario','aplica_recargo_vacacion'], 'integer'],
            [['fecha_creacion'], 'safe'],
            [['concepto'], 'string', 'max' => 120],
            [['codigo_salario'], 'exist', 'skipOnError' => true, 'targetClass' => ConceptoSalarios::className(), 'targetAttribute' => ['codigo_salario' => 'codigo_salario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_prestacion' => 'Id Prestacion',
            'concepto' => 'Concepto',
            'porcentaje_pago' => 'Porcentaje Pago',
            'aplicar_ausentismo' => 'Aplicar Ausentismo',
            'codigo_salario' => 'Codigo Salario',
            'fecha_creacion' => 'Fecha Creacion',
            'aplica_recargo_vacacion' => 'Aplica recargo vacacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoSalario()
    {
        return $this->hasOne(ConceptoSalarios::className(), ['codigo_salario' => 'codigo_salario']);
    }
}
