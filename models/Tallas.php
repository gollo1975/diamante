<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tallas".
 *
 * @property int $id_talla
 * @property string $nombre_talla
 */
class Tallas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tallas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_talla'], 'required'],
            [['nombre_talla'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_talla' => '',
            'nombre_talla' => 'Nombre Talla',
        ];
    }
}
