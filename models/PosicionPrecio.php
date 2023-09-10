<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "posicion_precio".
 *
 * @property int $id_posicion
 * @property int $posicion
 */
class PosicionPrecio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posicion_precio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['posicion'], 'required'],
            [['posicion'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_posicion' => 'Id Posicion',
            'posicion' => 'Posicion',
        ];
    }
}
