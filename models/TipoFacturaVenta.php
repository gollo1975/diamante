<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_factura_venta".
 *
 * @property int $id_tipo_factura
 * @property string $descripcion
 * @property string $user_name
 * @property string $fecha_registro
 */
class TipoFacturaVenta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_factura_venta';
    }
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->descripcion = strtoupper($this->descripcion); 
 
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['fecha_registro'], 'safe'],
            [['descripcion'], 'string', 'max' => 35],
            [['user_name','abreviatura'], 'string', 'max' => 15],
            [['porcentaje_retencion','porcentaje_mora'], 'number'],
            [['base_retencion','aplica_interes_mora','ver_registro_factura','codigo_interface','documento_libre'], 'integer'],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_factura' => 'Codigo',
            'descripcion' => 'Tipo documento',
            'user_name' => 'User Name',
            'fecha_registro' => 'Fecha registro',
            'porcentaje_retencion' => '% Retencion',
            'base_retencion' => 'Base retencion',
            'porcentaje_mora' => 'Porcentaje de mora',
            'aplica_interes_mora' => 'Aplica interes mora',
            'codigo_interface' => 'Codigo interface',
            'abreviatura' => 'abreviatura',
            'ver_registro_factura' => 'Ver registro factura',
            'documento_libre' => 'Documento libre',
        ];
    }
    
    public function getAplicaInteres() {
       if($this->aplica_interes_mora == 0){
           $aplicainteres = 'NO';
       }else{
           $aplicainteres = 'SI';
       }
       return $aplicainteres;
    }
    
     public function getVerRegistro() {
       if($this->ver_registro_factura == 0){
           $verregistro = 'NO';
       }else{
           $verregistro = 'SI';
       }
       return $verregistro;
    }
     public function getDocumentoLibre() {
       if($this->documento_libre == 0){
           $documentolibre = 'NO';
       }else{
           $documentolibre = 'SI';
       }
       return $documentolibre;
    }
}
