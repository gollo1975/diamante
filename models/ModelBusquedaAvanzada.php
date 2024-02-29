<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ModelBusquedaAvanzada extends Model
{        
   
    
    public $desde;
    public $hasta;
    public $busqueda;


    public function rules()
    {
        return [  
          
            [['busqueda'], 'integer'],
            [['desde', 'hasta'],'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'busqueda' => 'Variable de busqueda:',
            'desde' => 'Desde:',
            'hasta' => 'Hasta:',
        ];
    }
    
}
