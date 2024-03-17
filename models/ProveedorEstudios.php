<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proveedor_estudios".
 *
 * @property int $id_estudio
 * @property int $id_tipo_documento
 * @property string $nit_cedula
 * @property int $dv
 * @property string $primer_nombre
 * @property string $segundo_nombre
 * @property string $primer_apellido
 * @property string $segundo_apellido
 * @property string $razon_social
 * @property string $nombre_completo
 *
 * @property TipoDocumento $tipoDocumento
 */
class ProveedorEstudios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proveedor_estudios';
    }
    
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->razon_social = strtoupper($this->razon_social);
        $this->primer_nombre = strtoupper($this->primer_nombre);
        $this->segundo_nombre = strtoupper($this->segundo_nombre);
        $this->primer_apellido = strtoupper($this->primer_apellido);
        $this->segundo_apellido = strtoupper($this->segundo_apellido);
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tipo_documento', 'nit_cedula', 'dv'], 'required'],
            [['id_tipo_documento', 'dv','validado','aprobado','proceso_cerrado'], 'integer'],
            [['nit_cedula','user_name'], 'string', 'max' => 15],
            [['total_porcentaje'],'number'],
            [['observacion'], 'string'],
            ['fecha_registro', 'safe'],
            [['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido'], 'string', 'max' => 12],
            [['razon_social', 'nombre_completo'], 'string', 'max' => 50],
            [['id_tipo_documento'], 'exist', 'skipOnError' => true, 'targetClass' => TipoDocumento::className(), 'targetAttribute' => ['id_tipo_documento' => 'id_tipo_documento']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_estudio' => 'Id Estudio',
            'id_tipo_documento' => 'Tipo documento:',
            'nit_cedula' => 'Nit/Cedula:',
            'dv' => 'Dv:',
            'primer_nombre' => 'Primer nombre:',
            'segundo_nombre' => 'Segundo nombre:',
            'primer_apellido' => 'Primer apellido:',
            'segundo_apellido' => 'Segundo apellido:',
            'razon_social' => 'Razon social:',
            'nombre_completo' => 'Nombre Completo',
            'validado' => 'Validado:',
            'aprobado' => 'Aprobado:',
            'total_porcentaje' => 'Total porcentaje:',
            'observacion' => 'Observacion:',
            'user_name' => 'Usuario:',
            'fecha_registro'=> 'Fecha registro:',
            'proceso_cerrado' => 'Proceso cerrado:',
             
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoDocumento()
    {
        return $this->hasOne(TipoDocumento::className(), ['id_tipo_documento' => 'id_tipo_documento']);
    }
    
   public function getValidadoEstudio() {
        if($this->validado == 0 ){
            $alidadoestudio = 'NO';
        }else{
            $alidadoestudio = 'SI';
        }
        return $alidadoestudio;
    }
    
    public function getAprobadoEstudio() {
        if($this->aprobado == 0 ){
            $aprobadoestudio = 'NO';
        }else{
            $aprobadoestudio = 'SI';
        }
        return $aprobadoestudio;
    }
    public function getProcesoCerrado() {
        if($this->proceso_cerrado == 0 ){
            $procesocerrado = 'NO';
        }else{
            $procesocerrado = 'SI';
        }
        return $procesocerrado;
    }
}
