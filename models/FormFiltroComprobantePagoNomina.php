<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroComprobantePagoNomina extends Model
{
    public $id_grupo_pago;
    public $id_tipo_nomina;
    public $id_empleado;
    public $cedula_empleado;
    public $fecha_desde;
    public $fecha_hasta;
    public $anio;




    public function rules()
    {
        return [

            [['id_grupo_pago', 'id_tipo_nomina', 'id_empleado','cedula_empleado','anio'], 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            [['fecha_desde','fecha_hasta'],'safe'],
           
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_grupo_pago' => 'Grupo:',
            'id_tipo_nomina' => 'Tipo pago:',
            'id_empleado' =>'Empleado:', 
            'cedula_empleado' =>'Documento:',
            'fecha_desde' => 'Desde',
            'fecha_hasta' => 'Hasta',
            'anio' => 'Año:',
           
        ];
    }
}