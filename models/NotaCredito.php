<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "nota_credito".
 *
 * @property int $id_nota
 * @property int $numero_nota_credito
 * @property int $id_cliente
 * @property string $nit_cedula
 * @property string $cliente
 * @property int $id_motivo
 * @property string $cufe_factura
 * @property int $id_factura
 * @property int $id_tipo_factura
 * @property string $fecha_factura
 * @property string $fecha_nota_credito
 * @property string $fecha_enviada
 * @property int $valor_devolucion
 * @property int $valor_bruto
 * @property int $impuesto
 * @property int $retencion
 * @property int $rete_iva
 * @property int $valor_total_devolucion
 * @property string $user_name
 * @property int $autorizado
 * @property int $cerrar_nota
 * @property int $nuevo_saldo
 *
 * @property Clientes $cliente0
 * @property MotivoNotaCredito $motivo
 * @property FacturaVenta $factura
 * @property TipoFacturaVenta $tipoFactura
 */
class NotaCredito extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nota_credito';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_nota_credito', 'id_cliente', 'id_motivo', 'id_factura', 'id_tipo_factura', 'valor_devolucion', 'valor_bruto', 'impuesto', 'retencion', 'rete_iva',
                'valor_total_devolucion', 'autorizado', 'cerrar_nota', 'nuevo_saldo','numero_factura'], 'integer'],
            [['fecha_factura', 'fecha_nota_credito', 'fecha_hora_enviada','fecha_recepcion_dian'], 'safe'],
            [['nit_cedula', 'user_name'], 'string', 'max' => 15],
            [['cliente'], 'string', 'max' => 50],
            [['observacion'], 'string', 'max' => 100],
            [['cufe_factura','cude'], 'string', 'max' => 300],
            [['qrstr'], 'string'],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_motivo'], 'exist', 'skipOnError' => true, 'targetClass' => MotivoNotaCredito::className(), 'targetAttribute' => ['id_motivo' => 'id_motivo']],
            [['id_factura'], 'exist', 'skipOnError' => true, 'targetClass' => FacturaVenta::className(), 'targetAttribute' => ['id_factura' => 'id_factura']],
            [['id_tipo_factura'], 'exist', 'skipOnError' => true, 'targetClass' => TipoFacturaVenta::className(), 'targetAttribute' => ['id_tipo_factura' => 'id_tipo_factura']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_nota' => 'Id:',
            'numero_nota_credito' => 'Numero:',
            'id_cliente' => 'Cliente:',
            'nit_cedula' => 'Nit cedula:',
            'cliente' => 'Cliente',
            'id_motivo' => 'Motivo nota:',
            'cufe_factura' => 'Cufe',
            'cude' => 'cude',
            'qrstr' => 'qrstr',
            'id_factura' => 'Número factura:',
            'id_tipo_factura' => 'Tipo documento:',
            'fecha_factura' => 'Fecha factura:',
            'fecha_nota_credito' => 'Fecha nota Credito:',
            'fecha_hora_enviada' => 'Fecha enviada:',
            'fecha_recepcion_dian' => 'fecha_recepcion_dian',
            'valor_devolucion' => 'Valor nota',
            'valor_bruto' => 'Valor bruto',
            'impuesto' => 'Impuesto:',
            'retencion' => 'Retencion:',
            'rete_iva' => 'Rete Iva:',
            'valor_total_devolucion' => 'Valor devolucion:',
            'user_name' => 'User Name',
            'autorizado' => 'Autorizado:',
            'cerrar_nota' => 'Cerrado:',
            'nuevo_saldo' => 'Nuevo saldo:',
            'observacion' => 'Observacion',
            'numero_factura' => 'Numero factura',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClienteNota()
    {
        return $this->hasOne(Clientes::className(), ['id_cliente' => 'id_cliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMotivo()
    {
        return $this->hasOne(MotivoNotaCredito::className(), ['id_motivo' => 'id_motivo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFactura()
    {
        return $this->hasOne(FacturaVenta::className(), ['id_factura' => 'id_factura']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoFactura()
    {
        return $this->hasOne(TipoFacturaVenta::className(), ['id_tipo_factura' => 'id_tipo_factura']);
    }
    
    public function getAutorizadoNota() {
        if($this->autorizado == 0){
            $autotrizadonota = 'NO';
        }else{
            $autotrizadonota = 'SI';
        }
        return $autotrizadonota;
    }
      public function getCerrarNota() {
        if($this->cerrar_nota == 0){
            $cerrarnota = 'NO';
        }else{
            $cerrarnota = 'SI';
        }
        return $cerrarnota;
    }
}
