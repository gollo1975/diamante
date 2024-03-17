<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "listado_requisitos".
 *
 * @property int $id_requisito
 * @property string $concepto
 */
class ListadoRequisitos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'listado_requisitos';
    }
    

    /**
    /**
     * {@inheritdoc}
     */
    
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->concepto = strtoupper($this->concepto); 
 
        return true;
    }
    public function rules()
    {
        return [
            [['concepto'], 'required'],
            [['concepto'], 'string', 'max' => 40],
            [['porcentaje'], 'number'],
             [['aplica_proveedor','aplica_requisito'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_requisito' => 'Codigo:',
            'concepto' => 'Nombre del requisito:',
            'porcentaje' => 'Porcentaje',
            'aplica_proveedor' => 'Aplica proveedor:',
            'aplica_requisito' => 'Aplica requisito:',
            
        ];
    }
    
    public function getAplicaProveedor() {
        if($this->aplica_proveedor == 0){
              $aplicaproveedor = 'NO';
        }else{
            $aplicaproveedor = 'SI';
        }
        return $aplicaproveedor;
    }
    
    public function getAplicaRequisito() {
        if($this->aplica_requisito == 0){
              $aplicarequisito = 'NO';
        }else{
            $aplicarequisito = 'SI';
        }
        return $aplicarequisito;
    }
}
