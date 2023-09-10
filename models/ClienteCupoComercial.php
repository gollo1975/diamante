<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cliente_cupo_comercial".
 *
 * @property int $id_cupo
 * @property int $descripcion
 * @property int $valor_cupo
 * @property string $user_name
 * @property string $fecha_registro
 * @property int $id_cliente
 *
 * @property Clientes $cliente
 */
class ClienteCupoComercial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cliente_cupo_comercial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'valor_cupo', 'id_cliente','estado_registro'], 'integer'],
            [['fecha_registro'], 'safe'],
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
            'id_cupo' => 'Id Cupo',
            'descripcion' => 'Descripcion',
            'valor_cupo' => 'Valor Cupo',
            'user_name' => 'User Name',
            'fecha_registro' => 'Fecha Registro',
           'id_cliente' => 'Id Cliente',
            'estado_registro' =>'estado_registro',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Clientes::className(), ['id_cliente' => 'id_cliente']);
    }
    public function getEstadoRegistro() {
        if($this->estado_registro == 0){
            $estadoregistro = 'SI';
        }else{
            $estadoregistro = 'NO';
        }
        return $estadoregistro;
    }
}
