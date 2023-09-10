<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "solicitud_compra".
 *
 * @property int $id_solicitud_compra
 * @property int $id_solicitud
 * @property int $id_area
 * @property string $documento_soporte
 * @property string $fecha_creacion
 * @property string $fecha_entrega
 * @property string $observacion
 * @property int $user_name
 *
 * @property TipoSolicitud $solicitud
 * @property AreaEmpresa $area
 * @property SolicitudCompraDetalles[] $solicitudCompraDetalles
 */
class SolicitudCompra extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'solicitud_compra';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_solicitud', 'id_area', 'fecha_entrega'], 'required'],
            [['id_solicitud', 'id_area','subtotal','total_impuesto','total','numero_solicitud','autorizado','importado'], 'integer'],
            [['fecha_creacion', 'fecha_entrega'], 'safe'],
            [['observacion'], 'string'],
            [['documento_soporte','user_name'], 'string', 'max' => 15],
            [['id_solicitud'], 'exist', 'skipOnError' => true, 'targetClass' => TipoSolicitud::className(), 'targetAttribute' => ['id_solicitud' => 'id_solicitud']],
            [['id_area'], 'exist', 'skipOnError' => true, 'targetClass' => AreaEmpresa::className(), 'targetAttribute' => ['id_area' => 'id_area']],
            [['id_solicitud_compra'], 'exist', 'skipOnError' => true, 'targetClass' => SolicitudCompra::className(), 'targetAttribute' => ['id_solicitud_compra' => 'id_solicitud_compra']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_solicitud_compra' => 'Id:',
            'id_solicitud' => 'Solicitud:',
            'id_area' => 'Area:',
            'documento_soporte' => 'Soporte:',
            'fecha_creacion' => 'Fecha Creacion',
            'fecha_entrega' => 'Fecha proceso:',
            'observacion' => 'Observacion:',
            'user_name' => 'User name:',
            'subtotal' => 'Subtotal:',
            'total_impuesto' => 'Impuesto:',
            'total'=> 'Total:',
            'numero_solicitud' => 'Numero:',
            'autorizado' => 'Autorizado:',
            'fecha_creacion' => 'Fecha creación:',
            'importado' => 'Importado:',
        ];
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
    public function getArea()
    {
        return $this->hasOne(AreaEmpresa::className(), ['id_area' => 'id_area']);
    }
    
    public function geSolicitudCompra()
    {
        return $this->hasOne(SolicitudCompra::className(), ['id_solicitud_compra' => 'id_solicitud_compra']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudCompraDetalles()
    {
        return $this->hasMany(SolicitudCompraDetalles::className(), ['id_solicitud_compra' => 'id_solicitud_compra']);
    }
    
    public function getAutorizadoCompra(){
        if($this->autorizado == 0){
           $autorizadocompra = 'NO'; 
        }else{
           $autorizadocompra = 'SI';  
        }
        return $autorizadocompra;
    }
    public function getImportarSolicitud(){
        if($this->importado == 0){
           $importarsolicitud = 'NO'; 
        }else{
           $importarsolicitud = 'SI';  
        }
        return $importarsolicitud;
    }
}
