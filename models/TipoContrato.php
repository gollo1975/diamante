<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_contrato".
 *
 * @property int $id_tipo_contrato
 * @property string $contrato
 * @property int $prorroga
 * @property int $numero_prorrogas
 * @property string $prefijo
 * @property int $id_configuracion_prefijo
 * @property string $abreviatura
 * @property int $estado
 * @property string $user_name
 *
 * @property ConfiguracionFormatoPrefijo $configuracionPrefijo
 */
class TipoContrato extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_contrato';
    }
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->prorroga = strtoupper($this->prorroga); 
      
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contrato', 'prefijo'], 'required'],
            [['prorroga', 'numero_prorrogas', 'id_configuracion_prefijo', 'estado'], 'integer'],
            [['contrato'], 'string', 'max' => 100],
            [['prefijo'], 'string', 'max' => 3],
            [['abreviatura','codigo_api_enlace'], 'string', 'max' => 10],
            [['user_name'], 'string', 'max' => 15],
            [['id_configuracion_prefijo'], 'exist', 'skipOnError' => true, 'targetClass' => ConfiguracionFormatoPrefijo::className(), 'targetAttribute' => ['id_configuracion_prefijo' => 'id_configuracion_prefijo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_contrato' => 'Codigo',
            'contrato' => 'Tipo de contrato',
            'prorroga' => 'Prorroga',
            'numero_prorrogas' => 'Numero de prorrogas',
            'prefijo' => 'Prefijo',
            'id_configuracion_prefijo' => 'Tipo de formato',
            'abreviatura' => 'Abreviatura',
            'estado' => 'Activo',
            'user_name' => 'User Name',
            'codigo_api_enlace' => 'Codigo api',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfiguracionPrefijo()
    {
        return $this->hasOne(ConfiguracionFormatoPrefijo::className(), ['id_configuracion_prefijo' => 'id_configuracion_prefijo']);
    }
     /**
     * @return \yii\db\ActiveQuery
     */
     public function getFormatoContenidos()
    {
        return $this->hasone(FormatoContenido::className(), ['id_configuracion_prefijo' => 'id_configuracion_prefijo']);
    }
    
     public function getContratos()
    {
        return $this->hasMany(Contrato::className(), ['id_tipo_contrato' => 'id_tipo_contrato']);
    }
    
      public function getProrrogacontrato()
    {
        if($this->prorroga == 0){
            $prorroga = "NO";
        }else{
            $prorroga = "SI";
        }
        return $prorroga;
    }
    
    public function getActivo()
    {
        if($this->estado == 0){
            $estado = "SI";
        }else{
            $estado = "NO";
        }
        return $estado;
    }
}
