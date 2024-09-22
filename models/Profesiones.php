<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profesiones".
 *
 * @property int $id_profesion
 * @property string $profesion
 * @property double $ano_estudio
 */
class Profesiones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profesiones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['profesion'], 'required'],
            [['ano_estudio'], 'number'],
            [['profesion'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_profesion' => 'Id Profesion',
            'profesion' => 'Profesion',
            'ano_estudio' => 'Ano Estudio',
        ];
    }
}
