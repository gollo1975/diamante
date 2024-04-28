<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grupo_producto".
 *
 * @property int $id_grupo
 * @property string $nombre_grupo
 * @property string $fecha_registro
 * @property string $user_name
 */
class GrupoProducto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grupo_producto';
    }
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->nombre_grupo = strtoupper($this->nombre_grupo); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_grupo','id_clasificacion'], 'required'],
            [['fecha_registro'], 'safe'],
            [['id_clasificacion','ver_registro'], 'integer'],
            [['nombre_grupo'], 'string', 'max' => 40],
            [['user_name'], 'string', 'max' => 15],
            [['id_clasificacion'], 'exist', 'skipOnError' => true, 'targetClass' => ClasificacionInventario::className(), 'targetAttribute' => ['id_clasificacion' => 'id_clasificacion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_grupo' => 'Código',
            'nombre_grupo' => 'Nombre del grupo',
            'fecha_registro' => 'Fecha registro',
            'user_name' => 'Usuario',
            'id_clasificacion' => 'Clasificación inventario',
            'ver_registro' => 'Venta publico',
        ];
    }
       
    public function getClasificacionInventario()
    {
        return $this->hasOne(ClasificacionInventario::className(), ['id_clasificacion' => 'id_clasificacion']);
    }
    
    public function getVentaPublico() {
        if($this->ver_registro == 0){
            $ventapublico = 'NO';
        }else{
            $ventapublico = 'Si';
        }
        
        return $ventapublico;
    }
    
}
