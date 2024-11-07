<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;
use yii\web\Response;
use yii\web\Session;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use Codeception\Lib\HelperModule;
use yii\db\Expression;
use yii\db\Query;
use yii\db\Command;
use yii\db\ActiveQuery;
//models
//models

use app\models\InventarioPuntoVenta;
use app\models\UsuarioDetalle;
use app\models\FacturaVentaPunto;
use app\models\FacturaVentaPuntoDetalle;


/**
 * InventarioPuntoVentaController implements the CRUD actions for InventarioPuntoVenta model.
 */
class InventarioPuntoVentaController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all InventarioPuntoVenta models.
     * @return mixed
     */
    public function actionIndex($token = 0) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',145])->all()){
                $form = new \app\models\FiltroBusquedaInventarioPunto();
                $codigo = null;
                $inventario_inicial = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $punto_venta = null;
                $producto = null;
                $conPunto = \app\models\PuntoVenta::find()->orderBy('predeterminado DESC')->all();
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $inventario_inicial = Html::encode($form->inventario_inicial);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $producto = Html::encode($form->producto);
                        $punto_venta = Html::encode($form->punto_venta);
                        $table = InventarioPuntoVenta::find()
                                    ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['like', 'nombre_producto', $producto])
                                    ->andFilterWhere(['=', 'inventario_inicial', $inventario_inicial])
                                    ->andFilterWhere(['=', 'id_punto', $punto_venta]);
                        $table = $table->orderBy('id_inventario DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 15,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['id_inventario  DESC']);
                            $this->actionExcelInventarioPuntoVenta($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = InventarioPuntoVenta::find() ->orderBy('id_inventario DESC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if (isset($_POST['excel'])) {
                        $this->actionExcelInventarioPuntoVenta($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('index', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'token' => $token,
                            'conPunto' => ArrayHelper::map($conPunto, 'id_punto', 'nombre_punto'),
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }
    
    //CONSULTAR INVENTARIOS
     public function actionSearch_inventario($token = 1) {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',101])->all()){
                $form = new \app\models\FiltroBusquedaInventarioPunto();
                $codigo = null;
                $proveedor = null;
                $fecha_inicio = null;
                $fecha_corte = null;
                $punto_venta = null;
                $producto = null;
                $tokenAcceso = Yii::$app->user->identity->id_punto;
                $conPunto = \app\models\PuntoVenta::find()->orderBy('id_punto ASC')->all();
                $local = \app\models\PuntoVenta::findOne($tokenAcceso);
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $proveedor = Html::encode($form->proveedor);
                        $fecha_inicio = Html::encode($form->fecha_inicio);
                        $fecha_corte = Html::encode($form->fecha_corte);
                        $producto = Html::encode($form->producto);
                        $punto_venta = Html::encode($form->punto_venta);
                        if($tokenAcceso == 1){
                            $table = InventarioPuntoVenta::find()
                                    ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                    ->andFilterWhere(['between', 'fecha_proceso', $fecha_inicio, $fecha_corte])
                                    ->andFilterWhere(['like', 'nombre_producto', $producto])
                                    ->andFilterWhere(['=', 'id_proveedor', $proveedor])
                                    ->andFilterWhere(['=', 'id_punto', $punto_venta]);
                        }else{
                            $table = InventarioPuntoVenta::find()
                                    ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                    ->andFilterWhere(['like', 'nombre_producto', $producto])
                                    ->andWhere(['=', 'id_punto', $tokenAcceso]);
                        }
                        $table = $table->orderBy('id_inventario DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 15,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
                        if (isset($_POST['excel'])) {
                            $check = isset($_REQUEST['id_inventario  DESC']);
                            $this->actionExcelInventarioPuntoVenta($tableexcel);
                        }
                    } else {
                        $form->getErrors();
                    }
                } else {
                    if($tokenAcceso == 1){
                        $table = InventarioPuntoVenta::find()->orderBy('id_inventario DESC');
                    }else{
                        $table = InventarioPuntoVenta::find()->andWhere(['=','id_punto', $tokenAcceso])->orderBy('id_inventario DESC');
                    }
                    
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    if (isset($_POST['excel'])) {
                        $this->actionExcelInventarioPuntoVenta($tableexcel);
                    }
                }
                $to = $count->count();
                return $this->render('search_inventario', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'tokenAcceso' => $tokenAcceso,
                            'token' => $token,
                            'conPunto' => ArrayHelper::map($conPunto, 'id_punto', 'nombre_punto'),
                            'local' => $local,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }
    
    //PERMITE BUSCAR POR REFERENCIAS
    
    public function actionSearch_referencias($token = 2) {
         if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',102])->all()){
                $form = new \app\models\ModeloEntradaProducto();
                $codigo_producto = 0;
                $model = null;
                $tokenAcceso = Yii::$app->user->identity->id_punto;
                if ($form->load(Yii::$app->request->get())) {
                    $codigo_producto = Html::encode($form->codigo_producto);
                    if ($codigo_producto > 0) {
                        $table = \app\models\InventarioPuntoVenta::find()->Where(['=','codigo_producto', $codigo_producto])->all();
                        if(count($table) > 0){
                            $model = $table;
                        }else{
                             Yii::$app->getSession()->setFlash('info', 'El código del producto que digito NO se encuentra en el sistema.');
                             return $this->redirect(['search_referencias']);
                        }
                    }else{
                        Yii::$app->getSession()->setFlash('warning', 'Debe digitar codigo del producto a buscar.');
                        return $this->redirect(['search_referencias']);
                    }    
                } 
            }else{
                return $this->redirect(['site/sinpermiso']); 
            }   
         }else{
             return $this->redirect(['site/login']);
         }    

        return $this->render('search_referencias', [
           'form'=> $form,
           'model' => $model,  
           'tokenAcceso' => $tokenAcceso,
           'token' => $token,
        ]);
        
    }
    
    //TRASLADO ENTRE PUNTOS DE VENA
      public function actionTraslado_producto() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',107])->all()){
                $form = new \app\models\FiltroBusquedaInventarioPunto();
                $codigo = null;
                $inventario_inicial = null;
                $punto_venta = null;
                $marca = null;
                $producto = null;
                $conPunto = \app\models\PuntoVenta::find()->andWhere(['>','id_punto', 1])->orderBy('predeterminado DESC')->all();
                $conMarca = \app\models\Marca::find()->orderBy('marca DESC')->all();
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $inventario_inicial = Html::encode($form->inventario_inicial);
                        $producto = Html::encode($form->producto);
                        $punto_venta = Html::encode($form->punto_venta);
                        $marca = Html::encode($form->marca);
                        $table = InventarioPuntoVenta::find()
                                    ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                    ->andFilterWhere(['like', 'nombre_producto', $producto])
                                    ->andFilterWhere(['=', 'id_punto', $punto_venta])
                                    ->andFilterWhere(['=', 'id_marca', $marca])
                                    ->andWhere(['>', 'stock_inventario', 0])
                                    ->andWhere(['>','id_punto', 1]);
                        $table = $table->orderBy('id_inventario DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 15,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = InventarioPuntoVenta::find()->Where(['>', 'stock_inventario', 0])
                                                         ->andWhere(['>','id_punto', 1])->orderBy('id_inventario DESC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                }
                $to = $count->count();
                return $this->render('traslado_punto_venta', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'conPunto' => ArrayHelper::map($conPunto, 'id_punto', 'nombre_punto'),
                            'conMarca' => ArrayHelper::map($conMarca, 'id_marca', 'marca'),
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }     
   
    //PRODUCTO MAS VENDID
      public function actionProducto_masvendido() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',107])->all()){
                $form = new \app\models\FiltroBusquedaInventarioPunto();
                $cantidad_mostrar = null;
                $listado = null;
                $conPunto = \app\models\PuntoVenta::find()->orderBy('predeterminado DESC')->all();
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $cantidad_mostrar = Html::encode($form->cantidad_mostrar);
                        if($cantidad_mostrar <> null){
                            $query = (new Query())
                                       ->select('inventario_punto_venta.id_inventario, inventario_punto_venta.codigo_producto, inventario_punto_venta.nombre_producto,
                                                SUM(factura_venta_punto_detalle.cantidad) AS cantidad, factura_venta_punto_detalle.fecha_inicio, punto_venta.nombre_punto AS punto,
                                                proveedor.nombre_completo AS proveedor')
                                       ->from('factura_venta_punto_detalle, punto_venta, proveedor')
                                       ->innerJoin('inventario_punto_venta')
                                       ->where('factura_venta_punto_detalle.id_inventario = inventario_punto_venta.id_inventario')
                                       ->andWhere('inventario_punto_venta.id_proveedor = proveedor.id_proveedor')
                                       ->andWhere('factura_venta_punto_detalle.id_punto = punto_venta.id_punto')
                                       ->groupBy('inventario_punto_venta.id_inventario')
                                       ->orderBy('SUM(factura_venta_punto_detalle.cantidad) DESC')
                                       ->limit($cantidad_mostrar); 
                                       $command = $query->createCommand();
                                       $rows = $command->queryAll();   
                                       $listado = $rows;
                        }else{
                            Yii::$app->getSession()->setFlash('warning', 'Debe de seleccionar la cantidad de registro a mostrar.');
                           
                        }               
                    } else {
                        $form->getErrors();
                    }
                } 
                return $this->render('producto_masvendido', [
                            'listado' => $listado,
                            'form' => $form,
                            'conPunto' => ArrayHelper::map($conPunto, 'id_punto', 'nombre_punto'),
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }  
    /**
     * Displays a single InventarioPuntoVenta model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token, $codigo)
    {
        
        $talla_color = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id])->all();
        $talla_color_cerrado= \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id])->andWhere(['=','cerrado', 1])->all();
        if($codigo == 0){
            $traslado = \app\models\TrasladoReferenciaPunto::find()->where(['=','id_inventario_saliente', $id])->all();
        }else{
           $traslado = \app\models\TrasladoReferenciaPunto::find()->where(['=','id_inventario_entrante', $id])->all(); 
        }    
        if(isset($_POST["actualizarlineas"])){
            if(isset($_POST["entrada_cantidad"])){
                $intIndice = 0;
                foreach ($_POST["entrada_cantidad"] as $intCodigo):
                    $detalle = \app\models\DetalleColorTalla::find()->where(['=','id_detalle', $intCodigo])->andWhere(['=','cerrado', 0])->one();
                    if($detalle){
                        if($_POST["cantidad"]["$intIndice"] > 0){
                            $table = \app\models\DetalleColorTalla::findOne($intCodigo);
                            if($codigo <> 0){
                                $unidad_entrada = $_POST["cantidad"][$intIndice]; //asigno variable
                                $inventario = InventarioPuntoVenta::findOne($codigo);
                                if($unidad_entrada <= $inventario->stock_inventario){ //si hay stoxk
                                    $detalle->cantidad = $unidad_entrada;
                                    $detalle->stock_punto = $unidad_entrada;
                                    $detalle->save(false);
                                    $intIndice++;
                                }else{
                                    $intIndice++;
                                }
                            }else{    
                                $detalle->cantidad = $_POST["cantidad"][$intIndice];
                                $detalle->stock_punto = $_POST["cantidad"][$intIndice];
                                $detalle->save(false);
                                $intIndice++;
                            } 
                        }else{    
                           $intIndice++; 
                        }    
                    }else{
                        $intIndice++;
                    }   
                endforeach;
                    $this->ActualizarLineas($id);
                    $this->ActualizarTotalesProducto($id);
                return $this->redirect(['view','id' =>$id, 'token' => $token, 'codigo' => $codigo]);
            }
            
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'token' => $token,
            'talla_color' => $talla_color,
            'talla_color_cerrado' => $talla_color_cerrado,
            'codigo' => $codigo, 
            'traslado' => $traslado,            
        ]);
    }
    
    //VISTA DE BUSQUEDA DE INVENTIO
    public function actionView_search($token, $id, $tokenAcceso) {
        $talla_color = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id])->all();
        $entrada_detalle = \app\models\EntradaProductoInventarioDetalle::find()->where(['=','id_inventario', $id])->all();
        $item = \app\models\Documentodir::findOne(18);
        $inventario = InventarioPuntoVenta::findOne($id);
        $id_inventario = $inventario->codigo_enlace_bodega; //asigna el codigo de enlace
        if($id_inventario){
             $imagenes = \app\models\DirectorioArchivos::find()->where(['=', 'codigo', $id_inventario])->andWhere(['=', 'numero', $item->codigodocumento])->all();
        }else{
           $imagenes = \app\models\DirectorioArchivos::find()->where(['=', 'codigo', $id])->andWhere(['=', 'numero', $item->codigodocumento])->all();
        }        
       
        return $this->render('view_search', [
            'model' => $this->findModel($id),
            'token' => $token,
            'talla_color' => $talla_color,
            'tokenAcceso' => $tokenAcceso,
            'entrada_detalle' => $entrada_detalle,
            'imagenes' => $imagenes,
        ]);
        
    }
    

     // VISTA  DESCUENTOS COMERCIALES
    public function actionView_descuentos_comerciales($id, $id_punto){
        $model = InventarioPuntoVenta::findOne($id);
        $regla_punto = \app\models\DescuentoPuntoVenta::find()->where(['=','id_inventario', $id])->all();
        $regla_distribuidor = \app\models\DescuentoDistribuidor::find()->where(['=','id_inventario', $id])->all();
        return $this->render('view_descuentos_comerciales_puntos', [
                            'model' => $model,
                            'regla_punto' => $regla_punto,
                            'regla_distribuidor' => $regla_distribuidor,
                            'id' => $id,
                            'id_punto' => $id_punto, 
        ]);
    }
    
    //VISTA DE TRASLADO DE PRODUCO ENTRE SUCURSALES
    public function actionView_traslado($id, $id_punto, $sw) {
        $talla_color = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id])->all();
        $conPunto = \app\models\PuntoVenta::find()->andWhere(['<>','id_punto', 1])->orderBy('nombre_punto DESC')->all();
        $asignacion = \app\models\TrasladoReferenciaPunto::find()->where(['=','id_inventario_entrante', $id])->orderBy('id_traslado DESC')->all();
        $inventario = \app\models\InventarioPuntoVenta::findOne($id);
        if($sw == 0){
            if(isset($_POST["enviar_traslado_punto"])){
                if(isset($_POST["nuevo_traslado_punto"])){
                    $intIndice = 0;
                    $unidad_entrada = 0;
                    foreach ($_POST["nuevo_traslado_punto"] as $intCodigo):
                        $unidad_entrada = $_POST["cantidad_trasladar"][$intIndice]; //asigno variable
                        if($unidad_entrada > 0){
                            $Busqueda = \app\models\DetalleColorTalla::find()->where(['=','id_detalle', $intCodigo])->one();
             
                            if($Busqueda->codigo_producto == $inventario->codigo_producto && $id_punto == $_POST["nuevo_punto"][$intIndice]){
                                if($unidad_entrada <= $Busqueda->stock_punto){
                                    $table = new \app\models\TrasladoReferenciaPunto();
                                    $table->id_inventario_saliente = $id;
                                    $table->id_punto_saliente = $id_punto;
                                    $table->id_talla = $Busqueda->id_talla;
                                    $table->id_color = $Busqueda->id_color;
                                    $table->id_punto_entrante =  $_POST["nuevo_punto"][$intIndice];
                                    $table->unidades = $unidad_entrada;
                                    $table->fecha_proceso = date('Y-m-d');
                                    $table->user_name = Yii::$app->user->identity->username;
                                  //  $table->save();
                                    $intIndice++; 
                                }else{
                                   Yii::$app->session->setFlash('info', 'Las unidades a trasladar son mayores que el STOCK que en el punto de venta saliente.');
                                    $intIndice++;
                                }
                            
                            }else{
                              Yii::$app->session->setFlash('error', 'Este producto no esta creado en este punto de venta. Valide la informacion.');
                               $intIndice++;
                              // return $this->redirect(['view_traslado','id' =>$id, 'id_punto' => $id_punto,'sw' => $sw]);
                            }    
                        }else{
                           $intIndice++; 
                        } 
                    endforeach;
                  //  return $this->redirect(['view_traslado','id' =>$id, 'id_punto' => $id_punto,'sw' => $sw]);
                }
            } 
            return $this->render('view_traslado_producto', [
                            'model' => $this->findModel($id),
                            'talla_color' => $talla_color,
                            'id_punto' => $id_punto,
                            'conPunto' => ArrayHelper::map($conPunto, 'id_punto', 'nombrePunto'),
                            'asignacion'=> $asignacion,
                            'sw' => $sw,
                ]);
        }else{ ///PERMITE TRASLADAR LOS PRODUCTOS DE UN PUNTO DE VENTA A OTRO NO APLICA TALLAS
            $form = new \app\models\FiltroBusquedaInventarioPunto();
            $unidades = null;
            $punto_venta = null;
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {
                    $unidades = Html::encode($form->unidades);
                    $punto_venta = Html::encode($form->punto_venta);
                    if($unidades <> null && $punto_venta <> null){
                        $conInven = InventarioPuntoVenta::findOne($id);
                        if($unidades <= $conInven->stock_inventario){
                            $conExistencia = InventarioPuntoVenta::find()->where(['=','codigo_producto', $inventario->codigo_producto])->andWhere(['=','id_punto', $punto_venta])->one();
                            if($conExistencia){
                                if($punto_venta <> $id_punto){
                                    $table = new \app\models\TrasladoReferenciaPunto();
                                    $table->id_inventario_saliente = $conExistencia->id_inventario;
                                    $table->id_inventario_entrante = $id;
                                    $table->id_punto_saliente = $punto_venta;
                                    $table->id_punto_entrante = $id_punto;
                                    $table->unidades = $unidades;
                                    $table->fecha_proceso = date('Y-m-d');
                                    $table->user_name = Yii::$app->user->identity->username;
                                    $table->save();
                                    return $this->redirect(['view_traslado','id' =>$id, 'id_punto' => $id_punto,'sw'=> $sw]);
                                }else{
                                    Yii::$app->session->setFlash('info', 'El punto de venta seleccionado es IGUAL al punto de venta del PRODUCTO actual. Favor seleccionar otro punrto de venta.');
                                    return $this->redirect(['view_traslado','id' =>$id, 'id_punto' => $id_punto,'sw'=> $sw]); 
                                }    
                            }else{
                                Yii::$app->session->setFlash('error', 'El punto de venta SELECCIONADO no tiene creado este PRODUCTO en el inventario. Validar con el administrador.');
                                return $this->redirect(['view_traslado','id' =>$id, 'id_punto' => $id_punto,'sw'=> $sw]);  
                            }    
                       }else{
                           Yii::$app->session->setFlash('warning', 'Las existencias de estas referencia son MENORES que la cantidad a trasladar. Favor valide la informacion.');
                           return $this->redirect(['view_traslado','id' =>$id, 'id_punto' => $id_punto,'sw'=> $sw]);
                       }
                    }else{
                        Yii::$app->session->setFlash('error', 'Campos vacios. Favor seleccionar el PUNTO DE VENTA y las CANTIDADES a trasladar.');
                        return $this->redirect(['view_traslado','id' =>$id, 'id_punto' => $id_punto,'sw'=> $sw]);
                    }
                }else{
                  $form->getErrors();
                }   
            }    
            return $this->render('view_traslado_producto', [
                            'model' => $this->findModel($id),
                            'talla_color' => $talla_color,
                            'form' => $form,
                            'id_punto' => $id_punto,
                            'conPunto' => ArrayHelper::map($conPunto, 'id_punto', 'nombrePunto'),
                            'asignacion'=> $asignacion,
                            'sw' => $sw,
                ]); 
        }    
        
                           
    }
    
    //BUSCAR PUNTOS DE VENTA PARA TRASLADAR REFERENCIAS
     //BUSCA PRODUCTO DEL INVENTARIO PARA REPROGRAMARLO
    public function actionBuscar_punto_venta($id, $id_punto, $sw){
        $codigo = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id])->one();
        $form = new \app\models\FormModeloBuscar();
        $punto = null;
        $operacion = null;
        $pages = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $punto= Html::encode($form->punto); 
                if($punto <> null){
                    if ($punto <> $id_punto){
                        $operacion = \app\models\DetalleColorTalla::find()
                                ->where(['=','id_punto',$punto])
                                ->andWhere(['>','stock_punto', 0])
                                ->andWhere(['=','codigo_producto', $codigo->codigo_producto]);
                        $operacion = $operacion->orderBy('id_punto DESC');                    
                        $count = clone $operacion;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 10,
                            'totalCount' => $count->count()
                        ]);
                        $operacion = $operacion
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all(); 
                    }else{
                        Yii::$app->session->setFlash('warning', 'El punto saliente es IGUAL al punto de venta entrante. Selecciona un nuevo punto de venta.'); 
                        return $this->redirect(['buscar_punto_venta','id' => $id, 'id_punto' => $id_punto, 'sw' => $sw]);
                    }    
                }else{
                     Yii::$app->session->setFlash('error', 'Debe se seleccionar el punto de venta donde se va a trasladar la referencia.'); 
                      return $this->redirect(['buscar_punto_venta','id' => $id, 'id_punto' => $id_punto, 'sw' => $sw]);
                }    
            } else {
                $form->getErrors();
            }                    
        }
        //PROCESO DE GUARDAR
         if (isset($_POST["traslado_punto_venta"])) {
            if(isset($_POST["nuevo_traslado"])){
                $intIndice = 0;
                foreach ($_POST["nuevo_traslado"] as $intCodigo) {
                    $registro = \app\models\DetalleColorTalla::find()->where(['=','id_detalle', $intCodigo])->one();
                    $color =$registro->id_color;
                    $ConpuntoVenta = \app\models\PuntoVenta::findOne($id_punto);
                    $buscarRegistro = \app\models\DetalleColorTalla::find()->where(['=','id_punto', $id_punto])->all();
                    foreach ($buscarRegistro as $buscar):
                        
                        if($registro->codigo_producto == $buscar->codigo_producto && $registro->id_color = $buscar->id_color
                                && $registro->id_talla ==  $buscar->id_talla){
                            $table = new \app\models\TrasladoReferenciaPunto();
                            $table->id_inventario_saliente = $registro->id_inventario;
                            $table->id_inventario_entrante = $id;
                            $table->id_punto_entrante =  $id_punto;
                            $table->id_punto_saliente = $punto;
                            $table->id_talla = $registro->id_talla;
                            $table->id_color = $color;
                            $table->id_detalle = $intCodigo;
                            $table->fecha_proceso = date('Y-m-d');
                            $table->user_name = Yii::$app->user->identity->username;
                            $table->save(false);
                        }else{
                           Yii::$app->session->setFlash('error', 'La  TALLA Y COLOR seleccionada no se encuentra codificada en el local de ('.$ConpuntoVenta->nombre_punto.'). Favor valide la información');  
                        }
                    endforeach;
                }
                return $this->redirect(['view_traslado','id' => $id, 'id_punto' => $id_punto, 'sw' => $sw]);
            }
        }
        return $this->render('importar_referencia_punto', [
            'operacion' => $operacion,            
            'pagination' => $pages,
            'id' => $id,
            'form' => $form,
            'id_punto' => $id_punto,
            'sw' => $sw,
        ]);
    }
    
     //modificar cantidades produccion
    public function actionModificar_cantidades($id, $id_punto, $id_traslado, $sw) {
        $model = new \app\models\FormModeloCambiarCantidad();
        $conRegistro = \app\models\TrasladoReferenciaPunto::findOne($id_traslado);
        $existencia = $conRegistro->detalleColor->stock_punto;
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["asignar_unidades"])) {
                if($model->nueva_cantidad <= $existencia){
                    $conRegistro->unidades = $model->nueva_cantidad;
                    $conRegistro->save();
                    $this->redirect(["inventario-punto-venta/view_traslado", 'id' => $id, 'id_punto' =>$id_punto, 'sw' => $sw]);
                }else{
                    Yii::$app->session->setFlash('warning', 'La cantidad a trasladar es mayor que las existencias de la talla.'); 
                   $this->redirect(["inventario-punto-venta/view_traslado", 'id' => $id, 'id_punto' =>$id_punto, 'sw' => $sw]);
                }   
            }    
        }
         if (Yii::$app->request->get()) {
            $model->nueva_cantidad = $conRegistro->unidades; 
           
         }
        return $this->renderAjax('asignar_unidades_traslado', [
            'model' => $model,
        ]);
    }
    
    
    //APLICAR TRASLADO DE REFERENCIAS.
    public function actionAplicar_traslado($id, $id_punto, $id_traslado, $sw, $nuevo_punto){
        $inventario = InventarioPuntoVenta::findOne($id);
        $traslado = \app\models\TrasladoReferenciaPunto::findOne($id_traslado);
        if($traslado->unidades >0 ){ 
            if($sw == 0){
                //resta del inventario del punto de venta saliente
                $inventarioSaliente = InventarioPuntoVenta::findOne($traslado->id_inventario_saliente);
                $inventarioSaliente->stock_inventario -= $traslado->unidades;
                $inventarioSaliente->save();
                //resta de la talla y color saliente
                $talla_color = \app\models\DetalleColorTalla::findOne($traslado->id_detalle); //restar inventario
                $talla_color->stock_punto -= $traslado->unidades;
                $talla_color->save();
                //actualiza nuevo inventario
                $talla = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id])->all();
                foreach ($talla as $tallas):
                    if($talla_color->id_color == $tallas->id_color && $talla_color->id_talla == $tallas->id_talla){
                       //actualizar la talla y color del nuevo traslado
                       $tallas->stock_punto += $traslado->unidades;
                       $tallas->cantidad += $traslado->unidades;
                       $tallas->save();
                       //actualizar el inventario del punto de venta de la referencia
                       $inventario->stock_inventario += $traslado->unidades;
                       $inventario->stock_unidades += $traslado->unidades;
                       $inventario->save();
                       //actualiza el registro del traslado, cierra el registro
                       $traslado->aplicado = 1;
                       $traslado->save();
                    }
                endforeach;
                 Yii::$app->session->setFlash('success', 'El traslado se aplico con exito en el modulo de inventario.');
                return $this->redirect(['view_traslado','id' =>$id, 'id_punto' => $id_punto, 'sw' => $sw]);
            }else{
               //RESTA EL INVENTARIO DE SALIDA
                $conExistencia = InventarioPuntoVenta::find()->where(['=','id_inventario', $traslado->id_inventario_saliente])->one();    
                $conExistencia ->stock_inventario -= $traslado->unidades;
                $conExistencia ->save();
               
                //SUMA EL INVETARIO DE SALIDA
                $conActualizaEntrada = InventarioPuntoVenta::findOne($id);
                $conActualizaEntrada->stock_inventario += $traslado->unidades;
                $conActualizaEntrada->stock_unidades += $traslado->unidades;
                $conActualizaEntrada->save();
                
                // cierra el registro
                $traslado->aplicado = 1;
                $traslado->save();
                Yii::$app->session->setFlash('success', 'El traslado se aplico con exito en el modulo de inventario.');
                return $this->redirect(['view_traslado','id' =>$id, 'id_punto' => $id_punto, 'sw' => $sw]);
            }
        }else{
            Yii::$app->session->setFlash('warning', 'No se puede aplicar el traslado porque no ha ingresado las unidades. Favor valide la información.');
            return $this->redirect(['view_traslado','id' =>$id, 'id_punto' => $id_punto, 'sw' => $sw]);   
        }    
       
    }
    
    
    //PROCESO QUE SUMA TODAAS LAS CANTIDAD
    protected function ActualizarLineas($id) {
        $inventario = InventarioPuntoVenta::findOne($id);
        $detalle = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id])->all();
        $suma = 0;
        foreach ($detalle as $detalles):
            $suma += $detalles->cantidad; 
        endforeach;
        $inventario->stock_unidades =  $suma;
        $inventario->stock_inventario =  $suma;
        $inventario->save();
    }
    
    //CREAR LA REGLA PARA DISTRIBIDOR
    public function actionCrear_descuento_mayorista($id, $sw = 0, $id_punto) {
        $model = new \app\models\ModeloEditarReglaDescuento();
        $inventario = InventarioPuntoVenta::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["descuento_distribuidor"])) {
                    $table = new \app\models\DescuentoDistribuidor();
                    $table->id_inventario = $id;
                    $table->fecha_inicio =  $model->fecha_inicio;
                    $table->fecha_final = $model->fecha_final;
                    $table->nuevo_valor = $model->nuevo_valor;
                    $table->tipo_descuento = $model->tipo_descuento;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save(false);
                    $inventario->aplica_descuento_distribuidor = 1;
                    $inventario->save();
                    $this->redirect(["inventario-punto-venta/view_descuentos_comerciales", 'id' => $id, 'id_punto' =>$id_punto]);
                }
            }else{
                $model->getErrors();
            }    
        }
        return $this->renderAjax('_form_descuento_mayorista', [
            'model' => $model,
            'id' => $id,
            'sw' => $sw,
        ]);
    }
    
    //EDITAR DESCUENTO COMERCIAL MAYORISTA
    public function actionEditar_descuento_mayorista($id, $sw = 1, $id_punto, $id_detalle) {
        $model = new \app\models\ModeloEditarReglaDescuento();
        $table = InventarioPuntoVenta::findOne($id);
        $regla = \app\models\DescuentoDistribuidor::find()->where(['=','id_regla', $id_detalle])->one();
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["regla_distribuidor"])) {
                   $regla->fecha_inicio =  $model->fecha_inicio;
                   $regla->fecha_final = $model->fecha_final;
                   $regla->nuevo_valor = $model->nuevo_valor;
                   $regla->tipo_descuento = $model->tipo_descuento;
                   $regla->estado_regla = $model->estado;
                   $regla->save(false);
                   if($model->estado == 1){
                        $table->aplica_descuento_distribuidor = 0;
                        $table->save();
                    }else{
                        $table->aplica_descuento_distribuidor = 1;
                        $table->save();
                    }     
                   $this->redirect(["inventario-punto-venta/view_descuentos_comerciales",'id' => $id, 'id_punto' =>$id_punto]);
                }
            }else{
                $model->getErrors();
            }    
        }
        if (Yii::$app->request->get()) {
            $model->fecha_inicio = $regla->fecha_inicio;
            $model->fecha_final = $regla->fecha_final;
            $model->nuevo_valor = $regla->nuevo_valor;
            $model->tipo_descuento = $regla->tipo_descuento;
            $model->estado = $regla->estado_regla;
        }
        return $this->renderAjax('_form_editar_descuento_comercial', [
            'model' => $model,
            'id' => $id,
            'sw' => $sw,
        ]);
    }
    
    //CREAR DESCUENTO PARA PUNTO DE VENTA
    public function actionCrear_descuento_puntoventa($id, $sw = 0, $id_punto) {
        $model = new \app\models\ModeloEditarReglaDescuento();
        $inventario = InventarioPuntoVenta::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["descuento_puntoventa"])) {
                    $table = new \app\models\DescuentoPuntoVenta();
                    $table->id_inventario = $id;
                    $table->fecha_inicio =  $model->fecha_inicio;
                    $table->fecha_final = $model->fecha_final;
                    $table->nuevo_valor = $model->nuevo_valor;
                    $table->tipo_descuento = $model->tipo_descuento;
                    $table->id_punto = $id_punto;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save(false);
                    $inventario->aplica_descuento_punto = 1;
                    $inventario->save();
                    $this->redirect(["inventario-punto-venta/view_descuentos_comerciales", 'id' => $id, 'id_punto' => $id_punto]);
                }
            }else{
                $model->getErrors();
            }    
        }
        return $this->renderAjax('_form_descuento_puntoventa', [
            'model' => $model,
            'id' => $id,
            'sw' => $sw,
        ]);
    }
    
    //EDITAR DESCUENTO COMERCIAL PUNTO DE VENTA
    public function actionEditar_descuento_puntoventa($id, $sw = 1) {
        $model = new \app\models\ModeloEditarReglaDescuento();
        $table = InventarioPuntoVenta::findOne($id);
        $regla = \app\models\DescuentoPuntoVenta::find()->where(['=','id_inventario', $id])->one();
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["descuento_puntoventa"])) {
                   $regla->fecha_inicio =  $model->fecha_inicio;
                   $regla->fecha_final = $model->fecha_final;
                   $regla->nuevo_valor = $model->nuevo_valor;
                   $regla->tipo_descuento = $model->tipo_descuento;
                   $regla->estado_regla = $model->estado;
                   $regla->save(false);
                   if($model->estado == 1){
                        $table->aplica_descuento_punto = 0;
                        $table->save();
                    }else{
                        $table->aplica_descuento_punto = 1;
                        $table->save();
                    }     
                   $this->redirect(["inventario-punto-venta/view_descuentos_comerciales",'id' => $id]);
                }
            }else{
                $model->getErrors();
            }    
        }
        if (Yii::$app->request->get()) {
            $model->fecha_inicio = $regla->fecha_inicio;
            $model->fecha_final = $regla->fecha_final;
            $model->nuevo_valor = $regla->nuevo_valor;
            $model->tipo_descuento = $regla->tipo_descuento;
            $model->estado = $regla->estado_regla;
        }
        return $this->renderAjax('_form_descuento_puntoventa', [
            'model' => $model,
            'id' => $id,
            'sw' => $sw,
        ]);
    }
    
    //PROCESO QUE VALIDA LA CARGA DE IMAGENES DEL PRODUCTO
     public function actionValidador_imagen($token = 0) {
       if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',88])->all()){
                $form = new \app\models\FormModeloBuscar();
                $q = null;
                $nombre = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $q = Html::encode($form->q);
                        $nombre = Html::encode($form->nombre);
                        if ($q == ''){
                            $table = InventarioPuntoVenta::find()
                                ->Where(['like','nombre_producto', $nombre])
                                ->andwhere(['=','venta_publico', 1])
                                ->andwhere(['>','stock_inventario', 1])
                                ->andwhere(['=','id_punto', 1]);
                        }else{
                            $table = InventarioPuntoVenta::find()
                                ->where(['=','codigo_producto', $q])
                                ->andwhere(['=','venta_publico', 1])
                                ->andwhere(['>','stock_inventario', 1])
                                ->andwhere(['=','id_punto', 1]);
                        } 
                        $table = $table->orderBy('id_inventario DESC');  
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 6,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = InventarioPuntoVenta::find()->where(['>','stock_inventario', 0])->andWhere(['=','venta_publico', 1]) 
                                                         ->andWhere(['=','id_punto', 1])->orderBy('id_inventario DESC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 6,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                }

                $to = $count->count();
                return $this->render('validador_archivo_imagenes', [
                            'model' => $model,
                            'form' => $form,
                            'pagination' => $pages,
                            'token' => $token,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }
    
    //ENVIAR REFERENCIAS A PUNTOS DE VENTA DESDE BODEGA POR PRIMERA VEZ(NO APLICA TALLA NI COLO)
    public function actionTrasladar_punto_venta($id) {
        $model = new \app\models\ModeloTrasladoPuntoventa();
        $inventario = InventarioPuntoVenta::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                if (isset($_POST["enviar_producto"])) {
                    if(InventarioPuntoVenta::find()->where(['=','codigo_enlace_bodega', $id])->andWhere(['=','id_punto', $model->punto_venta])->one()){
                        Yii::$app->getSession()->setFlash('error', 'Este producto ya se encuentra registrado en este punto de venta.');
                       return $this->redirect(['index']);
                    }else{
                        $table = new InventarioPuntoVenta();
                        $table->codigo_producto = $inventario->codigo_producto;
                        $table->codigo_barra = $inventario->codigo_producto;
                        $table->nombre_producto = $inventario->nombre_producto;
                        $table->costo_unitario = $inventario->costo_unitario;
                        $table->id_proveedor = $inventario->id_proveedor;
                        $table->id_marca = $inventario->id_marca;
                        $table->id_categoria = $inventario->id_categoria;
                        $table->iva_incluido = $inventario->iva_incluido;
                        $table->inventario_inicial = $inventario->inventario_inicial;
                        $table->aplica_inventario = $inventario->aplica_inventario;
                        $table->porcentaje_iva = $inventario->porcentaje_iva;
                        $table->fecha_proceso = date('Y-m-d');
                        $table->user_name = Yii::$app->user->identity->username;
                        $table->venta_publico = $inventario->venta_publico;
                        $table->aplica_talla_color = $inventario->aplica_talla_color;
                        $table->codigo_enlace_bodega = $inventario->id_inventario;
                        $table->id_punto = $model->punto_venta;
                        if($model->unidades > 0){
                            if($inventario->stock_inventario >= $model->unidades){
                                $table->stock_inventario = $model->unidades; 
                                $table->stock_unidades = $model->unidades;
                                $table->inventario_aprobado = 1;
                                $table->save(false);
                                $inventario->stock_inventario -= $model->unidades;
                                $inventario->save();
                                return $this->redirect(['index']);
                               
                            }else{
                                return $this->renderAjax('_form_traslado_puntoventa', [
                                'model' => $model,
                                'id' => $id,

                                ]);  
                            }    
                        }else{
                            return $this->renderAjax('_form_traslado_puntoventa', [
                              'model' => $model,
                              'id' => $id,

                          ]);
                        }
                    }    
                }  
                
            }else{
                $model->getErrors();
            }    
        }
        return $this->renderAjax('_form_traslado_puntoventa', [
            'model' => $model,
            'id' => $id,
            
        ]);
    }
   
    //TRASLADAR REFERENCIAS A PUNTOS DE VENTA DESDE BODEGA (NO APLICA TALLA NI COLO)
    public function actionTrasladar_referencia_bodega_punto($id) {
        $model = new \app\models\ModeloTrasladoPuntoventa();
        $inventario = InventarioPuntoVenta::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["enviar_traslado_bodega"])) {
                $consulta = InventarioPuntoVenta::findOne($inventario->codigo_enlace_bodega); 
                if($model->unidades <= $consulta->stock_inventario){
                    $table = new \app\models\TrasladoReferenciaPunto();
                    $table->id_inventario_saliente = $inventario->codigo_enlace_bodega;
                    $table->id_inventario_entrante = $id;
                    $table->id_punto_saliente = 1;
                    $table->id_punto_entrante = $inventario->id_punto;
                    $table->unidades = $model->unidades;
                    $table->fecha_proceso = date('Y-m-d');
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->aplicado = 1;
                    $table->save(); 
                    //descargar de bodega
                    $consulta->stock_inventario -= $model->unidades;
                    $consulta->save();
                    //suma al nuevo inventario
                    $inventario->stock_inventario += $model->unidades;
                    $inventario->stock_unidades += $model->unidades;
                    $inventario->save();
                    return $this->redirect(['index']);
                }else{
                    Yii::$app->getSession()->setFlash('error', 'La cantidad a trasladar no puede ser mayor que el STOCK en BODEGA.');
                    return $this->redirect(['index']);
                }
               
            }
            
        }    
        return $this->renderAjax('traslado_bodega_puntoventa', [
            'model' => $model,
            'id' => $id,
            
        ]);
    }
    // IMPORTAR UNIDADES DE BODEGA
    public function actionImportar_inventario_bodega($id, $id_punto) {
        $model = InventarioPuntoVenta::findOne($id);
        $talla_color = \app\models\DetalleColorTalla::find()->where(['=','codigo_producto', $model->codigo_producto])
                                                            ->andWhere(['=','id_inventario', $model->codigo_enlace_bodega])->all();
        if (Yii::$app->request->post()) {
            if(isset($_POST["traslado_unidades_bodega"])){
                if(isset($_POST["nuevo_traslado_bodega"])){
                    $intIndice = 0;
                    $cantidad = 0;
                    foreach ($_POST["nuevo_traslado_bodega"] as $intCodigo):
                        $cantidad = $_POST["cantidades"][$intIndice];
                        if($cantidad > 0){
                            $talla = \app\models\DetalleColorTalla::findOne($intCodigo);
                            if($cantidad <= $talla->stock_punto){
                                $actualiar_unidades = \app\models\DetalleColorTalla::find()->where(['=','id_color', $talla->id_color])
                                                                                           ->andWhere(['=','id_talla', $talla->id_talla])
                                                                                           ->andWhere(['=','codigo_producto', $talla->codigo_producto])
                                                                                           ->andWhere(['=','id_punto', $id_punto])->one();
                                if($actualiar_unidades){
                                    $talla->stock_punto -= $cantidad;
                                    $talla->save();
                                    //actualiza cantidad entrante
                                    $actualiar_unidades->cantidad += $cantidad;
                                    $actualiar_unidades->stock_punto += $cantidad;
                                    $actualiar_unidades->save();
                                    //descarga del inventar de bodega
                                    $salida_inventario = InventarioPuntoVenta::findOne($talla->id_inventario);
                                    $salida_inventario->stock_inventario -= $cantidad;
                                    $salida_inventario->save();
                                    //actualizar inventario entrante
                                    $actualizar_inventario_entrante = InventarioPuntoVenta::findOne($id);
                                    $actualizar_inventario_entrante->stock_inventario += $cantidad;
                                    $actualizar_inventario_entrante->stock_unidades += $cantidad;
                                    $actualizar_inventario_entrante->save();
                                    //ingresa el registro en traslado
                                    $table = new \app\models\TrasladoReferenciaPunto();
                                    $table->id_inventario_saliente = $talla->id_inventario;
                                    $table->id_inventario_entrante = $id;
                                    $table->id_punto_saliente = $talla->id_punto;
                                    $table->id_punto_entrante = $id_punto;
                                    $table->id_talla = $talla->id_talla;
                                    $table->id_color = $talla->id_color;
                                    $table->unidades = $cantidad;
                                    $table->fecha_proceso = date('Y-m-d');
                                    $table->user_name = Yii::$app->user->identity->username;
                                    $table->aplicado = 1;
                                    $table->save();
                                    $intIndice++;
                                }else{
                                  $intIndice++;  
                                }
                            }else{
                                Yii::$app->getSession()->setFlash('error', 'La cantidad a trasladar no puede ser mayor que el STOCK de la talla.');
                            }
                           
                        
                        }else{
                             $intIndice++;  
                        } 
                    endforeach;
                    return $this->redirect(['importar_inventario_bodega','id' =>$id, 'id_punto' => $id_punto]);
                }
            }  
        }    
        return $this->render('importar_unidades_bodega', [
            'model' => $model,
            'id' => $id,
            'id_punto'=> $id_punto,
            'talla_color' => $talla_color,

            
        ]); 
    }
  
    //ENVIAR INVENTARIO POR PRIMERA VEZ A PUNTO DE VENTA (APLICA TALLA)
    public function actionEnviar_referencia_punto($id, $id_punto) {
        $model = InventarioPuntoVenta::findOne($id);
        $talla_color = \app\models\DetalleColorTalla::find()->where(['=','codigo_producto', $model->codigo_producto])
                                                            ->andWhere(['=','id_inventario', $id])->all();
        if(isset($_POST["enviar_referencia"])){
            if(isset($_POST["nuevo_envio_bodega"])){
                $intIndice = 0;
                $cantidad = 0;
                $auxiliar = 0;
                foreach ($_POST["nuevo_envio_bodega"] as $intCodigo):
                    $cantidad = $_POST["cantidades"][$intIndice];
                    if($cantidad > 0){
                        $talla = \app\models\DetalleColorTalla::findOne($intCodigo);
                        if($cantidad <= $talla->stock_punto){
                            if($auxiliar <> $_POST["id_punto_saliente"][$intIndice]){
                                $table = new InventarioPuntoVenta();
                                $table->codigo_producto = $model->codigo_producto;
                                $table->codigo_barra = $model->codigo_producto;
                                $table->nombre_producto = $model->nombre_producto;
                                $table->costo_unitario = $model->costo_unitario;
                                $table->stock_unidades += $cantidad;
                                $table->stock_inventario += $cantidad;
                                $table->id_proveedor = $model->id_proveedor;
                                $table->id_marca = $model->id_marca;
                                $table->id_categoria = $model->id_categoria;
                                $table->iva_incluido = $model->iva_incluido;
                                $table->inventario_inicial = $model->inventario_inicial;
                                $table->aplica_inventario = $model->aplica_inventario;
                                $table->aplica_talla_color = $model->aplica_talla_color;
                                $table->porcentaje_iva = $model->porcentaje_iva;
                                $table->fecha_proceso = date('Y-m-d');
                                $table->user_name = Yii::$app->user->identity->username;
                                $table->venta_publico = $model->venta_publico;
                                $table->codigo_enlace_bodega = $model->id_inventario;
                                $table->id_punto = $_POST["id_punto_saliente"][$intIndice];
                                $table->inventario_aprobado = 1;
                                $table->save();
                                $conId = InventarioPuntoVenta::find()->orderBy('id_inventario DESC')->one();
                                $model->stock_inventario -= $cantidad;  
                                $model->save(false);
                                //creamos cada talla con la combinacion de tallas
                                $insertarTalla = new \app\models\DetalleColorTalla();
                                $insertarTalla->id_inventario = $conId->id_inventario;
                                $insertarTalla->codigo_producto = $model->codigo_producto;
                                $insertarTalla->id_color = $talla->id_color;
                                $insertarTalla->id_talla = $talla->id_talla;
                                $insertarTalla->id_punto = $_POST["id_punto_saliente"][$intIndice];
                                $insertarTalla->cantidad = $cantidad;
                                $insertarTalla->stock_punto =  $cantidad;
                                $insertarTalla->cerrado = 1;
                                $insertarTalla->user_name = Yii::$app->user->identity->username;
                                $insertarTalla->save(false);
                                ////*****////
                                //actualizar talla
                                $talla->stock_punto -= $cantidad;
                                $talla->save(false);
                                $auxiliar = $_POST["id_punto_saliente"][$intIndice];
                                $intIndice++; 
                            }else{
                                $model->stock_inventario -= $cantidad;  
                                $model->save(false);
                                //creamos cada talla con la combinacion de tallas
                                $insertarTalla = new \app\models\DetalleColorTalla();
                                $insertarTalla->id_inventario = $conId->id_inventario;
                                $insertarTalla->codigo_producto = $model->codigo_producto;
                                $insertarTalla->id_color = $talla->id_color;
                                $insertarTalla->id_talla = $talla->id_talla;
                                $insertarTalla->id_punto = $_POST["id_punto_saliente"][$intIndice];
                                $insertarTalla->cantidad = $cantidad;
                                $insertarTalla->stock_punto =  $cantidad;
                                $insertarTalla->cerrado = 1;
                                $insertarTalla->user_name = Yii::$app->user->identity->username;
                                $insertarTalla->save(false);
                                ////*****////
                                //actualizar talla
                                $talla->stock_punto -= $cantidad;
                                $talla->save(false);
                                $conTallas = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $conId->id_inventario])->andWhere(['=','id_punto', $_POST["id_punto_saliente"][$intIndice]])->all();
                                $suma =0;
                                foreach ($conTallas as $sumar):
                                    $suma += $sumar->stock_punto;
                                endforeach;
                                //actualizar cantidades en el inventario del punto de venta
                                $invSumar = InventarioPuntoVenta::findOne($conId->id_inventario);
                                $invSumar->stock_inventario = $suma;
                                $invSumar->stock_unidades = $suma;
                                $invSumar->save();
                                $auxiliar = $_POST["id_punto_saliente"][$intIndice];
                                $intIndice++;   
                            }    
                        }else{
                            Yii::$app->getSession()->setFlash('error', 'La cantidad a trasladar no puede ser mayor que el STOCK de la talla.');
                        }    
                    }else{
                        $intIndice++;  
                    }
                endforeach;
            }
       }        
        return $this->render('submit_referencia_punto', [
            'model' => $model,
            'id' => $id,
            'id_punto'=> $id_punto,
            'talla_color' => $talla_color,

            
        ]); 
    }
    
    /**
     * Creates a new InventarioPuntoVenta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InventarioPuntoVenta();
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())){
            $conDato = \app\models\InventarioPuntoVenta::find()->where(['=','codigo_producto', $model->codigo_producto])->one();
            if($conDato){
                 Yii::$app->getSession()->setFlash('info', 'El codigo ('. $model->codigo_producto. ') ya esta codificado en el sistema. Valide la informacion.');
            }else{
                if($model->aplica_talla_color == 0 ){
                    if ($model->stock_unidades > 0){
                        $model->save() ;
                        $model->user_name = Yii::$app->user->identity->username;
                        $model->codigo_barra = $model->codigo_producto;
                        $model->id_punto = 1;
                        $model->stock_unidades = $model->stock_unidades;
                        $model->stock_inventario = $model->stock_unidades;
                        $model->save();
                        $id = $model->id_inventario;
                        return $this->redirect(['index']);
                    }else{
                        Yii::$app->getSession()->setFlash('error', 'Debe de ingresar las unidades al inventario.');
                    } 
               }else{
                    $model->save();
                    $model->user_name = Yii::$app->user->identity->username;
                    $model->codigo_barra = $model->codigo_producto;
                    $model->id_punto = 1;
                    $model->stock_unidades = 0;
                    $model->stock_inventario = 0;
                    $model->save();
                    $id = $model->id_inventario;
                    return $this->redirect(['index']);
               }    
            }
        }   

        return $this->render('create', [
            'model' => $model,
            'sw' =>0,
        ]);
    }
    
    /**
     * Updates an existing InventarioPuntoVenta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($model->aplica_talla_color == 0 ){
                if ($model->stock_unidades > 0){
                    if($model->codigo_enlace_bodega > 0){
                        $table = InventarioPuntoVenta::findOne($model->codigo_enlace_bodega);
                        if($model->stock_unidades <= $table->stock_inventario){
                            $model->save() ;
                            $model->user_name = Yii::$app->user->identity->username;
                            $model->codigo_barra = $model->codigo_producto;
                            $model->stock_unidades = $model->stock_unidades;
                            $model->stock_inventario = $model->stock_unidades;
                            $model->save();
                            $table->stock_inventario -= $model->stock_unidades;
                            $table->save();
                            $id = $model->id_inventario;
                             return $this->redirect(['index']); 
                           
                        }else{ 
                             Yii::$app->getSession()->setFlash('error', 'Las cantidades a ingresar son mayores que el inventario actual que hay en bodega.');
                        }
                    }else{
                        $model->save() ;
                        $model->user_name = Yii::$app->user->identity->username;
                        $model->stock_unidades = $model->stock_unidades;
                        $model->stock_inventario = $model->stock_unidades;
                        $model->save();
                        $id = $model->id_inventario;
                        return $this->redirect(['index']);
                    }
                    
                }else{
                    Yii::$app->getSession()->setFlash('error', 'Debe de ingresar las unidades al inventario.');
                } 
            }else{
                    $model->save();
                    $model->user_name = Yii::$app->user->identity->username;
                    $model->codigo_barra = $model->codigo_producto;
                    $model->id_punto = 1;
                    $model->stock_unidades = 0;
                    $model->stock_inventario = 0;
                    $model->save();
                    $id = $model->id_inventario;
                    return $this->redirect(['index']);
            }    
        }

        return $this->render('update', [
            'model' => $model,
            'sw' => 1,
        ]);
    }
    
     //PROCESO QUE ACTUALIZA LOS PRECIOS DEL PRODUCTO
    protected function ActualizarTotalesProducto($id) {
       $inventario = InventarioPuntoVenta::findOne($id);
       $subtotal =0;
       $impuesto = 0;
       $total = 0;
       $poncentaje = ''.round(($inventario->porcentaje_iva / 100),2);
       $subtotal = $inventario->stock_inventario * $inventario->costo_unitario;
       if($inventario->iva_incluido == 1){
          $impuesto = round($subtotal * $poncentaje);    
       }else{
           $impuesto = 0;
       }
       $inventario->subtotal = $subtotal - $impuesto;
       $inventario->valor_iva = $impuesto;
       $inventario->total_inventario = $subtotal;
       $inventario->save();
    }

    //ELIMINAR DETALLES DE TRASLADOS
    public function actionEliminar_traslado($id, $id_traslado, $id_punto, $sw)
    {                                
        
        if($sw == 0){
            $detalles = \app\models\TrasladoReferenciaPunto::findOne($id_traslado);
            $detalles->delete();
            $this->redirect(["inventario-punto-venta/view_traslado",'id' => $id, 'id_punto' => $id_punto,'sw' => $sw]);  
        }else{
            $detalles = \app\models\TrasladoReferenciaPunto::findOne($id_traslado);
            $detalles->delete();
            $this->redirect(["inventario-punto-venta/view_traslado",'id' => $id, 'id_punto' => $id_punto,'sw' => $sw]);  
        }    
    } 
    
    //ELIMINAR DETALLES  
    public function actionEliminar($id,$id_detalle, $token,$codigo)
    {                                
        $detalles = \app\models\DetalleColorTalla::findOne($id_detalle);
        $detalles->delete();
        $this->ActualizarLineas($id);
        $this->ActualizarTotalesProducto($id);
        $this->redirect(["view",'id' => $id, 'token' => $token, 'codigo' => $codigo]);        
    } 
    
    //PROCESO QUE GENERA LA COMBINACION DE TALLAS Y COLORES
    public function actionGenerar_combinacion_talla_color($id, $token, $codigo) {
        $form = new \app\models\FiltroBusquedaTallas();
        $codigo_talla = null;
        $conColores = null;
        if ($form->load(Yii::$app->request->get())) {
            $codigo_talla = Html::encode($form->codigo_talla);
            if($codigo_talla > 0){
                $model = \app\models\Tallas::find()->where(['=','id_talla', $codigo_talla])->one();
                if($codigo == 0){
                    $conColores = \app\models\Colores::find()->orderBy('colores ASC')->all();
                }else{
                    $conColores = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $codigo])->andWhere(['=','id_talla', $codigo_talla ])->orderBy('id_color ASC')->all();
                }
                
            }else{
                Yii::$app->getSession()->setFlash('warning', 'Debe seleccionar la talla de la lista.');
                return $this->redirect(['generar_combinacion_talla_color','id' =>$id, 'token' =>$token, 'codigo' => $codigo]);
            }
            
        }
        if (isset($_POST["enviarcolores"])) {
            if(isset($_POST["nuevo_color"])){
                foreach ($_POST["nuevo_color"] as $intCodigo) {
                    $ConInventario = InventarioPuntoVenta::findOne($id);
                    $table = new \app\models\DetalleColorTalla();
                    $table->id_inventario = $id;
                    $table->codigo_producto = $ConInventario->codigo_producto;
                    $table->id_color = $intCodigo;
                    $table->id_punto = $ConInventario->id_punto;
                    $table->id_talla = $model->id_talla;
                    $table->user_name = Yii::$app->user->identity->username;
                    $table->save();
                }
                return $this->redirect(['generar_combinacion_talla_color','id' => $id, 'token' => $token, 'conColores' => $conColores,  'model' =>$model, 'codigo'=> $codigo]);
            }
            
        }
        return $this->render('generar_combinacion', [
            'id' => $id,
            'token' => $token,
            'form' => $form, 
            'conColores' => $conColores,
            'codigo' => $codigo,
        ]);
    }
   
    //CERRAR COMBINACIONES
    public function actionCerrar_combinaciones($id, $token, $codigo){
        $detalle = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id])->andWhere(['=','cerrado', 0])->all();
        if($detalle){
            foreach ($detalle as $detalles):
                $detalles->cerrado = 1;
                $detalles->save ();
            endforeach;
            $this->redirect(["view",'id' => $id, 'token' => $token, 'codigo' => $codigo]);
        }else{
            Yii::$app->getSession()->setFlash('warning', 'Este proceso ya esta cerrado para las tallas y colores.');
            $this->redirect(["view",'id' => $id, 'token' => $token, 'codigo' => $codigo]);
        }    
        
    }
    
     //CREAR PRECIOS DE VENTA
    public function actionCrear_precio_venta() {
        if (Yii::$app->user->identity){
            if (UsuarioDetalle::find()->where(['=','codusuario', Yii::$app->user->identity->codusuario])->andWhere(['=','id_permiso',87])->all()){
                $form = new \app\models\ModelCrearPrecios();
                $codigo= null;
                $producto = null;
                $marca = null;
                $proveedor = null; $categoria = null; $punto_venta = null;
                $sw  = 0;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $codigo = Html::encode($form->codigo);
                        $marca = Html::encode($form->marca);
                        $proveedor = Html::encode($form->proveedor);
                        $punto_venta = Html::encode($form->punto_venta);
                        $categoria = Html::encode($form->categoria);
                        $producto = Html::encode($form->producto);
                        $table = InventarioPuntoVenta::find()
                                        ->andFilterWhere(['=', 'codigo_producto', $codigo])
                                        ->andFilterWhere(['=', 'id_marca', $marca])
                                        ->andFilterWhere(['like', 'nombre_producto', $producto])
                                        ->andFilterWhere(['=', 'id_proveedor', $proveedor])
                                        ->andFilterWhere(['=', 'id_categoria', $categoria])
                                        ->andFilterWhere(['=', 'id_punto', $punto_venta])
                                        ->andWhere(['>','stock_inventario', 0]); 
                        $table = $table->orderBy('id_inventario DESC');
                        $tableexcel = $table->all();
                        $count = clone $table;
                        $to = $count->count();
                        $pages = new Pagination([
                            'pageSize' => 15,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                                ->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
                            $sw = 1;
                    } else {
                        $form->getErrors();
                    }
                }else{
                    $table = InventarioPuntoVenta::find()->Where(['>','stock_inventario', 0])->orderBy('id_inventario DESC');
                    $tableexcel = $table->all();
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 15,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                }
                return $this->render('precio_deptal_mayorista', [
                            'model' => $model,
                            'form' => $form,
                            'sw' => $sw,
                            'pagination' => $pages,
                ]);
            }else{
                return $this->redirect(['site/sinpermiso']);
            }
        }else{
            return $this->redirect(['site/login']);
        }    
    }
    
    //PERMITE CREAR EL PRECIO UNICO PARA VENTA AL DEPTA Y MAYORISTA
    public function actionCrear_precios_deptal_mayorista($id) {
        $model = new \app\models\FormModeloCambiarCantidad();
        $table = InventarioPuntoVenta::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["crear_precio_venta"])) {
                if($model->tipo_precio == 2){//mayorista
                    $table->precio_mayorista = $model->nuevo_precio;
                }else{
                    $table->precio_deptal = $model->nuevo_precio;
                }    
            $table->save(false);
            $this->redirect(["inventario-punto-venta/crear_precio_venta", 'id' => $id]);
            }
        }
        return $this->renderAjax('_form_precio_punto_distribuidor', [
            'model' => $model,
            'id' => $id,
        ]);
    }
    
    //PERMITE CREAR EL MINITO STOCK DE CADA PRODUCTO
    public function actionCrear_minimo_stock($id) {
        $model = new \app\models\FormModeloCambiarCantidad();
        $table = InventarioPuntoVenta::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST["aplicar_stock"])) {
                if($model->aplicar == 1){ //NO
                    $table->stock_minimo = $model->minimo;
                    $table->save(false);
                    $this->redirect(["inventario-punto-venta/crear_precio_venta", 'id' => $id]);
                }else{
                    $total_unidades = InventarioPuntoVenta::find()->where(['=','codigo_producto', $table->codigo_producto])->all();
                    foreach ($total_unidades as $total):
                        $total->stock_minimo = $model->minimo;
                        $total->save(false);
                    endforeach;
                    $this->redirect(["inventario-punto-venta/crear_precio_venta", 'id' => $id]);
                }    
            }
        }
        return $this->renderAjax('_form_aplicar_stock_minimo', [
            'model' => $model,
            'id' => $id,
        ]);
    }
    
    //DESCARGAR INVENTARIO DE BODEGA
    public function actionDescargar_inventario_bodega($token, $id, $codigo){
        $bodega = InventarioPuntoVenta::findOne($codigo);
        $punto = InventarioPuntoVenta::findOne($id);
        $talla_color_bodega = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $codigo])->all();
        $talla_color_punto = \app\models\DetalleColorTalla::find()->where(['=','id_inventario', $id])->all();
        foreach ($talla_color_bodega as $talla_bodega):
            foreach ($talla_color_punto as $talla_punto):
                 $saldo = 0;
                 if($talla_bodega->id_color == $talla_punto->id_color && $talla_bodega->id_talla == $talla_punto->id_talla){
                     $saldo = $talla_bodega->stock_punto; 
                     $saldo -= $talla_punto->stock_punto;
                     if($saldo >= 0){
                         $talla_bodega->stock_punto = $saldo;
                         $talla_bodega->save();
                         $punto->inventario_aprobado = 1;
                         $punto->save();
                     }else{
                        Yii::$app->getSession()->setFlash('error', 'La Talla (' . $talla_bodega->talla->nombre_talla . ' ), No cumple con las exitencias actuales en bodega.'); 
                         $this->redirect(["view",'id' => $id, 'token' => $token, 'codigo' => $codigo]);
                     }
                         
                 }
            endforeach;
        endforeach;
        
       //primera descarga de bodega
        $bodega->stock_inventario -= $punto->stock_inventario;
        $bodega->inventario_aprobado = 1;
        $bodega->save();
        $this->redirect(["view",'id' => $id, 'token' => $token, 'codigo' => $codigo]);
        
    }
    /**
     * Finds the InventarioPuntoVenta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InventarioPuntoVenta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InventarioPuntoVenta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //EXCELES
    
    public function actionExcelInventarioPuntoVenta($tableexcel) {                
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
        
                                     
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'CODIGO')
                    ->setCellValue('C1', 'PRODUCTO')
                    ->setCellValue('D1', 'FECHA PROCESO')
                    ->setCellValue('E1', 'FECHA CREACION')
                    ->setCellValue('F1', 'APLICA INVENTARIO')
                    ->setCellValue('G1', 'INVENTARIO INICIAL')
                    ->setCellValue('H1', 'UNIDADES ENTRADAS')
                    ->setCellValue('I1', 'STOCK')
                    ->setCellValue('J1', 'MINIMO STOCK')
                    ->setCellValue('K1', 'VALOR UNITARIO')
                    ->setCellValue('L1', 'SUBTOTAL')
                    ->setCellValue('M1', 'IMPUESTO')
                    ->setCellValue('N1', 'VALOR TOTAL')
                    ->setCellValue('O1', 'USER NAME')
                    ->setCellValue('P1', 'CODIGO EAN')
                    ->setCellValue('Q1', 'MARCA')
                    ->setCellValue('R1', 'CATEGORIA')
                    ->setCellValue('S1', 'PRECIO DEPTAL')
                    ->setCellValue('T1', 'PRECIO MAYORISTA')
                    ->setCellValue('U1', 'APLICA DESCTO PUNTO')
                    ->setCellValue('V1', 'APLICA DESCTO MAYORISTA');
            $i = 2;
        
        foreach ($tableexcel as $val) {
                                  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_inventario)
                    ->setCellValue('B' . $i, $val->codigo_producto)
                    ->setCellValue('C' . $i, $val->nombre_producto)
                    ->setCellValue('D' . $i, $val->fecha_proceso)
                    ->setCellValue('E' . $i, $val->fecha_creacion)
                    ->setCellValue('F' . $i, $val->aplicaInventario)
                    ->setCellValue('G' . $i, $val->inventarioInicial)
                    ->setCellValue('H' . $i, $val->inventarioInicial)
                    ->setCellValue('I' . $i, $val->stock_unidades)
                    ->setCellValue('J' . $i, $val->stock_minimo)
                    ->setCellValue('K' . $i, $val->costo_unitario)
                    ->setCellValue('L' . $i, $val->subtotal)
                    ->setCellValue('M' . $i, $val->valor_iva)
                    ->setCellValue('N' . $i, $val->total_inventario)
                    ->setCellValue('O' . $i, $val->user_name)
                    ->setCellValue('P' . $i, $val->codigo_barra)
                    ->setCellValue('Q' . $i, $val->marca->marca)
                    ->setCellValue('R' . $i, $val->categoria->categoria)
                    ->setCellValue('S' . $i, $val->precio_deptal)
                    ->setCellValue('T' . $i, $val->precio_mayorista)
                    ->setCellValue('U' . $i, $val->aplicaDescuentoPunto)
                    ->setCellValue('V' . $i, $val->aplicaDescuentoDistribuidor) ;
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('inventario');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Inventario_productos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }
}
