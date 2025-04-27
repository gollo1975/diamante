<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_orden_compra".
 *
 * @property int $id_tipo_orden
 * @property string $descripcion_orden
 */
class TipoOrdenCompra extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_orden_compra';
    }
    
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->descripcion_orden = strtoupper($this->descripcion_orden); 
        $this->abreviatura = strtoupper($this->abreviatura); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion_orden','tipo_modulo','id_solicitud'], 'required'],
            [['descripcion_orden'], 'string', 'max' => 30],
             [['abreviatura'], 'string', 'max' => 3],
            [['tipo_modulo','id_solicitud'], 'integer'],
             [['id_solicitud'], 'exist', 'skipOnError' => true, 'targetClass' => TipoSolicitud::className(), 'targetAttribute' => ['id_solicitud' => 'id_solicitud']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_orden' => 'Código',
            'descripcion_orden' => 'Descripción',
            'abreviatura' => 'Abreviatura',
            'tipo_modulo' => 'Tipo de modulo:',
            'id_solicitud' => 'Solicitud',
        ];
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitud()
    {
        return $this->hasOne(TipoSolicitud::className(), ['id_solicitud' => 'id_solicitud']);
    }
    
    public function getTipoModulo() {
        if($this->tipo_modulo == 1){
            $tipomodulo = 'PRODUCCION';
        }else{
            $tipomodulo = 'INVENTARIOS';
        }
        return $tipomodulo;
    }
}
