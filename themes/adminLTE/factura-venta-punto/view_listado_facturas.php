<?php
//clases
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
/* @var $model app\models\Ordenproduccion */

$this->title = 'FACTURAS DE VENTA';
$this->params['breadcrumbs'][] = ['label' => 'Consulta maestro', 'url' => ['search_maestro_pedidos']];
$this->params['breadcrumbs'][] = $model->nombre_completo;
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<div class="pedidos-vista-facturas">

    <div class="panel panel-success">
        <div class="panel-heading">
           REGISTRO DEL VENDEDOR
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <?php if($busqueda == 1){?>
                    <tr style="font-size: 90%;">
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "Documento") ?>:</th>
                        <td><?= Html::encode($model->nit_cedula) ?></td>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Cliente') ?>:</th>
                        <td><?= Html::encode($model->nombre_completo) ?></td>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Vendedor') ?>:</th>
                        <td><?= Html::encode($model->agenteComercial->nombre_completo) ?></td>
                    </tr>
                <?php }else{?>
                    <tr style="font-size: 90%;">
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "Documento") ?>:</th>
                        <td><?= Html::encode($model->nit_cedula) ?></td>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Agente_comercial') ?>:</th>
                        <td><?= Html::encode($model->nombre_completo) ?></td>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Municipio') ?>:</th>
                        <td><?= Html::encode($model->codigoMunicipio->municipio) ?></td>
                    </tr>
                <?php }?>     
            </table>    
        </div>
    </div>  
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#listadofactura" aria-controls="listadofactura" role="tab" data-toggle="tab">Listado de facturas <span class="badge"><?= count($facturas) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="listadofactura">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style="font-size: 90%;">
                                        <th scope="col"  style='background-color:#B9D5CE;'><b>No factura</b></th>                        
                                        <th scope="col"  style='background-color:#B9D5CE;'>Fecha inicio</th>                        
                                        <th scope="col"  style='background-color:#B9D5CE;'>fecha vencimiento</th>       
                                        <?php if($busqueda == 1){?> 
                                            <th scope="col"  style='background-color:#B9D5CE;'>Agente de venta</th>
                                        <?php }else{?>
                                            <th scope="col"  style='background-color:#B9D5CE;'>Nombre del cliente</th>
                                        <?php }?>    
                                        <th scope="col"  style='background-color:#B9D5CE;'>Municipio</th>
                                        <th scope="col"  style='background-color:#B9D5CE;'>Subtotal</th>
                                        <th scope="col"  style='background-color:#B9D5CE;'>Total factura</th>  
                                    </tr>
                                </thead>
                                <body>
                                    <?php
                                    foreach ($facturas as $val):?>
                                       <tr style="font-size: 90%;">
                                           <td><?= $val->numero_factura ?></td>
                                           <td><?= $val->fecha_inicio ?></td>
                                           <td><?= $val->fecha_vencimiento ?></td>
                                           <?php if($busqueda == 1){?>
                                                <td><?= $val->agente->nombre_completo ?></td>
                                           <?php }else{?>
                                                <td><?= $val->clienteFactura->nombre_completo ?></td>
                                           <?php }?>     
                                           <td><?= $val->clienteFactura->codigoMunicipio->municipio ?></td>
                                           <td style="text-align: right"><?= '$'.number_format($val->subtotal_factura,0) ?></td>
                                           <td style="text-align: right"><?= '$'.number_format($val->total_factura,0) ?></td>
                                      </tr>
                                    <?php endforeach;?>          
                               </body>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- TERMINA TABS-->
          
        </div>        
    </div>    
</div>

