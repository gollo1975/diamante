<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "posiciones".
 *
 * @property int $id_posicion
 * @property string $posicion
 */
class Posiciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posiciones';
    }
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->posicion = strtoupper($this->posicion); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['posicion'], 'required'],
            [['posicion'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_posicion' => 'Codigo',
            'posicion' => 'Posicion',
        ];
    }
}
