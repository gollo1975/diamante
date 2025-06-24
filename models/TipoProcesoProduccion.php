<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_proceso_produccion".
 *
 * @property int $id_proceso_produccion
 * @property string $nombre_proceso
 * @property string $user_name
 */
class TipoProcesoProduccion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_proceso_produccion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_proceso'], 'required'],
            [['nombre_proceso'], 'string', 'max' => 30],
            [['user_name'], 'string', 'max' => 15],
            [['consecutivo'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_proceso_produccion' => 'Codigo',
            'nombre_proceso' => 'Nombre proceso',
            'user_name' => 'User name',
            'consecutivo' => 'consecutivo',
        ];
    }
}
