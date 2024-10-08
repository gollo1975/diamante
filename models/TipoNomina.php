<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_nomina".
 *
 * @property int $id_tipo_nomina
 * @property string $tipo_pago
 * @property int $ver_registro
 */
class TipoNomina extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_nomina';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo_pago'], 'required'],
            [['ver_registro'], 'integer'],
            [['tipo_pago'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_nomina' => 'Id Tipo Nomina',
            'tipo_pago' => 'Tipo Pago',
            'ver_registro' => 'Ver Registro',
        ];
    }
}
