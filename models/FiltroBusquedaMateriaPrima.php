<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaMateriaPrima extends Model
{        
   
    public $codigo;
    public $materia_prima;
    public $fecha_inicio;
    public $fecha_corte;
    public $medida;
    public $codigo_barra;
    public $aplica_inventario;
    public $busqueda_vcto;
    public $tipo_solicitud;
    
    public function rules()
    {
        return [  
           [['busqueda_vcto', 'medida','aplica_inventario','tipo_solicitud'], 'integer'],
           [['fecha_inicio','fecha_corte'], 'safe'],
           [['codigo','codigo_barra','materia_prima'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'busqueda_vcto' => 'Busqueda x Vcto:',
            'medida' => 'Medida:',
            'aplica_inventario' => 'Aplica inventario:',
            'codigo_barra' => 'Codigo barra:',
            'materia_prima' => 'Materia prima:',
            'codigo' => 'Codigo:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
            'tipo_solicitud' => 'Clasificacion',
            
       
        ];
    }
    
}
