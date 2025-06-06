<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_documento".
 *
 * @property int $id_tipo_documento
 * @property string $tipo_documento
 * @property string $documento
 * @property int $proceso_nomina
 * @property int $proceso_cliente
 * @property int $proceso_proveedor
 * @property string $codigo_interfaz
 * @property string $fecha_registro
 *
 * @property Proveedor[] $proveedors
 */
class TipoDocumento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_documento';
    }
    
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->tipo_documento = strtoupper($this->tipo_documento); 
         $this->documento = strtoupper($this->documento); 
        $this->codigo_interfaz = strtoupper($this->codigo_interfaz); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo_documento', 'documento'], 'required'],
            [['proceso_nomina', 'proceso_cliente', 'proceso_proveedor','codigo_api'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['tipo_documento', 'codigo_interfaz'], 'string', 'max' => 4],
            [['documento','codigo_interface_nomina'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_documento' => 'Id',
            'tipo_documento' => 'Tipo documento',
            'documento' => 'Documento',
            'proceso_nomina' => 'Proceso nomina',
            'proceso_cliente' => 'Proceso cliente',
            'proceso_proveedor' => 'Proceso proveedor',
            'codigo_interfaz' => 'Codigo banco',
            'fecha_registro' => 'Fecha Registro',
            'codigo_api' => 'Codigo_DS',
            'codigo_interface_nomina' => 'Codigo api nomina',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProveedors()
    {
        return $this->hasMany(Proveedor::className(), ['id_tipo_documento' => 'id_tipo_documento']);
    }
    
    public function getProcesoNomina() {
        if($this->proceso_nomina == 0){
             $procesonomina = 'NO';           
        }else{
            $procesonomina = 'SI';
        }
        return $procesonomina;
    }
    public function getProcesoCliente() {
        if($this->proceso_cliente == 0){
             $procesocliente = 'NO';           
        }else{
            $procesocliente = 'SI';
        }
        return $procesocliente;
    }
    public function getProcesoProveedor() {
        if($this->proceso_proveedor == 0){
             $procesoproveedor = 'NO';           
        }else{
            $procesoproveedor = 'SI';
        }
        return $procesoproveedor;
    }
}
