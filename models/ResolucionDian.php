<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resolucion_dian".
 *
 * @property int $id_resolucion
 * @property string $numero_resolucion
 * @property string $desde
 * @property string $hasta
 * @property string $fecha_vence
 * @property string $consecutivo
 * @property string $fecha_registro
 * @property string $user_name
 * @property int $estado_resolucion
 */
class ResolucionDian extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resolucion_dian';
    }
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->consecutivo = strtoupper($this->consecutivo); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_resolucion', 'desde', 'hasta', 'fecha_vence','id_documento'], 'required'],
            [['desde', 'hasta', 'fecha_vence', 'fecha_registro','fecha_aviso_vencimiento'], 'safe'],
            [['estado_resolucion','rango_inicio','rango_final','vigencia','codigo_interface','id_documento'], 'integer'],
            [['numero_resolucion'], 'string', 'max' => 30],
            [['consecutivo'], 'string', 'max' => 3],
            [['user_name'], 'string', 'max' => 15],
            [['id_documento'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentoElectronico::className(), 'targetAttribute' => ['id_documento' => 'id_documento']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_resolucion' => 'Id',
            'numero_resolucion' => 'Numero resolucion',
            'desde' => 'Fecha inicio',
            'hasta' => 'Fecha final',
            'fecha_vence' => 'Fecha vencimiento',
            'consecutivo' => 'Consecutivo',
            'fecha_registro' => 'Fecha Registro',
            'user_name' => 'User Name',
            'estado_resolucion' => 'Activo',
            'rango_inicio' => 'Rango inicio',
            'rango_final' => 'Rango final',
            'vigencia' => 'Vigencia',
            'id_documento' => 'Tipo documento',
            'codigo_interface' => 'Codigo interface',
            'fecha_aviso_vencimiento' => 'Fecha aviso vencimiento',
          
        ];
    }
    
     public function getDocumentosElectronicos()
    {
        return $this->hasOne(DocumentoElectronico::className(), ['id_documento' => 'id_documento']);
    }
    
    public function getActivo() {
        if ($this->estado_resolucion == 0){
            $estado = 'SI';
        }else{
            $estado = 'NO';
        }
        return $estado;
    }
}
