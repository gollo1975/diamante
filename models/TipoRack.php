<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_rack".
 *
 * @property int $id_rack
 * @property int $numero_rack
 * @property string $descripcion
 * @property string $medidas
 * @property int $capacidad_instalada
 * @property int $capacidad_actual
 * @property string $fecha_creacion
 * @property string $user_name
 */
class TipoRack extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_rack';
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
            [['numero_rack', 'capacidad_instalada', 'capacidad_actual','estado'], 'integer'],
            [['descripcion'], 'required'],
            [['fecha_creacion'], 'safe'],
            [['descripcion', 'medidas'], 'string', 'max' => 30],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_rack' => 'Id:',
            'numero_rack' => 'Numero rack:',
            'descripcion' => 'Descripcion:',
            'medidas' => 'Medidas:',
            'capacidad_instalada' => 'Capacidad instalada:',
            'capacidad_actual' => 'Capacidad actual:',
            'fecha_creacion' => 'Fecha creacion:',
            'user_name' => 'User Name:',
            'estado' => 'Activo:',
        ];
    }
    public function getEstadoActivo() {
        if($this->estado == 0){
            $estadoactivo = 'SI';
        }else{
            $estadoactivo = 'NO';
        }
        return $estadoactivo;
    }
}
