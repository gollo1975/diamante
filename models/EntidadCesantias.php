<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entidad_cesantias".
 *
 * @property int $id_cesantia
 * @property string $entidad
 */
class EntidadCesantias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidad_cesantias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entidad'], 'required'],
            [['entidad'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_cesantia' => 'Id Cesantia',
            'entidad' => 'Entidad',
        ];
    }
}
