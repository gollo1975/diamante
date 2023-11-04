<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_devolucion_productos".
 *
 * @property int $id_tipo_devolucion
 * @property string $concepto
 * @property string $user_name
 * @property string $fecha_registro
 */
class TipoDevolucionProductos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_devolucion_productos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto'], 'required'],
            [['fecha_registro'], 'safe'],
            [['concepto'], 'string', 'max' => 30],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_devolucion' => 'Id Tipo Devolucion',
            'concepto' => 'Concepto',
            'user_name' => 'User Name',
            'fecha_registro' => 'Fecha Registro',
        ];
    }
}
