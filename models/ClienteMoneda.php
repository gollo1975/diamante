<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cliente_moneda".
 *
 * @property int $id
 * @property int $id_cliente
 * @property int $id_moneda
 * @property string $nombre_moneda
 * @property string $sigla
 * @property double $tasa_negociacion
 * @property string $user_name
 *
 * @property Clientes $cliente
 * @property Moneda $moneda
 */
class ClienteMoneda extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cliente_moneda';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'id_moneda'], 'integer'],
            [['id_moneda', 'tasa_negociacion'], 'required'],
            [['tasa_negociacion'], 'number'],
            [['nombre_moneda'], 'string', 'max' => 30],
            [['sigla','operador'], 'string', 'max' => 10],
            [['user_name'], 'string', 'max' => 15],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_moneda'], 'exist', 'skipOnError' => true, 'targetClass' => Moneda::className(), 'targetAttribute' => ['id_moneda' => 'id_moneda']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_cliente' => 'Id Cliente',
            'id_moneda' => 'Id Moneda',
            'nombre_moneda' => 'Nombre Moneda',
            'sigla' => 'Sigla',
            'tasa_negociacion' => 'Tasa Negociacion',
            'user_name' => 'User Name',
            'operador' => 'operador',
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
    public function getMoneda()
    {
        return $this->hasOne(Moneda::className(), ['id_moneda' => 'id_moneda']);
    }
}
