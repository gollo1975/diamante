<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "indicador_comercial".
 *
 * @property int $id_indicador
 * @property string $fecha_inicio
 * @property string $fecha_cierre
 * @property int $anocierre
 * @property string $fecha_registro
 * @property string $user_name
 * @property int $total_citas
 * @property int $total_citas_reales
 * @property int $total_citas_no_reales
 * @property int $total_porcentaje
 */
class IndicadorComercial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'indicador_comercial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_inicio', 'fecha_cierre', 'anocierre'], 'required'],
            [['fecha_inicio', 'fecha_cierre', 'fecha_registro'], 'safe'],
            [['anocierre', 'total_citas', 'total_citas_reales', 'total_citas_no_reales', 'total_porcentaje','total_registros'], 'integer'],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_indicador' => 'Id:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_cierre' => 'Fecha cierre:',
            'anocierre' => 'Año:',
            'fecha_registro' => 'Fecha registro:',
            'user_name' => 'User name:',
            'total_citas' => 'Total citas:',
            'total_citas_reales' => 'Total citas reales:',
            'total_citas_no_reales' => 'Total citas No reales:',
            'total_porcentaje' => 'Total porcentaje:',
            'total_registros' => 'Total registros:',
        ];
    }
    
     public function getGestion()
    {
        return $this->hasMany(AgentesComerciales::className(), ['id_agente' => 'id_agente']);
    }
    
    public function getIndicadorCerrado() {
        if($this->estado_indicador == 0){
            $indicadorcerrado = 'NO';
        }else{
            $indicadorcerrado = 'SI';
        }
        return $indicadorcerrado;
    }
}
