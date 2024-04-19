<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroBusquedaAnalisis extends Model
{        
   
    public $codigo;
    public $concepto;
    public $etapa;


    public function rules()
    {
        return [  
           [['codigo','etapa'], 'integer'],
           [['concepto'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'codigo' => 'Codigo:',
            'Concepto' => 'Concepto:',
            'etapa' => 'Etapa analisis:',

        ];
    }
    
}
