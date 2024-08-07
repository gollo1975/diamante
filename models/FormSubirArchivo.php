<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Archivodir;
use yii\web\UploadedFile;


class FormSubirArchivo extends Model
{
    public $file;
    public $numero;
    public $codigo;
    public $view;
    public $view_archivo;
    public $validador_imagen;
    
    public function rules()
    {
        return [
             ['numero', 'default'],
            ['codigo', 'string'],
            ['view_archivo', 'default'],
            ['validador_imagen', 'default'],
            ['view', 'default'],
            ['file', 'file',
            'skipOnEmpty' => false,
            'uploadRequired' => 'Debe de seleccionar al menos un acrhivo.',    
            'extensions' => 'pdf,docx,jpeg,jpg,xlsx,png',            
            'wrongExtension' => 'El archivo no contiene una extension permitida.',
            'maxFiles' => 4,
            'tooMany' => 'El maximo de archivos permito son (4)',
        ],
     ];           
    }

    public function attributeLabels()
    {
        return [
            'file' => 'Selecciona el archivo:', 
            'numero' => '',
            'codigo' => '',
            'view' => '',
            'view_archivo' => '',
            'validador_imagen' => '',
        ];
    }

 /*   public function upload()
    {
        if ($this->validate()) {
            $carpeta = 'Documentos/'.$this->numero.'/'.$this->codigo.'/';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            if(!file_exists($carpeta . $this->imageFile->baseName . '.' . $this->imageFile->extension)){
                $this->imageFile->saveAs($carpeta . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
            }else{
                return false;
            }
            
        } else {
            return false;
        }
    }*/
}