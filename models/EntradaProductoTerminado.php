<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entrada_producto_terminado".
 *
 * @property int $id_entrada
 * @property int $id_proveedor
 * @property int $id_orden_compra
 * @property string $fecha_proceso
 * @property string $fecha_registro
 * @property string $numero_soporte
 * @property int $subtotal
 * @property int $impuesto
 * @property int $total_salida
 * @property int $autorizado
 * @property int $enviar_materia_prima
 * @property string $user_name_crear
 * @property string $user_name_edit
 * @property string $observacion
 *
 * @property Proveedor $proveedor
 * @property OrdenCompra $ordenCompra
 */
class EntradaProductoTerminado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entrada_producto_terminado';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_proveedor'], 'required'],
            [['id_proveedor', 'id_orden_compra', 'subtotal', 'impuesto', 'total_salida', 'autorizado', 'enviar_materia_prima','tipo_entrada'], 'integer'],
            [['fecha_proceso', 'fecha_registro'], 'safe'],
            [['observacion'], 'string'],
            [['numero_soporte'], 'string', 'max' => 10],
            [['user_name_crear', 'user_name_edit'], 'string', 'max' => 15],
            [['id_proveedor'], 'exist', 'skipOnError' => true, 'targetClass' => Proveedor::className(), 'targetAttribute' => ['id_proveedor' => 'id_proveedor']],
            [['id_orden_compra'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenCompra::className(), 'targetAttribute' => ['id_orden_compra' => 'id_orden_compra']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_entrada' => 'Codigo:',
            'id_proveedor' => 'Proveedor:',
            'id_orden_compra' => 'Orden compra:',
            'fecha_proceso' => 'Fecha proceso:',
            'fecha_registro' => 'Fecha registro:',
            'numero_soporte' => 'Numero soporte:',
            'subtotal' => 'Subtotal:',
            'impuesto' => 'Impuesto:',
            'total_salida' => 'Total pagar:',
            'autorizado' => 'Autorizado:',
            'enviar_materia_prima' => 'Enviar producto:',
            'user_name_crear' => 'User name creador:',
            'user_name_edit' => 'User name editado:',
            'observacion' => 'Observacion:',
            'tipo_entrada' => 'Tipo entrada:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProveedor()
    {
        return $this->hasOne(Proveedor::className(), ['id_proveedor' => 'id_proveedor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenCompra()
    {
        return $this->hasOne(OrdenCompra::className(), ['id_orden_compra' => 'id_orden_compra']);
    }
    public function getAutorizadoCompra() {
        if($this->autorizado == 0){
            $autorizadocompra = 'NO';
        }else{
            $autorizadocompra = 'SI';
        }
        return $autorizadocompra;
    }
    public function getEnviarMateria() {
        if($this->enviar_materia_prima == 0){
            $enviarmateria = 'NO';
        }else{
            $enviarmateria = 'SI';
        }
        return $enviarmateria;
    }
    public function getTipoEntrada() {
        if($this->tipo_entrada == 1){
            $tipoentrada = 'ORDEN DE COMPRA';
        }else{
            $tipoentrada = 'MANUAL';
        }
        return $tipoentrada;
    }
    
}
