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

$this->title = 'LISTAS DE PRECIOS';
$this->params['breadcrumbs'][] = ['label' => 'Inventario producto', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_inventario;
$posicion = ArrayHelper::map(app\models\PosicionPrecio::find()->all(), 'id_posicion', 'posicion');
?>
<div class="orden-produccion-view_precio_venta">

    <!--<?= Html::encode($this->title) ?>-->
    <p>
           <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['crear_precio_venta'], ['class' => 'btn btn-primary btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            INVENTARIO PRODUCTO TERMINADO
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Id') ?></th>
                    <td><?= Html::encode($model->id_inventario) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Codigo') ?></th>
                    <td><?= Html::encode($model->codigo_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Presentacion') ?></th>
                    <td><?= Html::encode($model->nombre_producto) ?></td>
                   
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'costo_unitario') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->costo_unitario,0)) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                   
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'aplica_iva') ?></th>
                    <td><?= Html::encode($model->aplicaIva) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_proceso') ?></th>
                    <td><?= Html::encode($model->fecha_proceso) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_producto') ?></th>
                    <td><?= Html::encode($model->producto->nombre_producto) ?></td>
                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'valor_iva') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->valor_iva,0)) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>
                  
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'unidades_entradas') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->unidades_entradas,0)) ?></td>
                   <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'stock_unidades') ?></th>
                    <td style="text-align: right; background-color:#F5EEF8;"><?= Html::encode(''.number_format($model->stock_unidades,0)) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'subtotal') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->subtotal,0)) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_ean') ?></th>
                    <td><?= Html::encode($model->codigo_ean) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_vencimiento') ?></th>
                    <td><?= Html::encode($model->fecha_vencimiento) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_creacion') ?></th>
                    <td><?= Html::encode($model->fecha_creacion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'total_inventario') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_inventario,0)) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_ean') ?></th>
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'inventario_inicial') ?></th>
                    <td><?= Html::encode($model->inventarioInicial) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'aplica_inventario') ?></th>
                    <td><?= Html::encode($model->aplicaInventario) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'porcentaje_iva') ?></th>
                    <td colspan="3"><?= Html::encode($model->porcentaje_iva) ?></td>
                  
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'descripcion_producto') ?></th>
                    <td colspan="9"><?= Html::encode($model->descripcion_producto)?></td>
                </tr>
                
                
            </table>
        </div>
    </div>
     <?php $form = ActiveForm::begin([
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
            <li role="presentation" class="active"><a href="#listadoprecios" aria-controls="listadoprecios" role="tab" data-toggle="tab">Listado de precios <span class="badge"><?= count($listado_precio)?></span></a></li>
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
                                        <th scope="col" style='background-color:#B9D5CE;'>Presentacion del producto</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Precio venta publico</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Posición</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Iva incluido</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>User name</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha registro</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'></th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($listado_precio as $val):?>
                                        <tr style="font-size: 85%;">
                                            <td><?= $val->consecutivo ?></td>  
                                            <td><?= $val->inventario->nombre_producto ?></td>
                                            <td style="padding-right: 1;padding-right: 0; text-align: right"> <input type="text" name="precio_venta_publico[]" value="<?= $val->precio_venta_publico ?>" style="text-align: right" size="9" required="true"> </td> 
                                            <td style="padding-left: 1;padding-right: 0;"><?= Html::dropDownList('posicion[]', $val->id_posicion, $posicion, ['class' => 'col-sm-10', 'prompt' => 'Seleccione', 'required' => true]) ?></td>
                                            <td align="center"><select name="iva_incluido[]" style="width: 90px">
                                                    <?php if ($val->iva_incluido == 0){
                                                        echo 'Seleccione';
                                                    }else{
                                                        if ($val->iva_incluido == 1){
                                                            echo 'SI';   
                                                        } else {
                                                            echo 'NO';
                                                        }
                                                    }?>    
                                                    <option value="<?= $val->iva_incluido ?>"><?= $val->ivaIncluido ?></option>
                                                    <option value="1">SI</option>
                                                    <option value="2">NO</option>
                                            </select></td>
                                            <td><?= $val->user_name ?></td>
                                            <td><?= $val->fecha_registro ?></td>
                                            <input type="hidden" name="detalle_precios[]" value="<?= $val->consecutivo ?>">
                                            <td style= 'width: 25px; height: 25px;'>
                                                <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle', 'id' => $model->id_inventario, 'detalle' => $val->consecutivo], [
                                                        'class' => '',
                                                        'data' => [
                                                            'confirm' => 'Esta seguro de eliminar el registro?',
                                                            'method' => 'post',
                                                        ],
                                                    ])
                                                   ?>
                                            </td>    
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>      
                            </table>
                        </div>
                        <div class="panel-footer text-right" >  
                            <!-- Inicio Nuevo Detalle proceso -->
                              <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Crear precio',
                                  ['/orden-produccion/nuevo_precio_venta','id' => $model->id_inventario],
                                  [
                                      'title' => 'Crear nuevo precio de venta',
                                      'data-toggle'=>'modal',
                                      'data-target'=>'#modalnuevoprecioventa'.$model->id_inventario,
                                      'class' => 'btn btn-info btn-sm'
                                  ])    
                             ?>
                            <div class="modal remote fade" id="modalnuevoprecioventa<?= $model->id_inventario ?>">
                                <div class="modal-dialog modal-lg" style ="width: 500px;">
                                     <div class="modal-content"></div>
                                </div>
                            </div> 
                            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-warning btn-sm", 'name' => 'actualizar_precio_venta']);?>    
                        </div>   
                </div>
            </div>
            <!--INICIO EL OTRO TABS -->
        </div>
    </div>    
          <?php ActiveForm::end(); ?>  
</div>

