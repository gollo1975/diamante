<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clasificacion_inventario".
 *
 * @property int $id_clasificacion
 * @property string $descripcion
 */
class ClasificacionInventario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clasificacion_inventario';
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
            'id_clasificacion' => 'Codigo',
            'descripcion' => 'Descripcion',
        ];
    }
}
