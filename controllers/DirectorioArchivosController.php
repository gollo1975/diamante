<?php

namespace app\controllers;

use app\models\Directorio;
use app\models\Documentodir;
use app\models\DirectorioArchivos;
use Yii;

use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use yii\base\Model;
use yii\web\Response;
use yii\web\Session;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\UploadedFile;
use app\models\FormSubirArchivo;
use yii\web\Controller;
use yii\filters\VerbFilter;


class DirectorioArchivosController extends \yii\web\Controller
{

    public function actionIndex($codigo,$numero,$view, $token)
    {
        //if (!Yii::$app->user->isGuest) {

        $table = DirectorioArchivos::find()->where(['=','numero',$numero])->andWhere(['=','codigo',$codigo])->orderBy('idarchivodir DESC');
        $count = clone $table;
        $pages = new Pagination([
            'pageSize' => 6,
            'totalCount' => $count->count(),
        ]);
        $model = $table
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        $to = $count->count();
        return $this->render('index', [
            'model' => $model,
            'codigo' => $codigo,
            'numero' => $numero,
            'pagination' => $pages,
            'view' => $view,
            'token' => $token,
        ]);
    }
    //INDEX DEL NUVA CAFRGA DE IMAGEN
      public function actionIndex_archivo($codigo,$numero,$view_archivo, $token)
    {
        //if (!Yii::$app->user->isGuest) {

        $table = DirectorioArchivos::find()->where(['=','numero',$numero])->andWhere(['=','codigo',$codigo])->orderBy('idarchivodir DESC');
        $count = clone $table;
        $pages = new Pagination([
            'pageSize' => 6,
            'totalCount' => $count->count(),
        ]);
        $model = $table
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        $to = $count->count();
        return $this->render('index_archivo', [
            'model' => $model,
            'codigo' => $codigo,
            'numero' => $numero,
            'pagination' => $pages,
            'view_archivo' => $view_archivo,
            'token' => $token,
        ]);
    }
    
    //CARGAR IMAGENES PARA PUNTOS DE VENTA DEL MODULO DE INVENTARIOS
    public function actionIndex_imagen_punto($codigo,$numero, $validador_imagen, $token)
    {
        //if (!Yii::$app->user->isGuest) {

        $table = DirectorioArchivos::find()->where(['=','numero',$numero])->andWhere(['=','codigo',$codigo])->orderBy('idarchivodir DESC');
        $count = clone $table;
        $pages = new Pagination([
            'pageSize' => 6,
            'totalCount' => $count->count(),
        ]);
        $model = $table
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        $to = $count->count();
        return $this->render('index_imagen_punto', [
            'model' => $model,
            'codigo' => $codigo,
            'numero' => $numero,
            'pagination' => $pages,
            'validador_imagen' => $validador_imagen,
            'token' => $token,
        ]);
    }

    public function actionSubir($token)
    {
        $model = new FormSubirArchivo();
        $msg = null;        
        $codigo = Html::encode($_GET["codigo"]);
        $numero = Html::encode($_GET["numero"]);
        $view = Html::encode($_GET["view"]);
        $descripcion = '';
        $documentodir = Documentodir::findOne($numero);
        if ($model->load(Yii::$app->request->post()))
            {
            $model->file = UploadedFile::getInstances($model, 'file');
            $descripcion = $_POST['descripcion'];
            if ($model->file && $model->validate()) {
                $carpeta = 'Documentos/'.$model->numero.'/'.$model->codigo.'/';
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }
                foreach ($model->file as $file):
                    if(!file_exists($carpeta . $file->baseName . '.' . $file->extension)){
                        $file->saveAs($carpeta . $file->baseName . '.' . $file->extension);
                        $table = new DirectorioArchivos();
                        $table->nombre = $file->baseName.'.'.$file->extension;
                        $table->extension = $file->extension;
                        $table->tamaño = $file->size;
                        $table->tipo = $file->type;
                        $table->numero = $numero;
                        $table->descripcion = $descripcion;
                        $table->codigo = $codigo;
                        $table->iddocumentodir = $documentodir->iddocumentodir;
                        $table->iddirectorio = 1;
                        $table->save(false);
                       $this->redirect([$view."/view",'id' => $codigo, 'token' => $token]);
                    } else {
                        Yii::$app->getSession()->setFlash('warning', 'Ya existe el nombre y la extesion del archivo que desea subir');    
                    }  
                endforeach;
            }
        }
        if (Yii::$app->request->get("numero")) {
            $model->codigo = $codigo;
            $model->numero = $numero;
            $model->view = $view;
        }

        return $this->render("subir", ["model" => $model, "msg" => $msg,'view' => $view, 'token' => $token]);
    }
    
    //permite subir imagen de inventario producto terminado
    public function actionSubir_archivo($token)
    {
        $model = new FormSubirArchivo();
        $msg = null;        
        $codigo = Html::encode($_GET["codigo"]);
        $numero = Html::encode($_GET["numero"]);
        $view_archivo = Html::encode($_GET["view_archivo"]);
        $descripcion = '';
        $documentodir = Documentodir::findOne($numero);
        if ($model->load(Yii::$app->request->post()))
            {
            $model->file = UploadedFile::getInstances($model, 'file');
            $descripcion = $_POST['descripcion'];
            if ($model->file && $model->validate()) {
                $carpeta = 'Documentos/'.$model->numero.'/'.$model->codigo.'/';
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }
                foreach ($model->file as $file):
                    if(!file_exists($carpeta . $file->baseName . '.' . $file->extension)){
                        $file->saveAs($carpeta . $file->baseName . '.' . $file->extension);
                        $table = new DirectorioArchivos();
                        $table->nombre = $file->baseName.'.'.$file->extension;
                        $table->extension = $file->extension;
                        $table->tamaño = $file->size;
                        $table->tipo = $file->type;
                        $table->numero = $numero;
                        $table->descripcion = $descripcion;
                        $table->codigo = $codigo;
                        $table->iddocumentodir = $documentodir->iddocumentodir;
                        $table->iddirectorio = 1;
                        $table->save(false);
                       $this->redirect([$view_archivo."/view_archivo",'id' => $codigo, 'token' => $token]);
                    } else {
                        Yii::$app->getSession()->setFlash('warning', 'Ya existe el nombre y la extesion del archivo que desea subir');    
                    }  
                endforeach;
            }
        }
        if (Yii::$app->request->get("numero")) {
            $model->codigo = $codigo;
            $model->numero = $numero;
            $model->view_archivo = $view_archivo;
        }

        return $this->render("subir_archivo", ["model" => $model, "msg" => $msg,'view_archivo' => $view_archivo, 'token' => $token]);
    }
    
    //PROCESO QUE PERMITE SUBIR IMAGENES DEL INVENTARIO DE PUNTO DE VENTA
     public function actionSubir_archivo_puntoventa($token)
    {
        $model = new FormSubirArchivo();
        $msg = null;        
        $codigo = Html::encode($_GET["codigo"]);
        $numero = Html::encode($_GET["numero"]);
        $validador_imagen = Html::encode($_GET["validador_imagen"]);
        $descripcion = '';
        $documentodir = Documentodir::findOne($numero);
        if ($model->load(Yii::$app->request->post()))
            {
            $model->file = UploadedFile::getInstances($model, 'file');
            $descripcion = $_POST['descripcion'];
            if ($model->file && $model->validate()) {
                $carpeta = 'Documentos/'.$model->numero.'/'.$model->codigo.'/';
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }
                foreach ($model->file as $file):
                    if(!file_exists($carpeta . $file->baseName . '.' . $file->extension)){
                        $file->saveAs($carpeta . $file->baseName . '.' . $file->extension);
                        $table = new DirectorioArchivos();
                        $table->nombre = $file->baseName.'.'.$file->extension;
                        $table->extension = $file->extension;
                        $table->tamaño = $file->size;
                        $table->tipo = $file->type;
                        $table->numero = $numero;
                        $table->descripcion = $descripcion;
                        $table->codigo = $codigo;
                        $table->iddocumentodir = $documentodir->iddocumentodir;
                        $table->iddirectorio = 1;
                        $table->save(false);
                       $this->redirect([$validador_imagen."/validador_imagen",'id' => $codigo, 'token' => $token]);
                    } else {
                        Yii::$app->getSession()->setFlash('warning', 'Ya existe el nombre y la extesion del archivo que desea subir');    
                    }  
                endforeach;
            }
        }
        if (Yii::$app->request->get("numero")) {
            $model->codigo = $codigo;
            $model->numero = $numero;
            $model->validador_imagen = $validador_imagen;
        }

        return $this->render("subir_archivo_imagen", ["model" => $model, "msg" => $msg,'validador_imagen' => $validador_imagen, 'token' => $token]);
    }
    
    //DESCARGA EL ARCHIVO DEL GESTOR DOCUMENTAL
    public function actionDescargar($id,$numero,$codigo, $token)
    {

            $archivo = DirectorioArchivos::findOne($id);
            $directorio = Directorio::findOne($archivo->iddirectorio);
            $carpeta = 'Documentos/'.$numero.'/'.$codigo.'/';
            if (!$this->downloadFile($carpeta, $archivo->nombre, ["pdf", "docx","xlsx","jpeg", "jpg", "png"]))
            {
                //Mensaje flash para mostrar el error
                Yii::$app->getSession()->setFlash('error', 'Error en la descarga.');                
            }

        return $this->render('index', [
            'codigo' => $codigo,
            'numero' => $numero,
            'token' => $token,            
        ]);
    }
    
    public function actionEditar($token)
    {
        $idarchivodir = Html::encode($_POST["idarchivodir"]);
        $numero = Html::encode($_POST["numero"]);
        $codigo = Html::encode($_POST["codigo"]);
        $view = Html::encode($_POST["view"]);
        $token = Html::encode($_POST["token"]);
        if(Yii::$app->request->post()){
            if((int) $idarchivodir)
            {
                $table = DirectorioArchivos::findOne($idarchivodir);
                
                if ($table) {
                    $table->descripcion = Html::encode($_POST["descripcion"]);                                                                                
                    $table->update();                       
                    $this->redirect(["directorio-archivos/index",'numero' => $numero,'codigo' => $codigo,'view' => $view, 'token' => $token]); 
                                        
                } else {
                    $msg = "El registro seleccionado no ha sido encontrado";
                    $tipomsg = "danger";
                }
            }
        }
        
    }
    
    //editar contenido de inventario producto terminado
     public function actionEditar_archivo($token)
    {
        $idarchivodir = Html::encode($_POST["idarchivodir"]);
        $numero = Html::encode($_POST["numero"]);
        $codigo = Html::encode($_POST["codigo"]);
        $view_archivo = Html::encode($_POST["view_archivo"]);
        $token = Html::encode($_POST["token"]);
        if(Yii::$app->request->post()){
            if((int) $idarchivodir)
            {
                $table = DirectorioArchivos::findOne($idarchivodir);
                
                if ($table) {
                    $table->descripcion = Html::encode($_POST["descripcion"]);                                                                                
                    $table->update();                       
                    $this->redirect(["directorio-archivos/index_archivo",'numero' => $numero,'codigo' => $codigo,'view_archivo' => $view_archivo, 'token' => $token]); 
                                        
                } else {
                    $msg = "El registro seleccionado no ha sido encontrado";
                    $tipomsg = "danger";
                }
            }
        }
        
    }
    
    //editar contenido de inventario punto de venta
     public function actionEditar_imagen($token)
    {
        $idarchivodir = Html::encode($_POST["idarchivodir"]);
        $numero = Html::encode($_POST["numero"]);
        $codigo = Html::encode($_POST["codigo"]);
        $validador_imagen = Html::encode($_POST["validador_imagen"]);
        $token = Html::encode($_POST["token"]);
        if(Yii::$app->request->post()){
            if((int) $idarchivodir)
            {
                $table = DirectorioArchivos::findOne($idarchivodir);
                
                if ($table) {
                    $table->descripcion = Html::encode($_POST["descripcion"]);                                                                                
                    $table->update();                       
                    $this->redirect(["directorio-archivos/index_imagen_punto",'numero' => $numero,'codigo' => $codigo,'validador_imagen' => $validador_imagen, 'token' => $token]); 
                                        
                } else {
                    $msg = "El registro seleccionado no ha sido encontrado";
                    $tipomsg = "danger";
                }
            }
        }
        
    }
    
    public function actionBorrar($id,$numero,$codigo,$view, $token)
    {
        $archivo = DirectorioArchivos::findOne($id);
        $directorio = Directorio::findOne($archivo->iddirectorio);            
        if ($archivo)
        {
            $carpeta = $directorio->ruta.$numero.'/'.$codigo.'/';
            $ruta = $carpeta.$archivo->nombre;
            $archivo->delete();
            unlink($ruta);
            $this->redirect(["directorio-archivos/index",'numero' => $numero,'codigo' => $codigo,'view' => $view, 'token' => $token]);
        }
    }
    
    //borrar archivos
    public function actionBorrar_archivo($id,$numero,$codigo,$view_archivo, $token)
    {
        $archivo = DirectorioArchivos::findOne($id);
        $directorio = Directorio::findOne($archivo->iddirectorio);            
        if ($archivo)
        {
            $carpeta = $directorio->ruta.$numero.'/'.$codigo.'/';
            $ruta = $carpeta.$archivo->nombre;
            $archivo->delete();
            unlink($ruta);
            $this->redirect(["directorio-archivos/index_archivo",'numero' => $numero,'codigo' => $codigo,'view_archivo' => $view_archivo, 'token' => $token]);
        }
    }
    
    //borrar imagenes
    public function actionBorrar_imagenes($id,$numero,$codigo,$validador_imagen, $token)
    {
        $archivo = DirectorioArchivos::findOne($id);
        $directorio = Directorio::findOne($archivo->iddirectorio);            
        if ($archivo)
        {
            $carpeta = $directorio->ruta.$numero.'/'.$codigo.'/';
            $ruta = $carpeta.$archivo->nombre;
            $archivo->delete();
            unlink($ruta);
            $this->redirect(["directorio-archivos/index_imagen_punto",'numero' => $numero,'codigo' => $codigo,'validador_imagen' => $validador_imagen, 'token' => $token]);
        }
    }
    
    private function downloadFile($dir, $file, $extensions=[])
    {
        //Si el directorio existe
        if (is_dir($dir))
        {
            //Ruta absoluta del archivo
            $path = $dir.$file;

            //Si el archivo existe
            if (is_file($path))
            {
                //Obtener información del archivo
                $file_info = pathinfo($path);
                //Obtener la extensión del archivo
                $extension = $file_info["extension"];

                if (is_array($extensions))
                {
                    //Si el argumento $extensions es un array
                    //Comprobar las extensiones permitidas
                    foreach($extensions as $e)
                    {
                        //Si la extension es correcta
                        if ($e === $extension)
                        {
                            //Procedemos a descargar el archivo
                            // Definir headers
                            $size = filesize($path);
                            header("Content-Type: application/force-download");
                            header("Content-Disposition: attachment; filename=$file");
                            header("Content-Transfer-Encoding: binary");
                            header("Content-Length: " . $size);
                            // Descargar archivo
                            readfile($path);
                            //Correcto
                            return true;
                        }
                    }
                }
            }
        }
        //Ha ocurrido un error al descargar el archivo
        return false;
    }
}
