<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "especificacion_producto".
 *
 * @property int $id_especificacion
 * @property string $concepto
 * @property string $fecha_registro
 * @property string $user_name
 */
class EspecificacionProducto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'especificacion_producto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto'], 'required'],
            [['fecha_registro'], 'safe'],
            [['concepto'], 'string', 'max' => 50],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_especificacion' => 'Id Especificacion',
            'concepto' => 'Concepto',
            'fecha_registro' => 'Fecha Registro',
            'user_name' => 'User Name',
        ];
    }
}
