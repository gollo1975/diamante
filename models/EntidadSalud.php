<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entidad_salud".
 *
 * @property int $id_entidad_salud
 * @property string $entidad_salud
 * @property int $estado
 * @property string $user_name
 */
class EntidadSalud extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidad_salud';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entidad_salud'], 'required'],
            [['estado'], 'integer'],
            [['entidad_salud'], 'string', 'max' => 40],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_entidad_salud' => 'Id Entidad Salud',
            'entidad_salud' => 'Entidad Salud',
            'estado' => 'Estado',
            'user_name' => 'User Name',
        ];
    }
}
