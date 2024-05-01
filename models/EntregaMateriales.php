<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entrega_materiales".
 *
 * @property int $id_entrega
 * @property int $codigo
 * @property int $numero_entrega
 * @property int $unidades_solicitadas
 * @property string $fecha_despacho
 * @property string $fecha_hora_registro
 * @property string $user_name
 * @property int $autorizado
 * @property int $cerrar_solicitud
 * @property string $observacion
 *
 * @property SolicitudMateriales $codigo0
 * @property EntregaMaterialesDetalle[] $entregaMaterialesDetalles
 */
class EntregaMateriales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entrega_materiales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'numero_entrega', 'unidades_solicitadas', 'autorizado', 'cerrar_solicitud'], 'integer'],
            [['fecha_despacho', 'fecha_hora_registro'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['observacion'], 'string', 'max' => 100],
            [['codigo'], 'exist', 'skipOnError' => true, 'targetClass' => SolicitudMateriales::className(), 'targetAttribute' => ['codigo' => 'codigo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_entrega' => 'Id:',
            'codigo' => 'Codigo solicitud:',
            'numero_entrega' => 'Numero entrega:',
            'unidades_solicitadas' => 'Unidades solicitadas:',
            'fecha_despacho' => 'Fecha despacho:',
            'fecha_hora_registro' => 'Fecha hora registro:',
            'user_name' => 'User name:',
            'autorizado' => 'Autorizado:',
            'cerrar_solicitud' => 'Solicitud cerrada:',
            'observacion' => 'Observacion:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitud()
    {
        return $this->hasOne(SolicitudMateriales::className(), ['codigo' => 'codigo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntregaMaterialesDetalles()
    {
        return $this->hasMany(EntregaMaterialesDetalle::className(), ['id_entrega' => 'id_entrega']);
    }
       //proceso que agrupa varios campos
    public function getEntregaSolicitud()
    {
        return " Numero solicitud: {$this->solicitud->numero_solicitud} - Id: {$this->codigo}";
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
   
}
