<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "presentacion_empresa".
 *
 * @property int $id
 * @property string $empresa
 * @property string $descripcion
 * @property string $email_soporte
 * @property string $celular1
 * @property string $celular2
 */
class PresentacionEmpresa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'presentacion_empresa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['empresa', 'email_soporte'], 'string', 'max' => 60],
            [['descripcion'], 'string', 'max' => 250],
            [['celular1', 'celular2'], 'string', 'max' => 15],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'empresa' => 'Empresa',
            'descripcion' => 'Descripcion',
            'email_soporte' => 'Email Soporte',
            'celular1' => 'Celular1',
            'celular2' => 'Celular2',
        ];
    }
}
