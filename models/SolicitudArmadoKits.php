<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "solicitud_armado_kits".
 *
 * @property int $id_solicitud_armado
 * @property int $id_solicitud
 * @property int $id_presentacion
 * @property int $total_unidades
 * @property string $fecha_solicitud
 * @property string $user_name
 * @property string $fecha_hora_proceso
 *
 * @property DocumentoSolicitudes $solicitud
 * @property PresentacionProducto $presentacion
 * @property SolicitudArmadoKitsDetalle[] $solicitudArmadoKitsDetalles
 */
class SolicitudArmadoKits extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'solicitud_armado_kits';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_solicitud', 'id_presentacion', 'fecha_solicitud','cantidad_solicitada'], 'required'],
            [['id_solicitud', 'id_presentacion', 'total_unidades', 'autorizado', 'proceso_cerrado','numero_solicitud','cantidad_solicitada','entregado','saldo_cantidad_solicitada'], 'integer'],
            [['fecha_solicitud', 'fecha_hora_proceso'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['observacion'],'string', 'max' => 100],
            [['id_solicitud'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentoSolicitudes::className(), 'targetAttribute' => ['id_solicitud' => 'id_solicitud']],
            [['id_presentacion'], 'exist', 'skipOnError' => true, 'targetClass' => PresentacionProducto::className(), 'targetAttribute' => ['id_presentacion' => 'id_presentacion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_solicitud_armado' => 'Id:',
            'id_solicitud' => 'Tipo de solicitud:',
            'id_presentacion' => 'Presentacion del producto:',
            'total_unidades' => 'Total unidades:',
            'fecha_solicitud' => 'Fecha Solicitud:',
            'user_name' => 'User Name:',
            'fecha_hora_proceso' => 'Fecha Hora Proceso',
            'numero_solicitud' => 'Numero de solicitud',
            'observacion' => 'Observacion:',
            'cantidad_solicitada' => 'Cantidad x presentacion:',
            'entregado' => 'entregado',
            'saldo_cantidad_solicitada' => 'saldo_cantidad_solicitada',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitud()
    {
        return $this->hasOne(DocumentoSolicitudes::className(), ['id_solicitud' => 'id_solicitud']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPresentacion()
    {
        return $this->hasOne(PresentacionProducto::className(), ['id_presentacion' => 'id_presentacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudArmadoKitsDetalles()
    {
        return $this->hasMany(SolicitudArmadoKitsDetalle::className(), ['id_solicitud_armado' => 'id_solicitud_armado']);
    }
    
    public function getProcesoCerrado() {
        if($this->proceso_cerrado == 0){
            $procesocerrado = 'NO';
        }else{
            $procesocerrado = 'SI';
        }
        return $procesocerrado;    
    }
    
    public function getAutorizadoProceso() {
        if($this->autorizado == 0){
            $autorizado = 'NO';
        }else{
            $autorizado = 'SI';
        }
        return $autorizado;    
    }
    public function getEntregaSolicitud() {
        if($this->entregado == 0){
            $entregadosolicitud = 'NO';
        }else{
            $entregadosolicitud = 'SI';
        }
        return $entregadosolicitud;    
    }
}
