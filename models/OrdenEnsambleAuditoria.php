<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_ensamble_auditoria".
 *
 * @property int $id_auditoria
 * @property int $numero_auditoria
 * @property int $numero_orden
 * @property int $numero_lote
 * @property int $id_ensamble
 * @property int $id_etapa
 * @property string $etapa
 * @property int $id_grupo
 * @property int $id_forma
 * @property int $condiciones_analisis 1.Cuarentena, 2. Rechazo y 3. Aprobado
 * @property string $observacion
 * @property string $user_name
 * @property string $fecha_creacion
 * @property string $fecha_proceso
 * @property int $cerrar_auditoria
 *
 * @property OrdenEnsambleProducto $ensamble
 * @property EtapasAuditoria $etapa0
 * @property GrupoProducto $grupo
 * @property FormaCosmetica $forma
 * @property OrdenEnsambleAuditoriaDetalle[] $ordenEnsambleAuditoriaDetalles
 */
class OrdenEnsambleAuditoria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_ensamble_auditoria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_auditoria', 'numero_orden', 'numero_lote', 'id_ensamble', 'id_etapa', 'id_grupo', 'id_forma', 'condiciones_analisis', 'cerrar_auditoria'], 'integer'],
            [['fecha_proceso','fecha_analisis'], 'safe'],
            [['etapa'], 'string', 'max' => 30],
            [['observacion'], 'string', 'max' => 100],
            [['user_name'], 'string', 'max' => 15],
            [['id_ensamble'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenEnsambleProducto::className(), 'targetAttribute' => ['id_ensamble' => 'id_ensamble']],
            [['id_etapa'], 'exist', 'skipOnError' => true, 'targetClass' => EtapasAuditoria::className(), 'targetAttribute' => ['id_etapa' => 'id_etapa']],
            [['id_grupo'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoProducto::className(), 'targetAttribute' => ['id_grupo' => 'id_grupo']],
            [['id_forma'], 'exist', 'skipOnError' => true, 'targetClass' => FormaCosmetica::className(), 'targetAttribute' => ['id_forma' => 'id_forma']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_auditoria' => 'Id',
            'numero_auditoria' => 'Numero auditoria:',
            'numero_orden' => 'Numero orden ensamble:',
            'numero_lote' => 'Numero lote:',
            'id_ensamble' => 'Codigo ensamble:',
            'id_etapa' => 'Id Etapa',
            'etapa' => 'Etapa:',
            'id_grupo' => 'Grupo:',
            'id_forma' => 'Forma cosmetica:',
            'condiciones_analisis' => 'Condiciones de analisis:',
            'observacion' => 'Observacion:',
            'user_name' => 'User name:',
            'fecha_proceso' => 'Fecha proceso:',
            'cerrar_auditoria' => 'Cerrar auditoria:',
            'fecha_analisis' => 'Fecha analisis:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnsamble()
    {
        return $this->hasOne(OrdenEnsambleProducto::className(), ['id_ensamble' => 'id_ensamble']);
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
    public function getGrupo()
    {
        return $this->hasOne(GrupoProducto::className(), ['id_grupo' => 'id_grupo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForma()
    {
        return $this->hasOne(FormaCosmetica::className(), ['id_forma' => 'id_forma']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenEnsambleAuditoriaDetalles()
    {
        return $this->hasMany(OrdenEnsambleAuditoriaDetalle::className(), ['id_auditoria' => 'id_auditoria']);
    }
    public function getCondicionAnalisis() {
        if($this->condiciones_analisis == 0){
            $condicionanalisis ='Seleccionar';
        }else{
            if($this->condiciones_analisis == 1){
                $condicionanalisis = 'CUARENTENA';
            } else {
                 if($this->condiciones_analisis == 2){  
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
