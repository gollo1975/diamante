<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaEntradaMateria extends Model
{        
   
    public $id_entrada;
    public $fecha_inicio;
    public $fecha_corte;
    public $proveedor;
    public $tipo_entrada;
    public $orden;
    public function rules()
    {
        return [  
           [['id_entrada','proveedor','tipo_entrada','orden'], 'integer'],
           [['fecha_inicio','fecha_corte'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'id_entrada' => 'Numero entrada:',
            'proveedor' => 'Proveedor:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
            'orden' => 'Tipo de orden:',
            'tipo_entrada' => 'Tipo entrada:',
            
       
        ];
    }
    
}