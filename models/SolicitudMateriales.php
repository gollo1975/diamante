<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "solicitud_materiales".
 *
 * @property int $codigo
 * @property int $id_orden_produccion
 * @property int $id_solicitud
 * @property int $unidades
 * @property int $numero_lote
 * @property int $numero_orden_produccion
 * @property string $fecha_hora_cierre
 * @property string $fecha_hora_registro
 * @property string $user_name
 *
 * @property OrdenProduccion $ordenProduccion
 * @property TipoSolicitud $solicitud
 * @property SolicitudMaterialesDetalle[] $solicitudMaterialesDetalles
 */
class SolicitudMateriales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'solicitud_materiales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_orden_produccion', 'id_solicitud'], 'required'],
            [['id_orden_produccion', 'id_solicitud', 'unidades',  'numero_orden_produccion','id_grupo','numero_solicitud','autorizado',
            'cerrar_solicitud','id_producto','aplica_todo'], 'integer'],
            [['observacion'], 'string', 'max' => 100],
            [['fecha_cierre', 'fecha_hora_registro'], 'safe'],
            [['user_name','numero_lote'], 'string', 'max' => 15],
            [['id_orden_produccion'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenProduccion::className(), 'targetAttribute' => ['id_orden_produccion' => 'id_orden_produccion']],
            [['id_solicitud'], 'exist', 'skipOnError' => true, 'targetClass' => TipoSolicitud::className(), 'targetAttribute' => ['id_solicitud' => 'id_solicitud']],
            [['id_grupo'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoProducto::className(), 'targetAttribute' => ['id_grupo' => 'id_grupo']],
            [['id_producto'], 'exist', 'skipOnError' => true, 'targetClass' => Productos::className(), 'targetAttribute' => ['id_producto' => 'id_producto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigo' => 'Codigo',
            'id_orden_produccion' => 'Orden de produccion',
            'id_solicitud' => 'Tipo de material:',
            'numero_solicitud' => 'Numero solicitud:',
            'unidades' => 'Unidades:',
            'numero_lote' => 'Numero lote:',
            'numero_orden_produccion' => 'Numero orden produccion:',
            'fecha_cierre' => 'Fecha cierre:',
            'fecha_hora_registro' => 'Fecha Hora Registro:',
            'user_name' => 'User Name',
            'id_grupo' => 'Grupo:',
            'cerrar_solicitud' => 'Cerrado:',
            'autorizado' => 'Autorizado:',
            'observacion' => 'Observacion:',
            'id_producto' => 'Producto:',
            'aplica_todo' => 'Aplica todo:',
            
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
    public function getGrupo()
    {
        return $this->hasOne(GrupoProducto::className(), ['id_grupo' => 'id_grupo']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductos()
    {
        return $this->hasOne(Productos::className(), ['id_producto' => 'id_producto']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitud()
    {
        return $this->hasOne(TipoSolicitud::className(), ['id_solicitud' => 'id_solicitud']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudMaterialesDetalles()
    {
        return $this->hasMany(SolicitudMaterialesDetalle::className(), ['codigo' => 'codigo']);
    }
    
    public function getCerrarSolicitud() {
        if($this->cerrar_solicitud == 0){
            $cerrarsolicitud = 'NO';
        }else{
            $cerrarsolicitud = 'SI';
        }
        return $cerrarsolicitud;
    }
    
    public function getAutorizadoSolicitud() {
        if($this->autorizado == 0){
            $autorizadosolicitud = 'NO';
        }else{
            $autorizadosolicitud = 'SI';
        }
        return $autorizadosolicitud;
    }
    
    public function getDespachadoSolicitud() {
        if($this->despachado == 0){
            $despachadosolicitud = 'NO';
        }else{
            $despachadosolicitud = 'SI';
        }
        return $despachadosolicitud;
    }
   
}
