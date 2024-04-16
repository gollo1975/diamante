<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_produccion_auditoria_fabricacion".
 *
 * @property int $id_auditoria
 * @property int $id_orden_produccion
 * @property int $id_etapa
 * @property string $etapa
 * @property string $observacion
 * @property int $continua
 * @property int $condicion_analisis 1.Cuarentena, 2. Rechazo y 3. Aprobado
 * @property string $user_name
 * @property string $fecha_proceso
 *
 * @property OrdenProduccion $ordenProduccion
 * @property EtapasAuditoria $etapa0
 * @property OrdenProduccionAuditoriaFabricacionDetalle[] $ordenProduccionAuditoriaFabricacionDetalles
 */
class OrdenProduccionAuditoriaFabricacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_produccion_auditoria_fabricacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_orden_produccion', 'id_etapa', 'continua', 'condicion_analisis','numero_auditoria','numero_orden','numero_lote','cerrar_auditoria'], 'integer'],
            [['fecha_proceso','fecha_creacion'], 'safe'],
            [['etapa'], 'string', 'max' => 30],
            [['observacion'], 'string', 'max' => 100],
            [['user_name'], 'string', 'max' => 15],
            [['id_orden_produccion'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenProduccion::className(), 'targetAttribute' => ['id_orden_produccion' => 'id_orden_produccion']],
            [['id_etapa'], 'exist', 'skipOnError' => true, 'targetClass' => EtapasAuditoria::className(), 'targetAttribute' => ['id_etapa' => 'id_etapa']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_auditoria' => 'Id',
            'id_orden_produccion' => 'Codigo de produccion:',
            'id_etapa' => 'Codigo:',
            'etapa' => 'Nombre de etapa:',
            'observacion' => 'Observacion:',
            'continua' => 'Continua:',
            'condicion_analisis' => 'Condicion de analisis:',
            'user_name' => 'User Name',
            'fecha_proceso' => 'Fecha Proceso',
            'numero_auditoria' => 'Numero auditoria:',
            'numero_orden' => 'Numero orden produccion:',
            'numero_lote' => 'Numero lote:',
            'fecha_creacion' => 'Fecha creacion:',
            'cerrar_auditoria' => 'Cerrado:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenProduccion()
    {
        return $this->hasOne(OrdenProduccion::className(), ['id_orden_produccion' => 'id_orden_produccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEtapaProceso()
    {
        return $this->hasOne(EtapasAuditoria::className(), ['id_etapa' => 'id_etapa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenProduccionAuditoriaFabricacionDetalles()
    {
        return $this->hasMany(OrdenProduccionAuditoriaFabricacionDetalle::className(), ['id_auditoria' => 'id_auditoria']);
    }
    
    public function getContinuaProceso() {
        if($this->continua == 1){
            $continuaproceso ='SI';
        }else{
            $continuaproceso = 'NO';
        }
        return $continuaproceso;
    }
    
    public function getCondicionAnalisis() {
        if($this->condicion_analisis == 0){
            $condicionanalisis ='Seleccionar';
        }else{
            if($this->condicion_analisis == 1){
                $condicionanalisis = 'CUARENTENA';
            } else {
                 if($this->condicion_analisis == 2){  
                     $condicionanalisis = 'RECHAZO';
                 } else {
                     $condicionanalisis = 'APROBADO';
                 }
            }    
        }
        return $condicionanalisis;
    }
    public function getCerrarAuditoria() {
        if($this->cerrar_auditoria == 0){
            $cerrarauditoria = 'NO';
        }else{
            $cerrarauditoria = 'SI';
        }
        return $cerrarauditoria;
    }
}
