<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_solicitud".
 *
 * @property int $id_solicitud
 * @property string $descripcion
 */
class TipoSolicitud extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_solicitud';
    }

     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->descripcion= strtoupper($this->descripcion); 
 
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['descripcion'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_solicitud' => 'Id',
            'descripcion' => 'Descripción',
        ];
    }
}
