<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroPrestaciones extends Model
{
    public $id_grupo_pago;
    public $id_empleado;
    public $documento;
    public $desde;
    public $hasta;


    public function rules()
    {
        return [

            ['documento', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            [['id_empleado', 'id_grupo_pago'], 'integer'],
            [['desde', 'hasta'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'documento' => 'Documento',
            'id_grupo_pago' => 'Grupo pago:',
            'id_empleado' => 'Empleado:',
            'desde' => 'Fecha de inicio',
            'hasta' => 'Fecha hasta',
          
        ];
    }
}
