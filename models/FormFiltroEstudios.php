<?php

namespace app\models;

use Yii;
use yii\base\Model;


/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroEstudios extends Model
{
    public $id_empleado;
    public $documento;
    public $id_tipo_estudio;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empleado', 'documento','id_tipo_estudio'], 'integer'],
            ['documento', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_empleado' => 'Empleado:',
            'documento' => 'Documento:',
            'id_tipo_estudio' => 'Tipo estudio:',
            
        ];
    }
     
    
}
