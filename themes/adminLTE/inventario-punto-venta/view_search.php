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

$this->title = 'Vista (' .$model->nombre_producto. ')';
$this->params['breadcrumbs'][] = ['label' => 'Inventario bodega', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_inventario;
?>
<div class="inventario-punto-venta-view">

    <!--<?= Html::encode($this->title) ?>-->
    <p>
        <?php if($token == 0){
            echo  Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']);
        }else{ 
            echo Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_inventario'], ['class' => 'btn btn-primary btn-sm']);
        }?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            DETALLE DEL INVENTARIO
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <?php if($tokenAcceso == 1){?>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Id') ?></th>
                    <td><?= Html::encode($model->id_inventario) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Codigo') ?></th>
                    <td><?= Html::encode($model->codigo_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre_producto') ?></th>
                    <td><?= Html::encode($model->nombre_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'iva_incluido') ?></th>
                    <td><?= Html::encode($model->ivaIncluido) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'costo_unitario') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->costo_unitario,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_punto') ?></th>
                    <td><?= Html::encode($model->punto->nombre_punto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_proceso') ?></th>
                    <td><?= Html::encode($model->fecha_proceso) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_creacion') ?></th>
                    <td><?= Html::encode($model->fecha_creacion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_marca') ?></th>
                    <td style="text-align: right;"><?= Html::encode($model->marca->marca) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_barra') ?></th>
                    <td><?= Html::encode($model->codigo_barra) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'stock_unidades') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->stock_unidades,0)) ?></td>
                   <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'stock_inventario') ?></th>
                    <td style="text-align: right; background-color:#F5EEF8;"><?= Html::encode(''.number_format($model->stock_inventario,0)) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'inventario_inicial') ?></th>
                    <td><?= Html::encode($model->inventarioInicial) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_categoria') ?></th>
                    <td style="text-align: right;"><?= Html::encode($model->categoria->categoria) ?></td>
                </tr>
                <tr style="font-size: 90%;" >
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'descripcion_producto') ?></th>
                    <td colspan="10"><?= Html::encode($model->descripcion_producto)?></td>
                </tr>
                <?php }else{?>
                    <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Id') ?></th>
                    <td><?= Html::encode($model->id_inventario) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Codigo') ?></th>
                    <td><?= Html::encode($model->codigo_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre_producto') ?></th>
                    <td><?= Html::encode($model->nombre_producto) ?></td>
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_punto') ?></th>
                    <td><?= Html::encode($model->punto->nombre_punto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_categoria') ?></th>
                    <td style="text-align: right;"><?= Html::encode($model->categoria->categoria) ?></td>
                </tr>
                <?php }?>
            </table>
            <!-- TERMINA TABLE-->
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
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#talla_color" aria-controls="talla_color" role="tab" data-toggle="tab">Tallas y colores <span class="badge"><?= count($talla_color) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="talla_color">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Id</th>                      
                                        <th scope="col" style='background-color:#B9D5CE;'>Talla</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Color</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>User_name</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha registro</th> 
                                         <th scope="col" style='background-color:#B9D5CE;'>Cerrado</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Stock</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Cantidad </th> 
                                        <th scope="col" style='background-color:#B9D5CE;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($talla_color as $val):?>
                                        <tr style='font-size:90%;'>
                                            <td><?= $val->id_detalle?></td>
                                            <td style="text-align: right"><?= $val->talla->nombre_talla?></td>
                                            <td><?= $val->color->colores?></td>
                                            <td><?= $val->user_name?></td>
                                            <td><?= $val->fecha_registro?></td>
                                            <td><?= $val->cerradoDetalle?></td>
                                            <td style="text-align: right"><?= $val->stock_punto?></td>
                                            <td style="padding-right: 1; padding-right: 0; text-align: right"><input type="text" name="cantidad[]" style="text-align: right" value="<?= $val->cantidad ?>" size="5" > </td> 
                                            <input type="hidden" name="entrada_cantidad[]" value="<?= $val->id_detalle ?>">
                                            <td style= 'width: 25px; height: 25px;'>
                                                <?php if($val->cerrado == 0){?>
                                                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar', 'id' => $model->id_inventario, 'id_detalle' => $val->id_detalle, 'token' => $token, 'codigo' => $codigo], [
                                                               'class' => '',
                                                               'data' => [
                                                                   'confirm' => 'Esta seguro de eliminar el registro?',
                                                                   'method' => 'post',
                                                               ],
                                                           ])
                                                    ?>
                                                <?php }?>
                                            </td>    
                                        </tr>
                                    <?php endforeach;?>
                                  
                                </tbody>      
                            </table>
                        </div>
                    </div>   
                </div>
            </div>
            <!-- TERMINA TABAS-->
        </div>
    </div>    
  <?php $form->end() ?> 
</div>
  

