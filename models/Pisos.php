<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pisos".
 *
 * @property int $id_piso
 * @property string $descripcion
 */
class Pisos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pisos';
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
            'id_piso' => 'Id Piso',
            'descripcion' => 'Descripcion',
        ];
    }
}
