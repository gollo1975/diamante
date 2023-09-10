<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "programacion_citas".
 *
 * @property int $id_programacion
 * @property int $id_agente
 * @property string $fecha_inicio
 * @property string $fecha_final
 * @property string $fecha_registro
 * @property int $total_citas
 * @property string $user_name
 *
 * @property AgentesComerciales $agente
 */
class ProgramacionCitas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'programacion_citas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_agente', 'total_citas','proceso_cerrado','visitas_cumplidas', 'visitas_no_cumplidas', 'porcentaje_eficiencia'], 'integer'],
            [['fecha_inicio', 'fecha_final'], 'required'],
            [['fecha_inicio', 'fecha_final', 'fecha_registro'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['id_agente'], 'exist', 'skipOnError' => true, 'targetClass' => AgentesComerciales::className(), 'targetAttribute' => ['id_agente' => 'id_agente']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_programacion' => 'Id:',
            'id_agente' => 'Agente comercial:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_final' => 'Fecha final:',
            'fecha_registro' => 'Fecha registro:',
            'total_citas' => 'Número citas:',
            'user_name' => 'User name:',
            'proceso_cerrado' => 'Proceso cerrado:',
            'porcentaje_eficiencia' => 'Eficiencia:',
            'visitas_no_cumplidas' => 'Visitas no cumplidas:',
            'visitas_cumplidas' => 'Visitas cumplidas',
            
            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgente()
    {
        return $this->hasOne(AgentesComerciales::className(), ['id_agente' => 'id_agente']);
    }
    public function getProcesoCerrado() {

       if($this->proceso_cerrado == 0){
             $procesocerrado = 'NO';
        }else{
             $procesocerrado = 'SI';
        }
        return $procesocerrado;
    }      
}
