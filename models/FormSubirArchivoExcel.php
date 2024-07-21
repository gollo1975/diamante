<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;



class FormSubirArchivoExcel extends Model
{
    public $fileProgramacion;
    public $filename;
    public $path;
    
    public function rules()
    {
        return [
            
           [['fileProgramacion'], 'file',
            'skipOnEmpty' => false,
            'uploadRequired' => 'Debe de seleccionar al menos un archivo para importar.',    
            'extensions' => 'xlsx, xls',            
            'wrongExtension' => 'El archivo no contiene una extension permitida.',
            'maxFiles' => 1,
            'tooMany' => 'El maximo de archivos permito son (1)',
            ],
           
        ];           
    }

    public function attributeLabels()
    {
        return [
            'fileProgramacion' => 'Selecciona el archivo:', 
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) {
            // backend/web/files
            $this->path ='../web/files';
            $this->filename = $this->fileProgramacion->name;
            $this->fileProgramacion->saveAs($this->path . DIRECTORY_SEPARATOR . $this->filename);
            return true;
        } else {
            return false;
        }
    }
    
    public function getFullPath()
    {
        return $this->path. DIRECTORY_SEPARATOR. $this->filename;
    }
    
/*     public function upload()
    {
        if ($this->validate()) {
            $this->fileProgramacion->saveAs('uploads/' . $this->fileProgramacion->baseName . '.' . $this->fileProgramacion->extension);
            return true;
        } else {
            return false;
        }
    }*/

}