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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo_documento', 'documento'], 'required'],
            [['proceso_nomina', 'proceso_cliente', 'proceso_proveedor'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['tipo_documento', 'codigo_interfaz'], 'string', 'max' => 4],
            [['documento'], 'string', 'max' => 40],
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
            'codigo_interfaz' => 'Codigo Interfaz',
            'fecha_registro' => 'Fecha Registro',
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
