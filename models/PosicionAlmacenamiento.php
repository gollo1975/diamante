<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "posicion_almacenamiento".
 *
 * @property int $id_movimiento
 * @property int $id_piso
 * @property int $id_rack
 * @property int $id_rack_nuevo
 * @property int $id_posicion
 * @property int $id_posicion_nueva
 * @property string $codigo
 * @property string $producto
 * @property int $cantidad
 * @property string $fecha_proceso
 * @property string $user_name
 * @property int $id
 *
 * @property Pisos $piso
 * @property TipoRack $rack
 * @property TipoRack $rackNuevo
 * @property Posiciones $posicion
 * @property Posiciones $posicionNueva
 * @property AlmacenamientoProductoDetalles $id0
 */
class PosicionAlmacenamiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posicion_almacenamiento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_piso', 'id_rack', 'id_rack_nuevo', 'id_posicion', 'id_posicion_nueva', 'cantidad', 'id','id'], 'integer'],
            [['fecha_proceso'], 'safe'],
            [['codigo', 'user_name'], 'string', 'max' => 15],
            [['producto'], 'string', 'max' => 40],
            [['id_piso'], 'exist', 'skipOnError' => true, 'targetClass' => Pisos::className(), 'targetAttribute' => ['id_piso' => 'id_piso']],
            [['id_piso_nuevo'], 'exist', 'skipOnError' => true, 'targetClass' => Pisos::className(), 'targetAttribute' => ['id_piso_nuevo' => 'id_piso']],
            [['id_rack'], 'exist', 'skipOnError' => true, 'targetClass' => TipoRack::className(), 'targetAttribute' => ['id_rack' => 'id_rack']],
            [['id_rack_nuevo'], 'exist', 'skipOnError' => true, 'targetClass' => TipoRack::className(), 'targetAttribute' => ['id_rack_nuevo' => 'id_rack']],
            [['id_posicion'], 'exist', 'skipOnError' => true, 'targetClass' => Posiciones::className(), 'targetAttribute' => ['id_posicion' => 'id_posicion']],
            [['id_posicion_nueva'], 'exist', 'skipOnError' => true, 'targetClass' => Posiciones::className(), 'targetAttribute' => ['id_posicion_nueva' => 'id_posicion']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => AlmacenamientoProductoDetalles::className(), 'targetAttribute' => ['id' => 'id']],
            //[['id_entrada'], 'exist', 'skipOnError' => true, 'targetClass' => AlmacenamientoProductoEntradaDetalles::className(), 'targetAttribute' => ['id_entrada' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_movimiento' => 'Id',
            'id_piso' => 'Piso:',
            'id_rack' => 'Rack:',
            'id_rack_nuevo' => 'Rack Nuevo:',
            'id_posicion' => 'Posicion:',
            'id_posicion_nueva' => 'Posicion Nueva',
            'codigo' => 'Codigo',
            'producto' => 'Producto',
            'cantidad' => 'Cantidad',
            'fecha_proceso' => 'Fecha Proceso',
            'user_name' => 'User Name',
            'id' => 'ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPiso()
    {
        return $this->hasOne(Pisos::className(), ['id_piso' => 'id_piso']);
    }
    
     public function getPisoNuevo()
    {
        return $this->hasOne(Pisos::className(), ['id_piso' => 'id_piso_nuevo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRack()
    {
        return $this->hasOne(TipoRack::className(), ['id_rack' => 'id_rack']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRackNuevo()
    {
        return $this->hasOne(TipoRack::className(), ['id_rack' => 'id_rack_nuevo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosicion()
    {
        return $this->hasOne(Posiciones::className(), ['id_posicion' => 'id_posicion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosicionNueva()
    {
        return $this->hasOne(Posiciones::className(), ['id_posicion' => 'id_posicion_nueva']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlmacenamientoLote()
    {
        return $this->hasOne(AlmacenamientoProductoDetalles::className(), ['id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
  /*  public function getAlmacenamientoEntrada()
    {
        return $this->hasOne(AlmacenamientoProductoEntradaDetalles::className(), ['id_entrada' => 'id_entrada']);
    }*/
}
