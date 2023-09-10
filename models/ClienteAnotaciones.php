<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cliente_anotaciones".
 *
 * @property int $id_anotacion
 * @property string $anotacion
 * @property string $user_name
 * @property string $fecha_registro
 * @property int $id_cliente
 *
 * @property Clientes $cliente
 */
class ClienteAnotaciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cliente_anotaciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['anotacion'], 'required'],
            [['anotacion'], 'string'],
            [['fecha_registro'], 'safe'],
            [['id_cliente'], 'integer'],
            [['user_name'], 'string', 'max' => 15],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_anotacion' => 'Id Anotacion',
            'anotacion' => 'Anotacion',
            'user_name' => 'User Name',
            'fecha_registro' => 'Fecha Registro',
            'id_cliente' => 'Id Cliente',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Clientes::className(), ['id_cliente' => 'id_cliente']);
    }
}
