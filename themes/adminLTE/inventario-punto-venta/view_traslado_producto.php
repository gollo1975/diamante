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
   
    <?php if($sw == 0){
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
            <li role="presentation" class="active"><a href="#tallas_colores" aria-controls="tallas_colores" role="tab" data-toggle="tab">Tallas y colores <span class="badge"><?= count($talla_color) ?></span></a></li>
            <li role="presentation"><a href="#traslado_referencia" aria-controls="traslado_referencia" role="tab" data-toggle="tab">Traslado de referencias <span class="badge"><?= count($asignacion) ?></span></a></li>
         </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="tallas_colores">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr style='font-size:90%;'>
                                    <th scope="col" style='background-color:#B9D5CE;'>Id</th> 
                                    <th scope="col" style='background-color:#B9D5CE;'>Descripcion talla</th>                      
                                    <th scope="col" style='background-color:#B9D5CE;'>Nombre del color</th> 
                                    <th scope="col" style='background-color:#B9D5CE;'>Existencias</th> 
                                     <th scope="col" style='background-color:#B9D5CE;'>Nuevo punto de venta</th>
                                    <th scope="col" style='background-color:#B9D5CE;'>Cantidad a trasladar</th> 

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($talla_color  as $val):?>
                                    <tr style='font-size:90%;'>
                                        <td><?= $val->id_detalle?></td>
                                        <td><?= $val->talla->nombre_talla?></td>
                                        <td><?= $val->color->colores?></td>
                                        <td style="text-align: right"><?= ''. number_format($val->stock_punto,0)?></td>
                                        <td style="padding-left: 1;padding-right: 0;"><?= Html::dropDownList('nuevo_punto[]',$model->id_punto, $conPunto, ['class' => 'col-sm-10', 'prompt' => 'Seleccione', 'required' => true]) ?></td>
                                        <td style="padding-right: 1; padding-right: 1; text-align: right"> <input type="text" name="cantidad_trasladar[]" style="text-align: right" size="9" > </td> 
                                        <input type="hidden" name="nuevo_traslado_punto[]" value = "<?= $val->id_detalle?>">
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer text-right">
                        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Traslada", ["class" => "btn btn-success btn-sm", 'name' => 'enviar_traslado']) ?>        
                    </div>
                </div>
            </div>
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
                                            <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_traslado', 'id' => $model->id_inventario, 'id_punto' => $id_punto, 'id_traslado' => $val->id_traslado], [
                                                       'class' => '',
                                                       'data' => [
                                                           'confirm' => 'Esta seguro de eliminar el registro?',
                                                           'method' => 'post',
                                                       ],
                                                   ])
                                            ?>
                                        </td> 
                                        <td style= 'width: 25px; height: 10px;'>
                                            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ', ['inventario-punto-venta/aplicar_traslado', 'id' => $model->id_inventario, 'id_punto' => $id_punto, 'id_traslado' => $val->id_traslado], [
                                                          'class' => '',
                                                          'title' => 'Proceso que permite cargar estas existencias a la referencia seleccionada.', 
                                                          'data' => [
                                                              'confirm' => 'Esta seguro de aplicar el traslado de la referencia:  ('.$model->nombre_producto.') al punto de venta de ('.$val->puntoEntrante->nombre_punto.').',
                                                              'method' => 'post',
                                                          ],
                                            ]);?>
                                        </td> 
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>    
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
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-send'></span> Enviar", ["class" => "btn btn-primary btn-sm",]) ?>
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
                                                <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ', ['inventario-punto-venta/aplicar_traslado', 'id' => $model->id_inventario, 'id_punto' => $id_punto, 'id_traslado' => $val->id_traslado,'sw' => $sw], [
                                                              'class' => '',
                                                              'title' => 'Proceso que permite cargar estas existencias a la referencia seleccionada.', 
                                                              'data' => [
                                                                  'confirm' => 'Esta seguro de aplicar el traslado de la referencia:  ('.$model->nombre_producto.') al punto de venta de ('.$val->puntoEntrante->nombre_punto.').',
                                                                  'method' => 'post',
                                                              ],
                                                ]);?>
                                            </td> 
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