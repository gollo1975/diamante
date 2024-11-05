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

$this->title = 'Desabastecimiento';
$this->params['breadcrumbs'][] = ['label' => 'Desabastecimiento', 'url' => ['search_desabastecimiento']];
$this->params['breadcrumbs'][] = $id_inventario;
?>
<div class="operarios-view">

    <!--<?= Html::encode($this->title) ?>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_desabastecimiento'], ['class' => 'btn btn-primary btn-sm']) ?>
   </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            Presentacion del producto
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_producto') ?></th>
                    <td><?= Html::encode($model->codigo_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre_producto') ?></th>
                    <td><?= Html::encode($model->nombre_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_producto') ?></th>
                    <td><?= Html::encode($model->producto->nombre_producto) ?></td>
                </tr>
            </table>
        </div>
    </div>
   <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#listadofaltante" aria-controls="listadofaltante" role="tab" data-toggle="tab">Listado <span class="badge"><?= count($listado) ?></span></a></li>
        </ul>
        
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="ordenproduccion">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:85%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Numero de pedido</th>  
                                        <th scope="col" style='background-color:#B9D5CE;'>Codigo producto</th>                      
                                        <th scope="col" style='background-color:#B9D5CE;'>Presentacion del producto</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Cliente</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Agente</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>C. vendida</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>C. faltante</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha pedido</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha despacho</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($listado as $key => $listados) {?>
                                        <tr style='font-size:85%;'>
                                            <td><?= $listados->pedido->numero_pedido?></td>
                                            <td><?= $listados->inventario->codigo_producto?></td>
                                            <td><?= $listados->inventario->nombre_producto?></td>
                                            <td><?= $listados->pedido->clientePedido->nombre_completo?></td>
                                            <td><?= $listados->pedido->clientePedido->agenteComercial->nombre_completo?></td>
                                            <td style = "text-align: right"><?= $listados->cantidad?></td>
                                            <td style = "text-align: right"><?= $listados->cantidad_faltante?></td>
                                            <td ><?= $listados->pedido->fecha_proceso?></td>
                                            <td><?= $listados->pedido->fecha_entrega?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>   
</div>   