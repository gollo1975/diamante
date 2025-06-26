<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaKits extends Model
{        
   
    public $solicitud;
    public $presentacion;
    public $fecha_inicio;
    public $fecha_corte;

    public function rules()
    {
        return [  
           [['solicitud','presentacion'], 'integer'],
           [['fecha_inicio','fecha_corte'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'solicitud' => 'Tipo de solicitud:',
            'presentacion' => 'Presentacion del producto:',
            'fecha_inicio' => 'Fecha inicio:',
            'fecha_corte' => 'Fecha corte:',
        ];
    }
    
}