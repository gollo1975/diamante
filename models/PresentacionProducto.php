<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "presentacion_producto".
 *
 * @property int $id_presentacion
 * @property int $id_grupo
 * @property string $descripcion
 * @property string $fecha_registro
 * @property string $user_name
 *
 * @property GrupoProducto $grupo
 */
class PresentacionProducto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'presentacion_producto';
    }
    
      public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->descripcion = strtoupper($this->descripcion); 
 
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_producto', 'descripcion', 'id_medida_producto','tipo_venta'], 'required'],
            [['id_grupo','id_medida_producto','id_producto','total_item','tipo_venta'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['descripcion'], 'string', 'max' => 70],
            [['user_name'], 'string', 'max' => 15],
            [['id_grupo'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoProducto::className(), 'targetAttribute' => ['id_grupo' => 'id_grupo']],
            [['id_producto'], 'exist', 'skipOnError' => true, 'targetClass' => Productos::className(), 'targetAttribute' => ['id_producto' => 'id_producto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_presentacion' => 'Id',
            'id_grupo' => 'Grupo producto:',
            'id_producto' => 'Nombre del producto:',
            'descripcion' => 'Presentacion del producto:',
            'fecha_registro' => 'Fecha registro',
            'user_name' => 'User name',
            'id_medida_producto' => 'Unidad de medida:',
            'total_item' => 'total_item',
            'tipo_venta' => 'Tipo de venta:'
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
    public function getMedidaProducto()
    {
        return $this->hasOne(MedidaProductoTerminado::className(), ['id_medida_producto' => 'id_medida_producto']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducto()
    {
        return $this->hasOne(Productos::className(), ['id_producto' => 'id_producto']);
    }
    
    public function getTipoVenta() {
        if($this->tipo_venta == 0){
            $tipoventa = 'INDIVIDUAL';
        }else{
            $tipoventa = 'KITS';
        }
        return $tipoventa;
    }
    
  
}
