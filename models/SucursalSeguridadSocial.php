<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sucursal_seguridad_social".
 *
 * @property int $id_sucursal
 * @property string $sucursal
 */
class SucursalSeguridadSocial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sucursal_seguridad_social';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sucursal'], 'required'],
            [['sucursal'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_sucursal' => 'Id Sucursal',
            'sucursal' => 'Sucursal',
        ];
    }
}
