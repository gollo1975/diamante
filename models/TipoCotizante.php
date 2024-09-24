<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_cotizante".
 *
 * @property int $id_tipo_cotizante
 * @property string $tipo
 * @property string $codigo_intefaz
 */
class TipoCotizante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_cotizante';
    }
    
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->tipo = strtoupper($this->tipo); 
        $this->codigo_interfaz = strtoupper($this->codigo_interfaz); 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo'], 'required'],
            [['tipo'], 'string', 'max' => 30],
            [['codigo_interfaz'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_cotizante' => 'Id',
            'tipo' => 'Tipo cotizante',
            'codigo_interfaz' => 'Codigo interfaz',
        ];
    }
}
