<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormConceptoSalario extends Model
{
    public $codigo;
    public $concepto;
    public $prestacional;
    public $agrupado;
    public $debito_credito;
    


    public function rules()
    {
        return [

            [['codigo', 'prestacional','agrupado','debito_credito'], 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            [['concepto'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'codigo' => 'Codigo de nomina:',
            'prestacional' => 'Genera prestaciones:',
            'agrupado' =>'Tipo de grupo:',
            'debito_credito' => 'Debito o credito:',
            'concepto' => 'Nombre del concepto:',
        ];
    }
}