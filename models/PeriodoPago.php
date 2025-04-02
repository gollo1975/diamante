<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "periodo_pago".
 *
 * @property int $id_periodo_pago
 * @property string $nombre_periodo
 * @property int $dias
 * @property int $limite_horas
 * @property int $continua
 */
class PeriodoPago extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'periodo_pago';
    }
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->nombre_periodo = strtoupper($this->nombre_periodo); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_periodo', 'dias', 'limite_horas'], 'required'],
            [['dias', 'limite_horas', 'continua'], 'integer'],
            [['nombre_periodo','codigo_api_nomina'], 'string', 'max' => 20],
            ['periodo_mes' ,'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_periodo_pago' => 'Id',
            'nombre_periodo' => 'Nombre del eriodo',
            'dias' => 'Dias',
            'limite_horas' => 'Limite horas',
            'continua' => 'Continua',
            'periodo_mes' => 'periodo_mes',
            'codigo_api_nomina' => 'Codigo api_nomina',
        ];
    }
    
    public function getContinuaP() {
        if($this->continua == 0){
            $continuap = 'NO';
        }else{
            $continuap = 'SI';
        }
        return $continuap;    
    } 
}
