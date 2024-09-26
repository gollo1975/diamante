<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "concepto_salarios".
 *
 * @property int $codigo_salario
 * @property string $nombre_concepto
 * @property int $compone_salario
 * @property int $inicio_nomina
 * @property int $aplica_porcentaje
 * @property double $porcentaje
 * @property double $porcentaje_tiempo_extra
 * @property int $prestacional
 * @property int $ingreso_base_prestacional
 * @property int $ingreso_base_cotizacion
 * @property int $debito_credito
 * @property int $adicion
 * @property int $auxilio_transporte
 * @property int $concepto_incapacidad
 * @property int $concepto_pension
 * @property int $concepto_salud
 * @property int $concepto_vacacion
 * @property int $provisiona_vacacion
 * @property int $provisiona_indemnizacion
 * @property int $tipo_adicion
 * @property int $recargo_nocturno
 * @property int $hora_extra
 * @property int $concepto_comision
 * @property int $concepto_licencia
 * @property int $fsp
 * @property int $concepto_prima
 * @property int $concepto_cesantias
 * @property int $intereses
 * @property string $fecha_creacion
 */
class ConceptoSalarios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'concepto_salarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_salario', 'nombre_concepto'], 'required'],
            [['codigo_salario', 'compone_salario', 'inicio_nomina', 'aplica_porcentaje', 'prestacional', 'ingreso_base_prestacional', 'ingreso_base_cotizacion', 'debito_credito', 'adicion', 'auxilio_transporte', 'concepto_incapacidad', 'concepto_pension', 'concepto_salud', 'concepto_vacacion', 'provisiona_vacacion', 'provisiona_indemnizacion', 'tipo_adicion', 'recargo_nocturno', 'hora_extra', 'concepto_comision', 'concepto_licencia', 'fsp', 'concepto_prima', 'concepto_cesantias', 'intereses'], 'integer'],
            [['porcentaje', 'porcentaje_tiempo_extra'], 'number'],
            [['fecha_creacion'], 'safe'],
            [['nombre_concepto'], 'string', 'max' => 150],
            [['codigo_salario'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigo_salario' => 'Codigo Salario',
            'nombre_concepto' => 'Nombre Concepto',
            'compone_salario' => 'Compone Salario',
            'inicio_nomina' => 'Inicio Nomina',
            'aplica_porcentaje' => 'Aplica Porcentaje',
            'porcentaje' => 'Porcentaje',
            'porcentaje_tiempo_extra' => 'Porcentaje Tiempo Extra',
            'prestacional' => 'Prestacional',
            'ingreso_base_prestacional' => 'Ingreso Base Prestacional',
            'ingreso_base_cotizacion' => 'Ingreso Base Cotizacion',
            'debito_credito' => 'Debito Credito',
            'adicion' => 'Adicion',
            'auxilio_transporte' => 'Auxilio Transporte',
            'concepto_incapacidad' => 'Concepto Incapacidad',
            'concepto_pension' => 'Concepto Pension',
            'concepto_salud' => 'Concepto Salud',
            'concepto_vacacion' => 'Concepto Vacacion',
            'provisiona_vacacion' => 'Provisiona Vacacion',
            'provisiona_indemnizacion' => 'Provisiona Indemnizacion',
            'tipo_adicion' => 'Tipo Adicion',
            'recargo_nocturno' => 'Recargo Nocturno',
            'hora_extra' => 'Hora Extra',
            'concepto_comision' => 'Concepto Comision',
            'concepto_licencia' => 'Concepto Licencia',
            'fsp' => 'Fsp',
            'concepto_prima' => 'Concepto Prima',
            'concepto_cesantias' => 'Concepto Cesantias',
            'intereses' => 'Intereses',
            'fecha_creacion' => 'Fecha Creacion',
        ];
    }
}
