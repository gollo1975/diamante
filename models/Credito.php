<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "credito".
 *
 * @property int $id_credito
 * @property int $id_empleado
 * @property int $id_grupo_pago
 * @property int $codigo_credito
 * @property int $id_tipo_pago
 * @property int $valor_credito
 * @property int $valor_cuota
 * @property int $numero_cuotas
 * @property int $numero_cuota_actual
 * @property int $validar_cuotas
 * @property string $fecha_creacion
 * @property string $fecha_inicio
 * @property int $seguro
 * @property string $numero_libranza
 * @property int $saldo_credito
 * @property int $estado_credito
 * @property int $estado_periodo
 * @property int $aplicar_prima
 * @property int $valor_aplicar
 * @property string $observacion
 * @property string $user_name
 *
 * @property Empleados $empleado
 * @property GrupoPago $grupoPago
 * @property ConfiguracionCredito $codigoCredito
 * @property TipoPagoCredito $tipoPago
 */
class Credito extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'credito';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empleado', 'codigo_credito', 'id_tipo_pago', 'valor_credito', 'valor_cuota', 'numero_cuotas', 'numero_cuota_actual', 'fecha_inicio'], 'required'],
            [['id_empleado', 'id_grupo_pago', 'codigo_credito', 'id_tipo_pago', 'valor_credito', 'valor_cuota', 'numero_cuotas', 'numero_cuota_actual', 'validar_cuotas', 'seguro', 'saldo_credito', 'estado_credito', 'estado_periodo', 'aplicar_prima', 'valor_aplicar'], 'integer'],
            [['fecha_creacion', 'fecha_inicio'], 'safe'],
            [['numero_libranza'], 'string', 'max' => 45],
            [['observacion'], 'string', 'max' => 100],
            [['user_name'], 'string', 'max' => 30],
            [['id_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleados::className(), 'targetAttribute' => ['id_empleado' => 'id_empleado']],
            [['id_grupo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoPago::className(), 'targetAttribute' => ['id_grupo_pago' => 'id_grupo_pago']],
            [['codigo_credito'], 'exist', 'skipOnError' => true, 'targetClass' => ConfiguracionCredito::className(), 'targetAttribute' => ['codigo_credito' => 'codigo_credito']],
            [['id_tipo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => TipoPagoCredito::className(), 'targetAttribute' => ['id_tipo_pago' => 'id_tipo_pago']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_credito' => 'Nro de credito:',
            'id_empleado' => 'Empleado:',
            'id_grupo_pago' => 'Grupo de pago:',
            'codigo_credito' => 'Tipo de credito:',
            'id_tipo_pago' => 'Tipo deducción:',
            'valor_credito' => 'Valor credito:',
            'valor_cuota' => 'Valor cuota:',
            'numero_cuotas' => 'Numero cuotas:',
            'numero_cuota_actual' => 'Numero cuota actual:',
            'validar_cuotas' => 'Validar cuotas:',
            'fecha_creacion' => 'Fecha creacion:',
            'fecha_inicio' => 'Fecha inicio:',
            'seguro' => 'Seguro:',
            'numero_libranza' => 'Numero de libranza:',
            'saldo_credito' => 'Saldo:',
            'estado_credito' => 'Credito activo:',
            'estado_periodo' => 'Periodo activo:',
            'aplicar_prima' => 'Aplicar prima:',
            'valor_aplicar' => 'Valor pago:',
            'observacion' => 'Observacion:',
            'user_name' => 'User Name:',
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
    public function getGrupoPago()
    {
        return $this->hasOne(GrupoPago::className(), ['id_grupo_pago' => 'id_grupo_pago']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoCredito()
    {
        return $this->hasOne(ConfiguracionCredito::className(), ['codigo_credito' => 'codigo_credito']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoPago()
    {
        return $this->hasOne(TipoPagoCredito::className(), ['id_tipo_pago' => 'id_tipo_pago']);
    }
    
    public function getEstadocredito(){
        if($this->estado_credito == 0){
            $estadocredito = 'SI';
        }else{
            $estadocredito = 'NO';
        }
        return $estadocredito;
    }
    public function getEstadoperiodo(){
        if($this->estado_periodo == 0){
            $estadoperiodo = 'SI';
        }else{
            $estadoperiodo = 'NO';
        }
        return $estadoperiodo;
    }
    
    public function getValidarcuota(){
        if($this->validar_cuotas == 1){
            $validarcuota = 'SI';
        }else{
            $validarcuota = 'NO';
        }
        return $validarcuota;
    }
    public function getAplicarprima(){
        if($this->aplicar_prima == 1){
            $aplicarprima = 'SI';
        }else{
            $aplicarprima = 'NO';
        }
        return $aplicarprima;
    }
}
