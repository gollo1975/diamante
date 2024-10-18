<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pago_adicional_permanente".
 *
 * @property int $id_pago_permanente
 * @property int $id_empleado
 * @property int $codigo_salario
 * @property int $id_contrato
 * @property int $id_grupo_pago
 * @property int $id_pago_fecha
 * @property string $fecha_corte
 * @property int $tipo_adicion
 * @property int $valor_adicion
 * @property int $permanente
 * @property int $aplicar_dia_laborado
 * @property int $aplicar_prima
 * @property int $aplicar_cesantias
 * @property int $estado_registro
 * @property int $estado_periodo
 * @property string $detalle
 * @property string $fecha_creacion
 * @property string $user_name
 *
 * @property Empleados $empleado
 * @property ConceptoSalarios $codigoSalario
 * @property Contratos $contrato
 * @property GrupoPago $grupoPago
 * @property PagoAdicionalFecha $pagoFecha
 */
class PagoAdicionalPermanente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pago_adicional_permanente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empleado', 'codigo_salario', 'valor_adicion'], 'required'],
            [['id_empleado', 'codigo_salario', 'id_contrato', 'id_grupo_pago', 'id_pago_fecha', 'tipo_adicion', 'valor_adicion', 'permanente', 'aplicar_dia_laborado', 'aplicar_prima', 'aplicar_cesantias', 'estado_registro', 'estado_periodo'], 'integer'],
            [['fecha_corte', 'fecha_creacion'], 'safe'],
            [['detalle'], 'string', 'max' => 50],
            [['user_name'], 'string', 'max' => 30],
            [['id_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleados::className(), 'targetAttribute' => ['id_empleado' => 'id_empleado']],
            [['codigo_salario'], 'exist', 'skipOnError' => true, 'targetClass' => ConceptoSalarios::className(), 'targetAttribute' => ['codigo_salario' => 'codigo_salario']],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contratos::className(), 'targetAttribute' => ['id_contrato' => 'id_contrato']],
            [['id_grupo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoPago::className(), 'targetAttribute' => ['id_grupo_pago' => 'id_grupo_pago']],
            [['id_pago_fecha'], 'exist', 'skipOnError' => true, 'targetClass' => PagoAdicionalFecha::className(), 'targetAttribute' => ['id_pago_fecha' => 'id_pago_fecha']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pago_permanente' => 'Id Pago Permanente',
            'id_empleado' => 'Id Empleado',
            'codigo_salario' => 'Codigo Salario',
            'id_contrato' => 'Id Contrato',
            'id_grupo_pago' => 'Id Grupo Pago',
            'id_pago_fecha' => 'Id Pago Fecha',
            'fecha_corte' => 'Fecha Corte',
            'tipo_adicion' => 'Tipo Adicion',
            'valor_adicion' => 'Valor Adicion',
            'permanente' => 'Permanente',
            'aplicar_dia_laborado' => 'Aplicar Dia Laborado',
            'aplicar_prima' => 'Aplicar Prima',
            'aplicar_cesantias' => 'Aplicar Cesantias',
            'estado_registro' => 'Estado Registro',
            'estado_periodo' => 'Estado Periodo',
            'detalle' => 'Detalle',
            'fecha_creacion' => 'Fecha Creacion',
            'user_name' => 'User Name',
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
    public function getCodigoSalario()
    {
        return $this->hasOne(ConceptoSalarios::className(), ['codigo_salario' => 'codigo_salario']);
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
    public function getPagoFecha()
    {
        return $this->hasOne(PagoAdicionalFecha::className(), ['id_pago_fecha' => 'id_pago_fecha']);
    }
    
     public function getAplicardialaborado(){
        if($this->aplicar_dia_laborado == 1){
          $aplicardialaborado = "SI";
        }else{
          $aplicardialaborado = "NO";
        }
        return $aplicardialaborado;
    }
    
    public function getAplicarPrima(){
        if($this->aplicar_prima == 1){
          $aplicarprima = "SI";
        }else{
          $aplicarprima = "NO";
        }
        return $aplicarprima;
    }
    
     public function getAplicarCesantia(){
        if($this->aplicar_cesantias == 1){
          $aplicarcesantia = "SI";
        }else{
          $aplicarcesantia = "NO";
        }
        return $aplicarcesantia;
    }
    
    public function getPermanentes(){
        if($this->permanente == 1){
          $permanente = "SI";
        }else{
          $permanente = "NO";
        }
        return $permanente;
    }
    
       public function getEstadoPeriodo(){
        if($this->estado_periodo == 0){
          $estadoperiodo = "SI";
        }else{
          $estadoperiodo = "NO";
        }
        return $estadoperiodo;
    }
    
     public function getEstadoRegistro(){
        if($this->estado_registro == 0){
          $estadoregistro = "SI";
        }else{
          $estadoregistro = "NO";
        }
        return $estadoregistro;
    }
    
     public function getDebitoCredito(){
        if($this->tipo_adicion == 0){
          $debitocredito = "-";
        }else{
              $debitocredito = "+";
        }
        return $debitocredito;
    }
}
