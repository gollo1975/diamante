<?php
//clase
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\Session;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\AccessControl;

$this->title = 'Nuevo pedido';
$this->params['breadcrumbs'][] = ['label' => 'Pedidos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_pedido;
?>
          <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
            <?php if($model->autorizado == 0 && $model->numero_pedido == 0){?>
                <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_pedido, 'tokenAcceso' => $tokenAcceso, 'token' => $token, 'id_cliente' => $model->id_cliente, 'pedido_virtual' => $model->pedido_virtual], ['class' => 'btn btn-default btn-sm']);?>
            <?php }else{
                if($model->autorizado == 1  && $model->numero_pedido == 0){?>
                    <?= Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id' => $model->id_pedido, 'tokenAcceso' => $tokenAcceso, 'token' => $token, 'id_cliente' => $model->id_cliente, 'pedido_virtual' => $model->pedido_virtual], ['class' => 'btn btn-default btn-sm']);?>
                    <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Crear pedido', ['crear_pedido_cliente', 'id' => $model->id_pedido, 'tokenAcceso'=> $tokenAcceso, 'token' => $token, 'pedido_virtual' => $model->pedido_virtual],['class' => 'btn btn-warning btn-sm',
                               'data' => ['confirm' => 'Esta seguro de CREAR el pedido al cliente ' .$model->cliente. '.', 'method' => 'post']]);?>
                     <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Observacion',
                                              ['/pedidos/crear_observacion', 'id' => $model->id_pedido, 'tokenAcceso' => $tokenAcceso, 'token' => $token, 'pedido_virtual' => $model->pedido_virtual],
                                                ['title' => 'Crear observaciones al pedido',
                                                 'data-toggle'=>'modal',
                                                 'data-target'=>'#modalcrearobservacion',
                                                 'class' => 'btn btn-info btn-xs'
                                                ])    
                    ?>
                    <div class="modal remote fade" id="modalcrearobservacion">
                           <div class="modal-dialog modal-lg" style ="width: 430px;">    
                               <div class="modal-content"></div>
                           </div>
                    </div>
                <?php }else{
                    if($model->cerrar_pedido == 0){
                        echo Html::a('<span class="glyphicon glyphicon-remove"></span> Cerrar pedido', ['cerrar_pedido', 'id' => $model->id_pedido, 'token'=> $token,'tokenAcceso' => $tokenAcceso, 'pedido_virtual' => $model->pedido_virtual],['class' => 'btn btn-warning btn-sm',
                           'data' => ['confirm' => 'Esta seguro de cerrar el pedido del cliente  '. $model->cliente.'.', 'method' => 'post']]);
                    }else{?>
                    <div class="btn-group btn-sm" role="group">
                        <button type="button" class="btn btn-info  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           Imprimir
                           <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                                <li><?= Html::a('<span class="glyphicon glyphicon-print"></span> Pedido', ['imprimir_pedido', 'id' => $model->id_pedido]) ?></li>
                                <?php if($model->presupuesto > 0){?>
                                    <li><?= Html::a('<span class="glyphicon glyphicon-print"></span> Presupuesto pedido', ['imprimir_presupuesto', 'id' => $model->id_pedido]) ?></li>
                                <?php }?>    
                        </ul>
                    </div>    
                    <?php }    
                } 
            
            }?>



<div class="panel panel-success">
    <div class="panel-body">
        <script language="JavaScript">
            function mostrarfiltro() {
                divC = document.getElementById("filtro");
                if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
            }
        </script>
        <?php $formulario = ActiveForm::begin([
            "method" => "get",
            "action" => Url::toRoute(["pedidos/adicionar_productos", 'id' => $id, 'tokenAcceso' => $tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual]),
            "enableClientValidation" => true,
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                            'template' => '{label}<div class="col-sm-3 form-group">{input}{error}</div>',
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                            'options' => []
                        ],
        ]);
        ?>
        <div class="panel panel-success panel-filters">
            <div class="panel-heading" onclick="mostrarfiltro()">
                Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
            </div>
            <div class="panel-body" id="filtro" style="display:none">
                <div class="row" >
                    <?= $formulario->field($form, "q")->input("search") ?>
                    <?= $formulario->field($form, "nombre")->input("search") ?>
                </div>

                <div class="panel-footer text-right">
                    <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
                    <a align="right" href="<?= Url::toRoute(["pedidos/adicionar_productos", 'id' => $id, 'tokenAcceso' => $tokenAcceso, 'token' => $token, 'pedido_virtual' => $pedido_virtual]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
                </div>
            </div>
        </div>
        <div class="panel panel-success">
            <div class="panel-heading">
                DETALLES DEL PEDIDO
            </div>
            <div class="panel-body">
                <table class="table table-responsive">
                    <tr style="font-size: 90%;">
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "Documento") ?></th>
                        <td><?= Html::encode($model->documento) ?></td>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Cliente') ?></th>
                        <td><?= Html::encode($model->cliente) ?></td>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_proceso') ?></th>
                        <td><?= Html::encode($model->fecha_proceso) ?></td>
                    </tr>
                    <tr style="font-size: 90%;">
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "fecha_entrega") ?></th>
                        <td><?= Html::encode($model->fecha_entrega) ?></td>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                        <td colspan="4"><?= Html::encode($model->observacion) ?></td>
                        
                    </tr>
                </table>    
            </div>   
        </div>    
        <?php $formulario->end() ?>
        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-3 control-label'],
                'options' => []
            ],
        ]); ?>
        <div>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#listadoproductos" aria-controls="listadoproductos" role="tab" data-toggle="tab">Inventarios <span class="badge"><?= $pagination->totalCount ?></span></a></li>
                <li role="presentation"><a href="#detallepedido" aria-controls="detallepedido" role="tab" data-toggle="tab">Pedido <span class="badge"><?= count($detalle_pedido) ?></span></a></li>
                <li role="presentation"><a href="#presupuestocomercial" aria-controls="presupuestocomercial" role="tab" data-toggle="tab">Presupuesto <span class="badge"><?= count($pedido_presupuesto) ?></span></a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="listadoproductos">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                           <div class="panel-body">
                                 <table class="table table-responsive">
                                     <link rel="stylesheet" href="dist/css/site.css">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" style='background-color:#B9D5CE;'>Código</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Imagen</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Cant.</th>
                                            <th scope="col" style='background-color:#B9D5CE;'><span title="Aplica regla comercial">Ar.</span></th>
                                            <th scope="col" style='background-color:#B9D5CE;'><span title="Aplica descuento comercial">%</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $cadena = '';
                                    $item = \app\models\Documentodir::findOne(8);
                                    foreach ($variable as $val): 
                                        $regla = app\models\ProductoReglaComercial::find()->where(['=','id_inventario', $val->id_inventario])->andWhere(['=','estado_regla', 0])->one();
                                        $valor = app\models\DirectorioArchivos::find()->where(['=','codigo', $val->id_inventario])->andWhere(['=','numero', $item->codigodocumento])->one();
                                        $descuento = app\models\ReglaDescuentoDistribuidor::find()->where(['=','id_inventario', $val->id_inventario])->one();
                                        ?>
                                        <tr style="font-size: 90%;">
                                            <td><?= $val->codigo_producto ?></td>
                                            <td><?= $val->nombre_producto ?></td>
                                            <?php if($valor){
                                                  $cadena = 'Documentos/'.$valor->numero.'/'.$valor->codigo.'/'. $valor->nombre;
                                                  if($valor->extension == 'png' || $valor->extension == 'jpeg' || $valor->extension == 'jpg'){?>
                                                     <td id="pelicula"  title="<?php echo $val->nombre_producto?>"> <?= yii\bootstrap\Html::img($cadena)?></td>

                                                  <?php }else {?>
                                                      <td><?= 'NOT FOUND'?></td>
                                                  <?php } 
                                                }else{?>
                                                      <td></td>
                                                <?php }?>     
                                            <td style="background-color:#EDF5F3; color: black"><?= $val->stock_unidades ?></td>
                                            <td style="padding-right: 1;padding-right: 0; text-align: left"> <input type="text" name="cantidad_productos[]"  style="text-align: right" size="5" maxlength="true"> </td> 
                                            <?php if($regla){?>
                                            <td style="color: red"><?= 'SI' ?></td> 
                                            <?php }else{?>
                                               <td></td>
                                            <?php }
                                            if($descuento){?>  
                                               <td style="color: blue"><?= $descuento->nuevo_valor ?></td>
                                            <?php }else {?>   
                                                  <td></td>
                                            <?php }?>   
                                            <input type="hidden" name="nuevo_producto[]" value="<?= $val->id_inventario?>"> 
                                        </tr>
                                    </tbody>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                            <?php if($model->autorizado == 0){?>
                                <div class="panel-footer text-right">
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-plus'></span> Adicionar", ["class" => "btn btn-info btn-sm", 'name' => 'cargar_producto']) ?>
                                </div>
                            <?php }?>
                        </div>
                        <?= LinkPager::widget(['pagination' => $pagination]) ?>
                    </div>
                </div>    
                <!-- TERMINA TABS-->  
                <div role="tabpanel" class="tab-pane" id="detallepedido">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                          <div class="panel-body">
                                <table class="table table-responsive">
                                   <thead>
                                       <tr style="font-size: 90%;">
                                            <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>Cant.</th>
                                           <th scope="col" style='background-color:#B9D5CE;'>Vr. unit.</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Subtotal.</th>
                                           <th scope="col" style='background-color:#B9D5CE; width: 12%'>Iva</th>
                                           <th scope="col" style='background-color:#B9D5CE; width: 14%'>Total</th>
                                            <th scope="col" style='background-color:#B9D5CE;'></th>
                                            <th scope="col" style='background-color:#B9D5CE;'></th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                   <?php
                                   $subtotal = 0; $impuesto = 0; $total = 0;
                                   foreach ($detalle_pedido as $val):
                                       $regla = app\models\ProductoReglaComercial::find()->where(['=','id_inventario', $val->id_inventario])
                                                                                         ->andWhere(['=','estado_regla', 0])->one();
                                       
                                       $subtotal += $val->subtotal;
                                       $impuesto += $val->impuesto;
                                       $total += $val->total_linea;
                                       ?>
                                   <tr style="font-size: 90%;">
                                       <td><?= $val->inventario->nombre_producto ?></td>
                                       <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                       <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                       <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                       <td style="text-align: right"><?= ''.number_format($val->impuesto,0) ?></td>
                                       <td style="text-align: right"><?= ''.number_format($val->total_linea,0) ?></td>
                                       <?php if($tokenAcceso == 3){?>
                                            <td style= 'width: 25px; height: 25px;'>
                                                <?php if($regla && $regla->limite_venta <= $val->cantidad){?>
                                                     <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ', ['crear_regla_pedido', 'id' => $val->id_pedido, 'tokenAcceso' =>$tokenAcceso, 'token' =>$token, 'sw' => 0, 'id_inventario' => $val->id_inventario,'id_cliente' => $model->id_cliente, 'pedido_virtual' => $pedido_virtual], [
                                                                   'class' => '',
                                                                   'title' => 'Proceso que permite agregar el producto al presupuesto comercial.', 
                                                                   'data' => [
                                                                       'confirm' => 'Este pruducto hace parte de la regla de bonificables. ¿Desea agregarlo al presupuesto comercial?',
                                                                       'method' => 'post',
                                                                   ],
                                                     ])?>
                                                <?php }?>
                                             </td>
                                        <?php }else{?>
                                             <td style= 'width: 25px; height: 25px;'></td> 
                                        <?php }?>     
                                       <td style= 'width: 25px; height: 25px;'>
                                            <?php if($model->autorizado == 0){?>
                                                <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle', 'id' => $model->id_pedido, 'detalle' => $val->id_detalle, 'tokenAcceso' => $tokenAcceso, 'token' => 1, 'pedido_virtual' => $pedido_virtual], [
                                                            'class' => '',
                                                            'data' => [
                                                                'confirm' => 'Esta seguro de eliminar este producto del pedido?',
                                                                'method' => 'post',
                                                            ],
                                                        ])
                                                ?>
                                            <?php }?>
                                       </td>
                                   </tr>
                                   </tbody>
                                   <?php endforeach; ?>
                                   <tr>
                                        <td colspan="4"></td>
                                        <td style="text-align: right;"><b>Subtotal:</b></td>
                                        <td align="right" ><b><?= '$'.number_format($subtotal,0); ?></b></td>
                                        <td colspan="1"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td style="text-align: right;"><b>Iva:</b></td>
                                        <td align="right" ><b><?= '$'.number_format($impuesto,0); ?></b></td>
                                        <td colspan="1"></td>
                                    </tr>
                                     <tr>
                                        <td colspan="4"></td>
                                        <td style="text-align: right;"><b>Total:</b></td>
                                        <td align="right" ><b><?= '$'.number_format($total,0); ?></b></td>
                                        <td colspan="1"></td>
                                    </tr>
                               </table>
                           </div>
                           <div class="panel-footer text-right">
                               <?= Html::a('<span class="glyphicon glyphicon-download-alt"></span> Expotar excel', ['excel_pedido', 'id' => $id, 'tokenAcceso' => $tokenAcceso], ['class' => 'btn btn-primary btn-sm']);?>
                           </div>
                       </div>
                </div>
                    <?php ActiveForm::end(); ?>
            </div>        
             <!-- TERMINA TABS-->
               <div role="tabpanel" class="tab-pane" id="presupuestocomercial">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-responsive">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Producto</th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Cant.</th>       
                                             <th scope="col" align="center" style='background-color:#B9D5CE;'>Vr. Unit.</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE; '>Subtotal</th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE; width: 12%'>Iva</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE; width: 14%'>Total</th> 
                                            <th scope="col" style='background-color:#B9D5CE;'></th> 
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                          $subtotal = 0; $impuesto = 0; $total = 0;
                                         foreach ($pedido_presupuesto as $val):
                                            $subtotal += $val->subtotal;
                                            $impuesto += $val->impuesto;
                                            $total += $val->total_linea;?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->inventario->nombre_producto ?></td>
                                                <?php if($val->cantidad == 0){?>
                                                      <td style="padding-right: 1;padding-right: 0; text-align: left"> <input type="text" name="cantidades[]" value="<?= $val->cantidad?>" style="text-align: right" size="7" maxlength="true"> </td> 
                                                <?php }else{?>
                                                      <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                                <?php }?>      
                                                <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->impuesto,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->total_linea,0) ?></td>
                                                <input type="hidden" name="producto_presupuesto[]" value="<?= $val->id_detalle?>"> 
                                                <td style= 'width: 20px; height: 20px;'>
                                                    <?php if($model->cerrar_pedido == 0){?>
                                                        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle_presupuesto', 'id' => $model->id_pedido, 'detalle' => $val->id_detalle, 'token' => $token, 'sw' => 1, 'tokenAcceso' => $tokenAcceso, 'pedido_virtual' => $model->pedido_virtual], [
                                                                    'class' => '',
                                                                    'data' => [
                                                                        'confirm' => 'Esta seguro de eliminar este producto del presupuesto comercial?',
                                                                        'method' => 'post',
                                                                    ],
                                                                ])
                                                        ?>
                                                    <?php }?>
                                               </td>
                                            </tr>
                                         <?php endforeach;?>          
                                    </body>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td style="text-align: right;"><b>Subtotal:</b></td>
                                        <td align="right"><b><?= '$'.number_format($subtotal,0); ?></b></td>
                                        <td colspan="1"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td style="text-align: right;"><b>Iva:</b></td>
                                        <td align="right" ><b><?= '$'.number_format($impuesto,0); ?></b></td>
                                        <td colspan="1"></td>
                                    </tr>
                                     <tr>
                                        <td colspan="4"></td>
                                        <td style="text-align: right;"><b>Total:</b></td>
                                        <td align="right" ><b><?= '$'.number_format($total,0); ?></b></td>
                                        <td colspan="1"></td>
                                    </tr>
                                </table>
                            </div>
                            <?php
                            if($cliente->presupuesto_comercial == 0 ){
                                Yii::$app->getSession()->setFlash('info', 'El cliente '.$model->cliente.' NO tiene presupuesto comercial asignado. Contactar al representante de ventas');     
                            }else{   
                                if($cliente->presupuesto_comercial >= $cliente->gasto_presupuesto_comercial){
                                    if($model->cerrar_pedido == 0 && count($detalle_pedido) > 0){?>
                                        <div class="panel-footer text-right">
                                           <?= Html::a('<span class="glyphicon glyphicon-plus"></span>Adicionar', ['pedidos/adicionar_presupuesto', 'id' => $model->id_pedido, 'token' => $token, 'sw' => 0, 'tokenAcceso' => $tokenAcceso, 'pedido_virtual' => $model->pedido_virtual],[ 'class' => 'btn btn-success btn-sm']) ?>                                            
                                        </div>     
                                    <?php }
                                }else{
                                    Yii::$app->getSession()->setFlash('info', 'Ha superado el presupuesto comercial. Favor eliminar productos o solicitar autorizacion de presupuesto.');     
                                }
                            }    
                            if($model->cerrar_pedido == 1){?>    
                                    <div class="panel-footer text-right">
                                        <?= Html::a('<span class="glyphicon glyphicon-download-alt"></span> Expotar excel', ['excel_pedido_presupuesto', 'id' => $model->id_pedido], ['class' => 'btn btn-primary btn-sm']);?>
                                    </div>                           
                            <?php }?>
                                
                        </div>
                    </div>
                </div>  
             <!--TERMINA TABS-->
        </div>     
    </div>        
</div> 
