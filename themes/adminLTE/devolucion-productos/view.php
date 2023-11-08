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

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Devolucion productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_devolucion;
$view = 'devolucion-productos';
$tipo_devolucion = ArrayHelper::map(app\models\TipoDevolucionProductos::find()->orderBy ('concepto ASC')->all(), 'id_tipo_devolucion', 'concepto');
?>
<div class="devolucion-productos-view">

    <!--<?= Html::encode($this->title) ?>-->
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }else{ ?>
                <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_devolucion'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }?>
        <?php if ($model->autorizado == 0 && $model->numero_devolucion == 0) { ?>
            <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_devolucion, 'token' =>$token], ['class' => 'btn btn-default btn-sm']);
        }else{
            if ($model->autorizado == 1 && $model->numero_devolucion == 0){?>
                <?= Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id' => $model->id_devolucion, 'token' =>$token], ['class' => 'btn btn-default btn-sm']);?>
                <?= Html::a('<span class="glyphicon glyphicon-book"></span> Generar devolucion', ['generar_devolucion_inventario', 'id' => $model->id_devolucion, 'token' =>$token],['class' => 'btn btn-default btn-sm',
                           'data' => ['confirm' => 'Esta seguro de generar la devolucion de estos productos al cliente  '.$model->cliente->nombre_completo.'.', 'method' => 'post']]);
            }else{
               echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_devolucion_producto', 'id' => $model->id_devolucion], ['class' => 'btn btn-default btn-sm']);             
               echo Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 14, 'codigo' => $model->id_devolucion,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']);               
            }      
        }?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            DEVOLUCION DE PRODUCTOS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Id') ?></th>
                    <td><?= Html::encode($model->id_devolucion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Numero') ?></th>
                    <td><?= Html::encode($model->numero_devolucion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_devolucion') ?></th>
                    <td><?= Html::encode($model->fecha_devolucion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'autorizado') ?></th>
                    <td><?= Html::encode($model->autorizadoProceso) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'cantidad_inventario') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->cantidad_inventario,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Documento') ?></th>
                    <td><?= Html::encode($model->cliente->nit_cedula) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_cliente') ?></th>
                    <td><?= Html::encode($model->cliente->nombre_completo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_registro') ?></th>
                    <td><?= Html::encode($model->fecha_registro) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_nota') ?></th>
                    <td style="text-align: right;"><?= Html::encode($model->id_nota) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td colspan="7"><?= Html::encode($model->observacion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'cantidad_averias') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->cantidad_averias,0)) ?></td>
                
                </tr>
            </table>
        </div>
    </div>
    <!--INICIO LOS TABS-->
    <?php $form = ActiveForm::begin([
    'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
    'fieldConfig' => [
        'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
        'labelOptions' => ['class' => 'col-sm-3 control-label'],
        'options' => []
    ],
    ]);?>
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#detalledevolucion" aria-controls="detalledevolucion" role="tab" data-toggle="tab">Items devolución <span class="badge"><?= count($detalle_devolucion) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="ordenproduccion">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:85%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Id</th>                      
                                        <th scope="col" style='background-color:#B9D5CE;'>Codigo</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Nombre producto</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Unidades</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Cantidad inventario</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Cantidad averias</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Tipo devolución</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'></th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($detalle_devolucion as $val):?>
                                        <tr style="font-size: 90%;">
                                            <td><?= $val->id_detalle ?></td>  
                                            <td><?= $val->codigo_producto ?></td>
                                            <td><?= $val->nombre_producto ?></td>
                                            <td style="text-align: right"><?= ''.number_format($val->cantidad,0)?></td>
                                            <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text" name="cantidad_inventario[]" value="<?= $val->cantidad_devolver?>" style="text-align: right" size="9" required="true"> </td> 
                                            <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text" name="cantidad_averias[]" value="<?= $val->cantidad_averias?>" style="text-align: right" size="9"> </td> 
                                            <td style="padding-right: 1;padding-right: 1;"><?= Html::dropDownList('tipo_devolucion[]', $val->id_tipo_devolucion, $tipo_devolucion, ['class' => 'col-sm-12', 'prompt' => 'Seleccione', 'required' => true]) ?></td>
                                            <input type="hidden" name="actualizar_cantidades[]" value="<?= $val->id_detalle?>">
                                            <?php if($model->autorizado == 0){?>
                                                <td style= 'width: 20px; height: 20px;'>
                                                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle_devolucion', 'id' => $model->id_devolucion, 'detalle' => $val->id_detalle, 'token' => $token], [
                                                            'class' => '',
                                                            'data' => ['confirm' => 'Esta seguro que desea eliminar el registro?',
                                                                      'method' => 'post',
                                                            ],
                                                    ])?>
                                                </td>   
                                            <?php }else{ ?> 
                                                <td style= 'width: 20px; height: 20px;'></td>
                                            <?php } ?>    
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>      
                            </table>
                             <?php if($model->autorizado == 0){?>
                                <div class="panel-footer text-right">  
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizacantidades'])?>
                                </div>
                             <?php }?>
                        </div>
                    </div>   
                </div>
            </div>
            <!--INICIO EL OTRO TABS -->
        </div>
    </div>    
    <?php ActiveForm::end(); ?>
</div>
