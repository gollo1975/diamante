<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "productos".
 *
 * @property int $id_producto
 * @property string $nombre_producto
 * @property int $id_grupo
 * @property int $id_marca
 * @property int $entradas
 * @property int $salidas
 * @property int $saldo_unidades
 * @property string $fecha_registro
 * @property string $user_name
 *
 * @property GrupoProducto $grupo
 * @property Marca $marca
 */
class Productos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'productos';
    }

     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->nombre_producto = strtoupper($this->nombre_producto); 
 
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_producto', 'id_grupo', 'id_marca'], 'required'],
            [['id_grupo', 'id_marca', 'entradas', 'salidas', 'saldo_unidades'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['nombre_producto'], 'string', 'max' => 50],
            [['user_name'], 'string', 'max' => 15],
            [['id_grupo'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoProducto::className(), 'targetAttribute' => ['id_grupo' => 'id_grupo']],
            [['id_marca'], 'exist', 'skipOnError' => true, 'targetClass' => Marca::className(), 'targetAttribute' => ['id_marca' => 'id_marca']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_producto' => 'Id',
            'nombre_producto' => 'Producto',
            'id_grupo' => 'Grupo',
            'id_marca' => 'Marca',
            'entradas' => 'Entradas',
            'salidas' => 'Salidas',
            'saldo_unidades' => 'Saldo Unidades',
            'fecha_registro' => 'Fecha Registro',
            'user_name' => 'User Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(GrupoProducto::className(), ['id_grupo' => 'id_grupo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMarca()
    {
        return $this->hasOne(Marca::className(), ['id_marca' => 'id_marca']);
    }
}
