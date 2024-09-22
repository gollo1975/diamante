<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_salario".
 *
 * @property int $id_tipo_salario
 * @property string $descripcion
 */
class TipoSalario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_salario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['descripcion'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_salario' => 'Id Tipo Salario',
            'descripcion' => 'Descripcion',
        ];
    }
}
