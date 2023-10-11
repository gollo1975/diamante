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
            [['numero_recibo', 'id_cliente', 'id_tipo', 'valor_pago', 'autorizado','recibo_cerrado'], 'integer'],
            [['id_tipo', 'fecha_pago', 'codigo_banco'], 'required'],
            [['fecha_pago', 'fecha_proceso'], 'safe'],
            [['codigo_municipio', 'codigo_banco'], 'string', 'max' => 10],
            [['observacion'], 'string', 'max' => 100],
            [['cliente','direccion_cliente'], 'string', 'max' => 50],
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
            'id_recibo' => 'Id',
            'numero_recibo' => 'Numero recibo',
            'id_cliente' => 'Cliente',
            'id_tipo' => 'Tipo recibo',
            'fecha_pago' => 'Fecha pago',
            'fecha_proceso' => 'Fecha proceso',
            'valor_pago' => 'Valor pago',
            'autorizado' => 'Autorizado',
            'codigo_municipio' => 'Municipio',
            'codigo_banco' => 'Entidad bancaria',
            'observacion' => 'Observacion',
            'user_name' => 'User Name',
            'cliente' => 'Cliente',
            'direccion_cliente' => 'Direccion',
            'recibo_cerrado' => 'Recibo cerrado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClienteRecibo()
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
    public function getAutorizado() {
        if($this->autorizado == 0){
            $autorizado = 'NO';
        }else{
            $autorizado = 'SI';
        }
        return $autorizado;
    }
    public function getReciboCerrado() {
        if($this->recibo_cerrado == 0){
            $recibocerrado = 'NO';
        }else{
            $recibocerrado = 'SI';
        }
        return $recibocerrado;
    }
}
