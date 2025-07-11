<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entrega_solicitud_kits".
 *
 * @property int $id_entrega_kits
 * @property int $id_solicitud
 * @property int $id_presentacion
 * @property int $id_solicitud_armado
 * @property int $total_unidades_entregadas
 * @property string $fecha_solicitud
 * @property string $fecha_hora_proceso
 * @property int $proceso_cerrado
 * @property int $autorizado
 * @property int $numero_entrega
 * @property string $observacion
 * @property int $cantidad_despachada
 * @property string $user_name
 *
 * @property DocumentoSolicitudes $solicitud
 * @property PresentacionProducto $presentacion
 * @property SolicitudArmadoKits $solicitudArmado
 * @property EntregaSolicitudKitsDetalle[] $entregaSolicitudKitsDetalles
 */
class EntregaSolicitudKits extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entrega_solicitud_kits';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_solicitud', 'cantidad_despachada'], 'required'],
            [['id_solicitud', 'id_presentacion', 'id_solicitud_armado', 'total_unidades_entregadas', 'proceso_cerrado', 'autorizado', 'numero_entrega', 'cantidad_despachada',
                'solicitud_generada','cantidad_despachada_saldo','producto_armado'], 'integer'],
            [['fecha_solicitud', 'fecha_hora_proceso','fecha_hora_cierre'], 'safe'],
            [['observacion'], 'string', 'max' => 100],
            [['user_name'], 'string', 'max' => 15],
            [['id_solicitud'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentoSolicitudes::className(), 'targetAttribute' => ['id_solicitud' => 'id_solicitud']],
            [['id_presentacion'], 'exist', 'skipOnError' => true, 'targetClass' => PresentacionProducto::className(), 'targetAttribute' => ['id_presentacion' => 'id_presentacion']],
            [['id_solicitud_armado'], 'exist', 'skipOnError' => true, 'targetClass' => SolicitudArmadoKits::className(), 'targetAttribute' => ['id_solicitud_armado' => 'id_solicitud_armado']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_entrega_kits' => 'Id ',
            'id_solicitud' => 'Tipo de solicitud:',
            'id_presentacion' => 'Presentacion:',
            'id_solicitud_armado' => 'Id Solicitud Armado',
            'total_unidades_entregadas' => 'Unidades entregadas:',
            'fecha_solicitud' => 'Fecha Solicitud',
            'fecha_hora_proceso' => 'Fecha Hora Proceso',
            'proceso_cerrado' => 'Proceso Cerrado',
            'autorizado' => 'Autorizado',
            'numero_entrega' => 'Numero Entrega',
            'observacion' => 'Observacion',
            'cantidad_despachada' => 'Cantidad Despachada',
            'user_name' => 'User Name',
            'fecha_hora_cierre' => 'fecha_hora_cierre',
            'solicitud_generada' => 'solicitud_generada',
            'cantidad_despachada_saldo' => 'cantidad_despachada_saldo',
            'producto_armado' => 'producto_armado',
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
    public function getSolicitudArmado()
    {
        return $this->hasOne(SolicitudArmadoKits::className(), ['id_solicitud_armado' => 'id_solicitud_armado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntregaSolicitudKitsDetalles()
    {
        return $this->hasMany(EntregaSolicitudKitsDetalle::className(), ['id_entrega_kits' => 'id_entrega_kits']);
    }
    
     //proceso que agrupa varios campos de la solicitud del kits
    public function getEntregaKits()
    {
        return " Numero orden: {$this->numero_entrega} - Nombre de Kits: {$this->presentacion->descripcion}";
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
    
    public function getProductoArmado() {
        if($this->producto_armado == 0){
            $productoarmado = 'NO';
        }else{
            $productoarmado = 'SI';
        }
        return $productoarmado;    
    }
}
