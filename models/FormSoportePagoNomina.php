<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormSoportePagoNomina extends Model
{        
    public $id_programacion;
    public $id_empleado;
    public $id_contrato;
    public $cedula_empleado;
    public $salario_contrato;
    public $fecha_desde;
    public $fecha_hasta;
    public $nro_pago;
    public $dias_pago;
    public $dia_real_pagado;
    public $total_devengado;
    public $total_deduccion;
    public $total_pagar;
    public $id_grupo_pago;
    public $fecha_inicio_contrato;
    public $id_periodo_pago_nomina;
    public $salario_promedio;
    public $tipo_salario;
    public $usuariosistema;
    public $fecha_creacion;
    public $dias_ausentes;
    public $id_detalle;
    public $promedio;



    public function rules()
    {
        return [  
           
        ];
    }

    public function attributeLabels()
    {
        return [   
            'id_programacion' => 'Id programación:',
            'id_empleado' => 'Nombre:',
            'cedula_empleado' => 'Documento:',
            'salario_contrato' => 'Salario:',
            'id_contrato ' => 'Contrato:',
            'fecha_desde' => 'Fecha inicio:',
            'fecha_hasta' => 'Fecha final:',
            'nro_pago' => 'Nro Pago:',
            'dias_pago' => 'Dias periodo:',
            'dia_real_pagado' => 'Dias reales:',
            'total_devengado' => 'Total devengado:',
            'total_deduccion' => 'Total _deducción:',
            'total_pagar' => 'Neto a pagar:',
            'id_grupo_pago' => 'Grupo pago:',
            'fecha_inicio_contrato' => 'Inicio contrato:',
            'id_periodo_pago_nomina' => 'Nro periodo:',
            'salario_promedio' => 'Salario promedio:',
            'fecha_creacion' => 'Fecha creacion:',
            'promedio' => 'Promedio:',
            
      
        ];
    }
    
    
}
