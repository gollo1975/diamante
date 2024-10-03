<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormIncapacidad extends Model
{        
   
    public  $id_incapacidad;
    public $codigo_incapacidad;
    public $id_empleado;
    public $id_codigo;
    public $numero_incapacidad;
    public $nombre_medico;
    public $fecha_inicio ;
    public $fecha_final;
    public $fecha_documento_fisico;
    public $fecha_aplicacion;
    public $transcripcion;
    public $cobrar_administradora;
    public $aplicar_adicional;
    public $pagar_empleado;
    public $prorroga;
    public $observacion;
   

    public function rules()
    {
        return [            
           [['codigo_incapacidad', 'id_empleado', 'id_codigo', 'numero_incapacidad', 'fecha_inicio', 'fecha_final', 'fecha_documento_fisico', 'fecha_aplicacion'], 'required'],
           [['codigo_incapacidad', 'id_empleado', 'id_codigo', 'numero_incapacidad', 'transcripcion', 'cobrar_administradora','aplicar_adicional','pagar_empleado', 'prorroga','numero_incapacidad'], 'integer'],
            [['fecha_inicio', 'fecha_final','fecha_documento_fisico','fecha_aplicacion'], 'safe'],
           [['nombre_medico'], 'string', 'max' => 50],
            [['observacion'], 'string', 'max' => 100],
        
        ];
    }

    public function attributeLabels()
    {
        return [   
            'id_incapacidad' => 'id_incapacidad',
            'codigo_incapacidad' => 'Tipo Incapacidad',
            'id_empleado' => 'Empleado(a)',
            'id_codigo' => 'Codigo',
            'codigo_diagnostico'=>'Codigo diagnóstico',
            'nombre_medico' => 'Nombre Medico',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_final' => 'Fecha Final',
            'pagar_empleado' => 'Pagar Empleado',
            'prorroga' => 'Prorroga',
            'fecha_documento_fisico'=> 'Fecha_documento_fisico',
            'fecha_aplicacion'=> 'Fecha_aplicacion',
            'transcripcion' =>'Transcripcion',
            'cobrar_administradora'=> 'Cobrar_administradora',
            'aplicar_adicional' => 'Aplicar_adicional',
             'observacion' => 'Observacion',
        ];
    }
    
}
