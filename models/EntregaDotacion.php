<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entrega_dotacion".
 *
 * @property int $id_entrega
 * @property int $id_empleado
 * @property int $id_tipo_dotacion
 * @property string $fecha_entrega
 * @property string $fecha_hora_registro
 * @property int $cantidad
 * @property int $user_name
 * @property int $autorizado
 * @property int $cerrado
 * @property int $numero_entrega
 * @property string $observacion
 *
 * @property Empleados $empleado
 * @property TipoDotacion $tipoDotacion
 * @property EntregaDotacionDetalles[] $entregaDotacionDetalles
 */
class EntregaDotacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entrega_dotacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empleado', 'id_tipo_dotacion', 'fecha_entrega'], 'required'],
            [['id_empleado', 'id_tipo_dotacion', 'cantidad', 'autorizado', 'cerrado', 'numero_entrega','tipo_proceso','devuelto','descargar_inventario'], 'integer'],
            [['fecha_entrega', 'fecha_hora_registro'], 'safe'],
            [['observacion','user_name'], 'string', 'max' => 100],
            [['id_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleados::className(), 'targetAttribute' => ['id_empleado' => 'id_empleado']],
            [['id_tipo_dotacion'], 'exist', 'skipOnError' => true, 'targetClass' => TipoDotacion::className(), 'targetAttribute' => ['id_tipo_dotacion' => 'id_tipo_dotacion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_entrega' => 'Id',
            'id_empleado' => 'Empleado:',
            'id_tipo_dotacion' => 'Tipo dotacion:',
            'fecha_entrega' => 'Fecha entrega:',
            'fecha_hora_registro' => 'Fecha Hora Registro',
            'cantidad' => 'Cantidad',
            'user_name' => 'User Name',
            'autorizado' => 'Autorizado',
            'cerrado' => 'Cerrado',
            'numero_entrega' => 'Numero Entrega',
            'observacion' => 'Observacion:',
            'tipo_proceso' => 'Tipo proceso:',
            'devuelto' => 'Devuelto:',
            'descargar_inventario' => 'descargar_inventario',
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
    public function getTipoDotacion()
    {
        return $this->hasOne(TipoDotacion::className(), ['id_tipo_dotacion' => 'id_tipo_dotacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntregaDotacionDetalles()
    {
        return $this->hasMany(EntregaDotacionDetalles::className(), ['id_entrega' => 'id_entrega']);
    }
    
    public function getTipoProceso() {
        if($this->tipo_proceso == 0){
            $tipoproceso = 'SALIDA';
        }else{
            $tipoproceso = 'DEVOLUCION';
        }
        return  $tipoproceso;
    }
    
    public function getCerradaEntrega() {
        if($this->cerrado == 0){
            $cerradaentrega = 'NO';
        }else{
            $cerradaentrega = 'SI';
        }
        return  $cerradaentrega;
    }
    
    public function getAutorizadaEntrega() {
        if($this->autorizado == 0){
            $autorizadaentrega = 'NO';
        }else{
            $autorizadaentrega = 'SI';
        }
        return  $autorizadaentrega;
    }
    
    public function getDescargoInventario() {
        if($this->descargar_inventario == 0){
            $descargarinventario = 'NO';
        }else{
            $descargarinventario = 'SI';
        }
        return  $descargarinventario;
    }
}
