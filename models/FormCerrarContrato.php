<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Empleado;
use app\models\Contrato;

/**
 * ContactForm is the model behind the contact form.
 */
class FormCerrarContrato extends Model
{        
    public $fecha_final;
    public $id_contrato;
    public $id_motivo_terminacion;    
    public $observacion;
    public $generar_liquidacion;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_motivo_terminacion','fecha_final'], 'required', 'message' => 'Campo requerido'],
            [['id_contrato','generar_liquidacion'],'integer'],
            ['fecha_final', 'fecha_error'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [            
            'id_contrato' => 'Contrato',
            'id_motivo_terminacion' => 'Motivo Terminación',
            'fecha_final' => 'Fecha Retiro',            
            'observacion' => 'Observacion',
            'generar_liquidacion' => 'Generar_liquidacion:',
        ];
    }
    
    public function fecha_error($attribute, $params)
    {
        //Buscar la fecha en la tabla
        $table = Contratos::find()->where([">","fecha_inicio", $this->fecha_final])->andWhere(["=","id_contrato", $this->id_contrato]);
        //Si el email existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "La fecha inicio del contrato, no puede ser mayor a la fecha de retiro");
        }
    }
    
}
