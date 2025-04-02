<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prestaciones_sociales".
 *
 * @property int $id_prestacion
 * @property int $id_empleado
 * @property int $id_contrato
 * @property int $documento
 * @property int $nro_pago
 * @property int $id_grupo_pago
 * @property string $fecha_inicio_contrato
 * @property string $fecha_termino_contrato
 * @property string $fecha_creacion
 * @property int $salario
 * @property int $dias_primas
 * @property int $ibp_prima
 * @property string $ultimo_pago_prima
 * @property int $dias_ausencia_prima
 * @property int $dias_cesantias
 * @property int $ibp_cesantias
 * @property string $ultimo_pago_cesantias
 * @property int $dias_ausencia_cesantias
 * @property int $interes_cesantia
 * @property double $porcentaje_interes
 * @property int $dias_vacaciones
 * @property int $ibp_vacaciones
 * @property string $ultimo_pago_vacaciones
 * @property int $dias_ausencia_vacaciones
 * @property int $total_indemnizacion
 * @property int $total_deduccion
 * @property int $total_devengado
 * @property int $total_pagar
 * @property string $observacion
 * @property int $estado_generado
 * @property int $estado_aplicado
 * @property int $estado_cerrado
 * @property string $usuariosistema
 *
 * @property Empleados $empleado
 * @property Contratos $contrato
 * @property GrupoPago $grupoPago
 * @property PrestacionesSocialesAdicion[] $prestacionesSocialesAdicions
 * @property PrestacionesSocialesCreditos[] $prestacionesSocialesCreditos
 * @property PrestacionesSocialesDetalle[] $prestacionesSocialesDetalles
 */
class PrestacionesSociales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prestaciones_sociales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empleado', 'id_contrato', 'id_grupo_pago'], 'required'],
            [['id_empleado', 'id_contrato', 'documento', 'nro_pago', 'id_grupo_pago', 'salario', 'dias_primas', 'ibp_prima', 'dias_ausencia_prima', 'dias_cesantias', 'ibp_cesantias', 'dias_ausencia_cesantias', 'interes_cesantia', 'dias_vacaciones', 'ibp_vacaciones', 'dias_ausencia_vacaciones', 'total_indemnizacion', 'total_deduccion', 
                'total_devengado', 'total_pagar', 'estado_generado', 'estado_aplicado', 'estado_cerrado','generar_pagos'], 'integer'],
            [['fecha_inicio_contrato', 'fecha_termino_contrato', 'fecha_creacion', 'ultimo_pago_prima', 'ultimo_pago_cesantias', 'ultimo_pago_vacaciones'], 'safe'],
            [['porcentaje_interes'], 'number'],
            [['observacion'], 'string', 'max' => 100],
            [['usuariosistema'], 'string', 'max' => 15],
            [['id_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleados::className(), 'targetAttribute' => ['id_empleado' => 'id_empleado']],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contratos::className(), 'targetAttribute' => ['id_contrato' => 'id_contrato']],
            [['id_grupo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoPago::className(), 'targetAttribute' => ['id_grupo_pago' => 'id_grupo_pago']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_prestacion' => 'Codigo',
            'id_empleado' => 'Empleado',
            'id_contrato' => 'No contrato',
            'documento' => 'Documento',
            'nro_pago' => 'Nro pago',
            'id_grupo_pago' => 'Grupo de pago',
            'fecha_inicio_contrato' => 'Fecha Inicio Contrato',
            'fecha_termino_contrato' => 'Fecha Termino Contrato',
            'fecha_creacion' => 'Fecha Creacion',
            'salario' => 'Salario',
            'dias_primas' => 'Dias primas',
            'ibp_prima' => 'Ibp Prima',
            'ultimo_pago_prima' => 'Ultimo Pago Prima',
            'dias_ausencia_prima' => 'Dias Ausencia Prima',
            'dias_cesantias' => 'Dias Cesantias',
            'ibp_cesantias' => 'Ibp Cesantias',
            'ultimo_pago_cesantias' => 'Ultimo Pago Cesantias',
            'dias_ausencia_cesantias' => 'Dias Ausencia Cesantias',
            'interes_cesantia' => 'Interes Cesantia',
            'porcentaje_interes' => 'Porcentaje Interes',
            'dias_vacaciones' => 'Dias Vacaciones',
            'ibp_vacaciones' => 'Ibp Vacaciones',
            'ultimo_pago_vacaciones' => 'Ultimo Pago Vacaciones',
            'dias_ausencia_vacaciones' => 'Dias Ausencia Vacaciones',
            'total_indemnizacion' => 'Total indemnizacion',
            'total_deduccion' => 'Total deduccion',
            'total_devengado' => 'Total devengado',
            'total_pagar' => 'Total pagar',
            'observacion' => 'Observacion',
            'estado_generado' => 'Estado Generado',
            'estado_aplicado' => 'Estado Aplicado',
            'estado_cerrado' => 'Estado Cerrado',
            'usuariosistema' => 'Usuariosistema',
            'generar_pagos' => 'generar_pagos'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleado()
    {
        return $this->hasOne(Empleados::className(), ['id_empleado' => 'id_empleado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContrato()
    {
        return $this->hasOne(Contratos::className(), ['id_contrato' => 'id_contrato']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoPago()
    {
        return $this->hasOne(GrupoPago::className(), ['id_grupo_pago' => 'id_grupo_pago']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrestacionesSocialesAdicions()
    {
        return $this->hasMany(PrestacionesSocialesAdicion::className(), ['id_prestacion' => 'id_prestacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrestacionesSocialesCreditos()
    {
        return $this->hasMany(PrestacionesSocialesCreditos::className(), ['id_prestacion' => 'id_prestacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrestacionesSocialesDetalles()
    {
        return $this->hasMany(PrestacionesSocialesDetalle::className(), ['id_prestacion' => 'id_prestacion']);
    }
    
    public function getEstadoGenerado() {
        if($this->estado_generado == 0){
            $estadogenerado = 'NO';
        }else{
            $estadogenerado = 'SI';
        }
        return  $estadogenerado;
    }
    
    public function getEstadoAplicado() {
        if($this->estado_aplicado == 0){
            $estadoaplicado = 'NO';
        }else{
            $estadoaplicado = 'SI';
        }
        return  $estadoaplicado;
    }
    
    public function getEstadoCerrado() {
        if($this->estado_cerrado == 0){
            $estadocerrado = 'NO';
        }else{
            $estadocerrado = 'SI';
        }
        return  $estadocerrado;
    }
}
