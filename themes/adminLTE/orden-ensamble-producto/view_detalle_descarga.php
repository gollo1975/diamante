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
/* @var $model app\models\Municipio */

$this->title = 'DETALLE DE LA ORDEN';
$this->params['breadcrumbs'][] = ['label' => 'Orden de ensamble', 'url' => ['index_descargar_inventario']];
$this->params['breadcrumbs'][] = $modelo->id_orden_produccion;
?>
<p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index_descargar_inventario'], ['class' => 'btn btn-primary btn-sm']); ?>
</p>    
 <div>
    <ul class="nav nav-tabs" role="tablist">
         <li role="presentation" class="active"><a href="#listado_productos" aria-controls="listado_productos" role="tab" data-toggle="tab">Listado de productos  <span class="badge"><?= count($detalle) ?></span></a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="listado_productos">
            <div class="table-responsive">
                <div class="panel panel-success">
                    <div class="panel-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr style='font-size:85%;'>
                                    <th scope="col" style='background-color:#B9D5CE; '>Codigo</th>                        
                                    <th scope="col" style='background-color:#B9D5CE; '>Presentacion del producto</th> 
                                    <th scope="col" style='background-color:#B9D5CE; '>Cantidad proyectada</th> 
                                    <th scope="col" style='background-color:#B9D5CE; '>Cantidad reales</th> 
                                    <th scope="col" style='background-color:#B9D5CE; '>No de Lote</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach ($detalle as $val): ?>
                                    <tr style='font-size:85%;'>
                                        <td><?= $val->codigo_producto?></td>
                                        <td><?= $val->descripcion?></td>
                                        <td style="text-align: right;"><?= $val->cantidad?></td>
                                        <td style="text-align: right"> <?= $val->cantidad_real ?> </td>
                                        <td><?= $val->numero_lote?></td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>   
                </div>
            </div>
        </div>
    </div>
 </div>     