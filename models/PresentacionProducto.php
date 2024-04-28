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
            [['id_grupo', 'descripcion','id_marca', 'id_medida_producto'], 'required'],
            [['id_grupo','id_marca','id_medida_producto'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['descripcion'], 'string', 'max' => 40],
            [['user_name'], 'string', 'max' => 15],
            [['id_grupo'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoProducto::className(), 'targetAttribute' => ['id_grupo' => 'id_grupo']],
            [['id_marca'], 'exist', 'skipOnError' => true, 'targetClass' => Marca::className(), 'targetAttribute' => ['id_marca' => 'id_marca']],
            [['id_medida_producto'], 'exist', 'skipOnError' => true, 'targetClass' => MedidaProductoTerminado::className(), 'targetAttribute' => ['id_medida_producto' => 'id_medida_producto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_presentacion' => 'Id',
            'id_grupo' => 'Grupo producto',
            'descripcion' => 'Descripcion',
            'fecha_registro' => 'Fecha registro',
            'user_name' => 'User name',
            'id_marca' => 'Marca:',
            'id_medida_producto' => 'Medida:',
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
    
    public function getMarca()
    {
        return $this->hasOne(Marca::className(), ['id_marca' => 'id_marca']);
    }
}
