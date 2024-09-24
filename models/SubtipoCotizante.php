<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subtipo_cotizante".
 *
 * @property int $id_subtipo_cotizante
 * @property string $descripcion
 * @property string $codigo_interfaz
 * @property string $user_name
 */
class SubtipoCotizante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subtipo_cotizante';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['descripcion'], 'string', 'max' => 100],
            [['codigo_interfaz'], 'string', 'max' => 10],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_subtipo_cotizante' => 'Id',
            'descripcion' => 'Descripcion',
            'codigo_interfaz' => 'Codigo Interfaz',
            'user_name' => 'User Name',
        ];
    }
}
