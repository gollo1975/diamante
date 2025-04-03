<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormDeduccionCredito extends Model
{        
    public $id;
    public $id_credito;
     public $deduccion;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            
            [['deduccion'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [            
            'id_credito' => 'Nro credito:',
            'deduccion' => 'Deducción:',
           
        ];
    }
    
}
