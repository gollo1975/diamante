<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recibo_caja".
 *
 * @property int $id_recibo
 * @property int $numero_recibo
 * @property int $id_cliente
 * @property int $id_tipo
 * @property string $fecha_pago
 * @property string $fecha_proceso
 * @property int $valor_pago
 * @property int $autorizado
 * @property string $codigo_municipio
 * @property string $codigo_banco
 * @property string $observacion
 * @property string $user_name
 *
 * @property Clientes $cliente
 * @property TipoReciboCaja $tipo
 * @property Municipios $codigoMunicipio
 * @property EntidadBancarias $codigoBanco
 */
class ReciboCaja extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'recibo_caja';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_recibo', 'id_cliente', 'id_tipo', 'valor_pago', 'autorizado'], 'integer'],
            [['id_tipo', 'fecha_pago', 'codigo_banco'], 'required'],
            [['fecha_pago', 'fecha_proceso'], 'safe'],
            [['codigo_municipio', 'codigo_banco'], 'string', 'max' => 10],
            [['observacion'], 'string', 'max' => 100],
            [['user_name'], 'string', 'max' => 15],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_tipo'], 'exist', 'skipOnError' => true, 'targetClass' => TipoReciboCaja::className(), 'targetAttribute' => ['id_tipo' => 'id_tipo']],
            [['codigo_municipio'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio' => 'codigo_municipio']],
            [['codigo_banco'], 'exist', 'skipOnError' => true, 'targetClass' => EntidadBancarias::className(), 'targetAttribute' => ['codigo_banco' => 'codigo_banco']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_recibo' => 'Id Recibo',
            'numero_recibo' => 'Numero Recibo',
            'id_cliente' => 'Id Cliente',
            'id_tipo' => 'Id Tipo',
            'fecha_pago' => 'Fecha Pago',
            'fecha_proceso' => 'Fecha Proceso',
            'valor_pago' => 'Valor Pago',
            'autorizado' => 'Autorizado',
            'codigo_municipio' => 'Codigo Municipio',
            'codigo_banco' => 'Codigo Banco',
            'observacion' => 'Observacion',
            'user_name' => 'User Name',
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
    public function getTipo()
    {
        return $this->hasOne(TipoReciboCaja::className(), ['id_tipo' => 'id_tipo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoMunicipio()
    {
        return $this->hasOne(Municipios::className(), ['codigo_municipio' => 'codigo_municipio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoBanco()
    {
        return $this->hasOne(EntidadBancarias::className(), ['codigo_banco' => 'codigo_banco']);
    }
}
