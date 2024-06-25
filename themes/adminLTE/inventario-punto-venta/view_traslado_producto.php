    <?php
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
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

$this->title = 'TRASLADO DE PUNTO DE VENTA';
$this->params['breadcrumbs'][] = ['label' => 'Traslados de producto', 'url' => ['traslado_producto']];
$this->params['breadcrumbs'][] = $model->id_inventario;

?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<div class="inventario-punto-venta-view_traslado">

    <!--<?= Html::encode($this->title) ?>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['traslado_producto'], ['class' => 'btn btn-primary btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            DESDE EL PUNTO DE VENTA 
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                   
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo') ?></th>
                    <td><?= Html::encode($model->codigo_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre_producto') ?></th>
                    <td><?= Html::encode($model->nombre_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_punto') ?></th>
                    <td><?= Html::encode($model->punto->nombre_punto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'stock_inventario') ?></th>
                    <td style="text-align: right"><?= Html::encode(''. number_format($model->stock_inventario,0)) ?></td>
                </tr>
            </table>
        </div>
    </div>
   
    <?php if($sw == 0){ //permite trasladar prouductos que  aplica talla y color
        $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
            'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-3 control-label'],
            'options' => []
            ],
            ]);?>
        <!--INICIO LOS TABS-->
        <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#traslado_referencia" aria-controls="traslado_referencia" role="tab" data-toggle="tab">Traslado de referencias <span class="badge"><?= count($asignacion) ?></span></a></li>
         </ul>
        
            <!--TERMINA TABS-->
            <div role="tabpanel" class="tab-pane" id="traslado_referencia">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr style='font-size:90%;'>
                                    <th scope="col" style='background-color:#B9D5CE;'>Punto de venta anterior</th>                      
                                    <th scope="col" style='background-color:#B9D5CE;'>Nuevo punto de venta</th> 
                                    <th scope="col" style='background-color:#B9D5CE;'>Tallas</th>
                                    <th scope="col" style='background-color:#B9D5CE;'>Colores</th>
                                    <th scope="col" style='background-color:#B9D5CE;'>Cantidad</th> 
                                    <th scope="col" style='background-color:#B9D5CE;'>Fecha hora registro</th>
                                     <th scope="col" style='background-color:#B9D5CE;'>Aplicado</th>
                                    <th scope="col" style='background-color:#B9D5CE;'></th>
                                    <th scope="col" style='background-color:#B9D5CE;'></th>
                                    <th scope="col" style='background-color:#B9D5CE;'></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($asignacion  as $val):?>
                                    <tr>
                                        <td><?= $val->puntoSaliente->nombre_punto?></td>
                                        <td><?= $val->puntoEntrante->nombre_punto?></td>
                                        <td><?= $val->tallas->nombre_talla?></td>
                                        <td><?= $val->colores->colores?></td> 
                                        <td style="text-align: right"><?= ''. number_format( $val->unidades,0)?></td>
                                        <td><?= $val->fecha_hora_registro?></td>
                                        <td><?= $val->registroAplicado?></td>
                                        <td style= 'width: 25px; height: 25px;'>
                                            <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_traslado', 'id' => $model->id_inventario, 'id_punto' => $id_punto, 'id_traslado' => $val->id_traslado, 'sw' => $sw], [
                                                       'class' => '',
                                                       'data' => [
                                                           'confirm' => 'Esta seguro de eliminar el registro?',
                                                           'method' => 'post',
                                                       ],
                                                   ])
                                            ?>
                                        </td> 
                                        <td style= 'width: 25px; height: 10px;'>
                                            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ', ['inventario-punto-venta/aplicar_traslado', 'id' => $model->id_inventario, 'id_punto' => $id_punto, 'id_traslado' => $val->id_traslado, 'sw'=> $sw, 'nuevo_punto'=> $val->id_punto_saliente], [
                                                          'class' => '',
                                                          'title' => 'Proceso que permite cargar estas existencias a la referencia seleccionada.', 
                                                          'data' => [
                                                              'confirm' => 'Esta seguro de aplicar el traslado de la referencia:  ('.$model->nombre_producto.') al punto de venta de ('.$val->puntoEntrante->nombre_punto.').',
                                                              'method' => 'post',
                                                          ],
                                            ]);?>
                                        </td> 
                                        <td style="width: 25px; height: 25px;">
                                                <!-- Inicio Nuevo Detalle proceso -->
                                                  <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ',
                                                      ['/inventario-punto-venta/modificar_cantidades', 'id' => $model->id_inventario, 'id_punto' => $id_punto, 'sw' => $sw, 'id_traslado' => $val->id_traslado],
                                                      [
                                                          'title' => 'Ingresar existencia a trasladar',
                                                          'data-toggle'=>'modal',
                                                          'data-target'=>'#modalmodificarcantidades'.$val->id_traslado,
                                                      ])    
                                                 ?>
                                              <div class="modal remote fade" id="modalmodificarcantidades<?= $val->id_traslado ?>">
                                                  <div class="modal-dialog modal-lg" style ="width: 550px;">
                                                      <div class="modal-content"></div>
                                                  </div>
                                              </div>
                                         </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>    
                    </div>
                    <div class="panel-footer text-right"> 
                        <?= Html::a('<span class="glyphicon glyphicon-search"></span> Buscar punto de venta', ['inventario-punto-venta/buscar_punto_venta', 'id' => $model->id_inventario, 'id_punto' => $id_punto, 'sw' => $sw],[ 'class' => 'btn btn-primary btn-sm']) ?>                                            
                    </div>                 
                </div>    
            </div>
            <!-- TERMINA TABS-->
        </div>
    </div> 
        <?php $form->end() ?>    
    <?php }else{
           $formulario = ActiveForm::begin([
                "method" => "get",
                "action" => Url::toRoute(["inventario-punto-venta/view_traslado", 'id' => $model->id_inventario,  'sw' => $sw, 'id_punto' => $id_punto]),
                "enableClientValidation" => true,
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                               'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                               'labelOptions' => ['class' => 'col-sm-2 control-label'],
                               'options' => []
                           ],

           ]);


        ?>
        <div>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#informacion_traslado" aria-controls="informacion_traslado" role="tab" data-toggle="tab">Informacion de traslado <span class="badge"><?= count($talla_color) ?></span></a></li>
                <li role="presentation"><a href="#traslado_producto" aria-controls="traslado_producto" role="tab" data-toggle="tab">Traslado de producto <span class="badge"><?= count($asignacion) ?></span></a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="informacion_traslado">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                           SELECCIONE LA INFORMACION..
                        </div>
                          <div class="panel-body">
                               <div class="row">
                                    <?= $formulario->field($form, 'punto_venta')->widget(Select2::classname(), [
                                   'data' => $conPunto,
                                   'options' => ['prompt' => 'Seleccione...'],
                                   'pluginOptions' => [
                                       'allowClear' => true
                                   ],
                                   ]); ?> 
                                   <?= $formulario->field($form, 'unidades')->textInput(['maxlength' => true]) ?>
                                </div>  
                                <div class="panel-footer text-right">
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-send'></span> Enviar", ["class" => "btn btn-primary btn-sm", 'name' => 'enviar_productos']) ?>
                                </div>
                           </div>
                    </div>
                </div>
            
                <!--TERMINA TABS-->
                <div role="tabpanel" class="tab-pane" id="traslado_producto">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Punto de venta anterior</th>                      
                                        <th scope="col" style='background-color:#B9D5CE;'>Nuevo punto de venta</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Cantidad</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha hora registro</th>
                                         <th scope="col" style='background-color:#B9D5CE;'>Aplicado</th>
                                        <th scope="col" style='background-color:#B9D5CE;'></th>
                                        <th scope="col" style='background-color:#B9D5CE;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($asignacion  as $val):?>
                                        <tr>
                                            <td><?= $val->puntoSaliente->nombre_punto?></td>
                                            <td><?= $val->puntoEntrante->nombre_punto?></td>
                                            <td style="text-align: right"><?= ''. number_format( $val->unidades,0)?></td>
                                            <td><?= $val->fecha_hora_registro?></td>
                                            <td><?= $val->registroAplicado?></td>
                                            <?php 
                                            if($val->aplicado == 0){?>
                                                <td style= 'width: 25px; height: 10px;'>
                                                        <a href="<?= Url::toRoute(["inventario-punto-venta/eliminar_traslado", 'id' => $model->id_inventario, 'id_punto' => $id_punto, 'id_traslado' => $val->id_traslado,'sw' => $sw])?>"
                                                        <span class='glyphicon glyphicon-trash'></span> </a>
                                                 </td>  
                                                <td style= 'width: 25px; height: 10px;'>
                                                    <a href="<?= Url::toRoute(["inventario-punto-venta/aplicar_traslado", 'id' => $model->id_inventario, 'id_punto' => $id_punto, 'id_traslado' => $val->id_traslado,'sw' => $sw, 'nuevo_punto' =>$val->id_punto_entrante])?>"
                                                        <span class='glyphicon glyphicon-plus'></span> </a>
                                                </td> 
                                            <?php }else{?>
                                                <td style= 'width: 25px; height: 10px;'></td>
                                                <td style= 'width: 25px; height: 10px;'></td>
                                            <?php } ?>    
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>    
                        </div>
                    </div>    
                </div>
            </div>
        </div> 
        <?php $formulario->end() ?>  
    <?php }?>    
      
</div>    