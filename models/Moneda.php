<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "moneda".
 *
 * @property int $id_moneda
 * @property string $descripcion
 * @property string $abreviatura
 * @property string $user_name
 *
 * @property ClienteMoneda[] $clienteMonedas
 */
class Moneda extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'moneda';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'abreviatura'], 'required'],
            [['descripcion'], 'string', 'max' => 30],
            [['abreviatura'], 'string', 'max' => 10],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_moneda' => 'Id Moneda',
            'descripcion' => 'Descripcion',
            'abreviatura' => 'Abreviatura',
            'user_name' => 'User Name',
            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClienteMonedas()
    {
        return $this->hasMany(ClienteMoneda::className(), ['id_moneda' => 'id_moneda']);
    }
}
