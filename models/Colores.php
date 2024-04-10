<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "colores".
 *
 * @property int $id_color
 * @property string $colores
 * @property string $codigo
 */
class Colores extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'colores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['colores'], 'required'],
            [['colores'], 'string', 'max' => 15],
            [['codigo'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_color' => '',
            'colores' => 'Colores',
            'codigo' => 'Codigo',
        ];
    }
}
