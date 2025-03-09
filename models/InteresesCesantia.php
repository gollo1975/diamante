<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "intereses_cesantia".
 *
 * @property int $id_interes
 * @property int $id_programacion
 * @property int $id_grupo_pago
 * @property int $id_periodo_pago_nomina
 * @property int $id_tipo_nomina
 * @property int $id_contrato
 * @property int $id_empleado
 * @property int $documento
 * @property string $inicio_contrato
 * @property int $salario_promedio
 * @property int $vlr_cesantia
 * @property string $fecha_inicio
 * @property string $fecha_corte
 * @property int $dias_generados
 * @property int $vlr_intereses
 * @property double $porcentaje
 * @property string $fecha_creacion
 * @property string $user_name
 * @property int $importado
 * @property int $enviado
 *
 * @property TipoNomina $tipoNomina
 * @property Contratos $contrato
 * @property Empleados $empleado
 * @property ProgramacionNomina $programacion
 * @property GrupoPago $grupoPago
 * @property PeriodoPagoNomina $periodoPagoNomina
 */
class InteresesCesantia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'intereses_cesantia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_programacion', 'id_grupo_pago', 'id_periodo_pago_nomina', 'id_tipo_nomina', 'id_contrato', 'id_empleado', 'documento', 'salario_promedio', 'valor_cesantias', 
                'dias_generados', 'valor_intereses', 'importado', 'enviado','codigo_salario','anio'], 'integer'],
            [['inicio_contrato', 'fecha_inicio', 'fecha_corte', 'fecha_creacion'], 'safe'],
            [['porcentaje'], 'number'],
            [['user_name'], 'string', 'max' => 15],
            [['id_tipo_nomina'], 'exist', 'skipOnError' => true, 'targetClass' => TipoNomina::className(), 'targetAttribute' => ['id_tipo_nomina' => 'id_tipo_nomina']],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contratos::className(), 'targetAttribute' => ['id_contrato' => 'id_contrato']],
            [['id_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleados::className(), 'targetAttribute' => ['id_empleado' => 'id_empleado']],
            [['id_programacion'], 'exist', 'skipOnError' => true, 'targetClass' => ProgramacionNomina::className(), 'targetAttribute' => ['id_programacion' => 'id_programacion']],
            [['id_grupo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoPago::className(), 'targetAttribute' => ['id_grupo_pago' => 'id_grupo_pago']],
            [['id_periodo_pago_nomina'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoPagoNomina::className(), 'targetAttribute' => ['id_periodo_pago_nomina' => 'id_periodo_pago_nomina']],
            [['codigo_salario'], 'exist', 'skipOnError' => true, 'targetClass' => ConceptoSalarios::className(), 'targetAttribute' => ['codigo_salario' => 'codigo_salario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_interes' => 'Id Interes',
            'id_programacion' => 'Id Programacion',
            'id_grupo_pago' => 'Id Grupo Pago',
            'id_periodo_pago_nomina' => 'Id Periodo Pago Nomina',
            'id_tipo_nomina' => 'Id Tipo Nomina',
            'id_contrato' => 'Id Contrato',
            'id_empleado' => 'Id Empleado',
            'documento' => 'Documento',
            'inicio_contrato' => 'Inicio Contrato',
            'salario_promedio' => 'Salario Promedio',
            'valor_cesantias' => 'Vlr Cesantia',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_corte' => 'Fecha Corte',
            'dias_generados' => 'Dias Generados',
            'valor_intereses' => 'Vlr Intereses',
            'porcentaje' => 'Porcentaje',
            'fecha_creacion' => 'Fecha Creacion',
            'user_name' => 'User Name',
            'importado' => 'Importado',
            'enviado' => 'Enviado',
            'codigo_salario' => 'codigo_salario',
            'anio' => 'Año:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoNomina()
    {
        return $this->hasOne(TipoNomina::className(), ['id_tipo_nomina' => 'id_tipo_nomina']);
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
    public function getEmpleado()
    {
        return $this->hasOne(Empleados::className(), ['id_empleado' => 'id_empleado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramacion()
    {
        return $this->hasOne(ProgramacionNomina::className(), ['id_programacion' => 'id_programacion']);
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
    public function getPeriodoPagoNomina()
    {
        return $this->hasOne(PeriodoPagoNomina::className(), ['id_periodo_pago_nomina' => 'id_periodo_pago_nomina']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getConceptoSalario()
    {
        return $this->hasOne(ConceptoSalarios::className(), ['codigo_salario' => 'codigo_salario']);
    }
    
    public function getEnviarDato() {

        if($this->enviado == 0){
            $enviardato = 'NO';
        }else{
            $enviardato = 'SI';
        }
        return $enviardato;
    }    
}
