<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "items".
 *
 * @property int $id_items
 * @property string $descripcion
 */
class Items extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'items';
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
            [['descripcion','id_iva','id_solicitud','codigo', 'id_medida'], 'required'],
            [['descripcion'], 'string', 'max' => 40],
            [['codigo','user_name'], 'string', 'max' => 15],
            [['fecha_hora'], 'safe'],
            [['id_iva','id_solicitud','id_medida' ,'convertir_gramo','codificar'],'integer'],
            [['id_iva'], 'exist', 'skipOnError' => true, 'targetClass' => ConfiguracionIva::className(), 'targetAttribute' => ['id_iva' => 'id_iva']],
            [['id_solicitud'], 'exist', 'skipOnError' => true, 'targetClass' => TipoSolicitud::className(), 'targetAttribute' => ['id_solicitud' => 'id_solicitud']],
            [['id_medida'], 'exist', 'skipOnError' => true, 'targetClass' => MedidaMateriaPrima::className(), 'targetAttribute' => ['id_medida' => 'id_medida']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_items' => 'Id',
            'id_iva' => 'Iva',
            'descripcion' => 'Descripcion',
            'id_solicitud' => 'Clasificacion',
            'id_medida' => 'Medida',
            'codigo' => 'Codigo insumo',
        ];
    }
    
    public function getIva()
    {
        return $this->hasOne(ConfiguracionIva::className(), ['id_iva' => 'id_iva']);
    }
    
    public function getTipoSolicitud()
    {
        return $this->hasOne(TipoSolicitud::className(), ['id_solicitud' => 'id_solicitud']);
    }
    
    public function getMedida()
    {
        return $this->hasOne(MedidaMateriaPrima::className(), ['id_medida' => 'id_medida']);
    }
}
