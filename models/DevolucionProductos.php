<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "devolucion_productos".
 *
 * @property int $id_devolucion
 * @property int $id_cliente
 * @property int $id_nota
 * @property string $fecha_devolucion
 * @property int $cantidad
 * @property string $fecha_registro
 * @property string $user_name
 * @property string $observacion
 *
 * @property Clientes $cliente
 * @property NotaCredito $nota
 */
class DevolucionProductos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'devolucion_productos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'id_nota', 'cantidad_inventario','cantidad_averias','numero_devolucion','autorizado'], 'integer'],
            [['fecha_registro','fecha_devolucion'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['observacion'], 'string', 'max' => 150],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_nota'], 'exist', 'skipOnError' => true, 'targetClass' => NotaCredito::className(), 'targetAttribute' => ['id_nota' => 'id_nota']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_devolucion' => 'Codigo',
            'id_cliente' => 'Cliente:',
            'id_nota' => 'Nota crédito:',
            'fecha_devolucion' => 'Fecha devolucion:',
            'cantidad_inventario' => 'Cantidad inventario:',
            'fecha_registro' => 'Fecha registro:',
            'user_name' => 'User Name:',
            'observacion' => 'Observacion:',
            'numero_devolucion' => 'Numero devolución:',
            'autorizado' => 'Autorizado:',
            'cantidad_averias' => 'Cantidad averias:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Clientes::className(), ['id_cliente' => 'id_cliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNota()
    {
        return $this->hasOne(NotaCredito::className(), ['id_nota' => 'id_nota']);
    }
    
    public function getProductoAlmacenado() {
        if($this->almacenado == 0){
            $productoalmacenado = 'NO';
        }else{
            $productoalmacenado = 'SI';
        }
        return $productoalmacenado;
    }
    public function getAutorizadoProceso() {
        if($this->autorizado == 0){
            $autorizado = 'NO';
        }else{
            $autorizado = 'SI';
        }
        return $autorizado;
    }
}
