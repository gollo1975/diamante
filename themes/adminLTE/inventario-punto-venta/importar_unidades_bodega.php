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

$this->title = 'IMPORTAR DE BODEGA (' .$model->nombre_producto. ')';
$this->params['breadcrumbs'][] = ['label' => 'Importar unidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_inventario;
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<div class="inventario-punto-venta-importar">
    <p>
       
      <?=  Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']);?>
        
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            IMPORTAR DE BODEGA
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Codigo') ?></th>
                    <td><?= Html::encode($model->codigo_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre_producto') ?></th>
                    <td><?= Html::encode($model->nombre_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_punto') ?></th>
                    <td><?= Html::encode($model->punto->nombre_punto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'stock_inventario') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->stock_inventario,0)) ?></td>
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
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#listadotallas" aria-controls="listadotallas" role="tab" data-toggle="tab">Tallas y colores <span class="badge"><?= count($talla_color) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="listadotallas">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Id</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Talla</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Color</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Cerrado</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha registro</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Stock</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Cantidad a trasladar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($talla_color as $tallas):?>
                                        <tr style='font-size:90%;'>
                                            <td><?= $tallas->id_detalle?></td>
                                            <td><?= $tallas->talla->nombre_talla?></td>
                                            <td><?= $tallas->color->colores?></td>
                                            <td><?= $tallas->cerradoDetalle?></td>
                                            <td><?= $tallas->fecha_registro?></td>
                                            <td style="text-align: right"><?= ''. number_format($tallas->stock_punto,0)?></td>
                                            <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text" name="cantidades[]" value="" style="text-align: right" size="9"> </td> 
                                             <input type="hidden" name="nuevo_traslado_bodega[]" value="<?= $tallas->id_detalle?>"> 
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-footer text-right">
                            <?= Html::submitButton("<span class='glyphicon glyphicon-send'></span> Enviar traslado", ["class" => "btn btn-primary btn-sm", 'name' => 'traslado_unidades_bodega']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   
     <?php $form->end() ?> 
</div>

