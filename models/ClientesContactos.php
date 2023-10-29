<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clientes_contactos".
 *
 * @property int $id_contacto
 * @property int $id_cliente
 * @property string $nombres
 * @property string $apellidos
 * @property string $celular
 * @property string $email
 * @property int $id_cargo
 * @property string $fecha_nacimiento
 * @property string $fecha_registro
 *
 * @property Clientes $cliente
 * @property Cargos $cargo
 */
class ClientesContactos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clientes_contactos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'id_cargo'], 'integer'],
            [['id_cargo'], 'required'],
            [['fecha_nacimiento', 'fecha_registro'], 'safe'],
            [['nombres', 'apellidos'], 'string', 'max' => 30],
            [['celular','user_name'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 50],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_cargo'], 'exist', 'skipOnError' => true, 'targetClass' => Cargos::className(), 'targetAttribute' => ['id_cargo' => 'id_cargo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_contacto' => 'Id Contacto',
            'id_cliente' => 'Id Cliente',
            'nombres' => 'Nombres',
            'apellidos' => 'Apellidos',
            'celular' => 'Celular',
            'email' => 'Email',
            'id_cargo' => 'Id Cargo',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'fecha_registro' => 'Fecha Registro',
            'user_name' => 'user_name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Clientes::className(), ['id_cliente' => 'id_cliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCargo()
    {
        return $this->hasOne(Cargos::className(), ['id_cargo' => 'id_cargo']);
    }
}
