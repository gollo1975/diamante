<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "centro_trabajo".
 *
 * @property int $id_centro_trabajo
 * @property string $centro_trabajo
 * @property int $estado
 * @property string $user_name
 */
class CentroTrabajo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'centro_trabajo';
    }
    
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->centro_trabajo = strtoupper($this->centro_trabajo); 

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['centro_trabajo'], 'required'],
            [['estado'], 'integer'],
            [['centro_trabajo'], 'string', 'max' => 40],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_centro_trabajo' => 'Codigo',
            'centro_trabajo' => 'Centro Trabajo',
            'estado' => 'Activo',
            'user_name' => 'User Name',
        ];
    }
    
    public function getActivo() {
        if($this->estado == 0){
            $estado = 'SI';
        }else{
            $estado = 'NO';
        }
        return $estado;
    }
}
