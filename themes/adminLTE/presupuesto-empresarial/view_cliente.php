<?php

//modelos
use app\models\Items;
use app\models\SolicitudCompra;
use app\models\SolicitudCompraDetalles;
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

/* @var $this yii\web\View */
/* @var $model app\models\Ordenproduccion */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Presupuesto mensual', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_mensual;
?>

<?php
    //$remision = Remision::find()->where(['=', 'idordenproduccion', $model->idordenproduccion])->one();
?>

<div class="presupuesto-empresarial-view_cliente">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['presupuesto_mensual', 'id' => $model->id_mensual], ['class' => 'btn btn-primary btn-sm']) ?>

            <?php if ($model->autorizado == 0 && $model->cerrado == 0) { ?>
                <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_mensual,'desde'=> $model->fecha_inicio,'hasta' => $model->fecha_corte,'cerrado'=>$model->cerrado, 'id_presupuesto' =>$model->presupuesto->id_presupuesto], ['class' => 'btn btn-default btn-sm']);
            } else {
                if ($model->autorizado == 1 && $model->cerrado == 0){
                    echo Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id' => $model->id_mensual,'desde'=> $model->fecha_inicio,'hasta' => $model->fecha_corte,'cerrado'=>$model->cerrado, 'id_presupuesto' =>$model->presupuesto->id_presupuesto], ['class' => 'btn btn-default btn-sm']);
                      echo Html::a('<span class="glyphicon glyphicon-remove"></span> Cerrar mes', ['cerrar_mes', 'id' => $model->id_mensual],['class' => 'btn btn-warning btn-sm',
                               'data' => ['confirm' => 'Esta seguro de cerrar el presupuesto del '. $model->fecha_inicio.'  al '. $model->fecha_corte.'.', 'method' => 'post']]);
                }else{
                    echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_cierre_mensual', 'id' => $model->id_mensual], ['class' => 'btn btn-default btn-sm']);            
                }
            }?>        
    </p>  
    <div class="panel panel-success">
        <div class="panel-heading">
            PRESUPUESTO MENSUAL
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_mensual") ?></th>
                    <td><?= Html::encode($model->id_mensual) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_presupuesto') ?></th>
                    <td><?= Html::encode($model->presupuesto->descripcion) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Total_gastado') ?></th>
                     <td style="text-align: right;"><?= Html::encode(''.number_format($model->valor_gastado,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_inicio') ?></th>
                    <td><?= Html::encode($model->fecha_inicio) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_corte')?></th>
                    <td><?= Html::encode($model->fecha_corte) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'total_registro') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_registro,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                   
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'autorizado') ?></th>
                    <td><?= Html::encode($model->autorizadoMes) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'cerrado') ?></th>
                    <td><?= Html::encode($model->cerradoMes) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td  colspan="6"><?= Html::encode($model->observacion) ?></td>
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
    <!-- comienza los tabs -->
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#listo_clientes" aria-controls="listo_clientes" role="tab" data-toggle="tab">Clientes <span class="badge"><?= count($detalle) ?></span></a></li>
            <li role="presentation"><a href="#graficaMes" aria-controls="graficaMes" role="tab" data-toggle="tab">Grafica <span class="badge"></span></a></li>
        </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="listo_clientes">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Id</b></th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Cliente</th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>P. gastado</th>       
                                             <th scope="col" align="center" style='background-color:#B9D5CE;'>P. asignado</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Desde</th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Hasta</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Fecha registro</th> 
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($detalle as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->id_detalle ?></td>
                                                <td><?= $val->cliente->nombre_completo ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->gasto_mensual,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->presupuesto_asignado,0) ?></td>
                                                <td><?= $model->fecha_inicio ?></td>
                                                <td><?= $model->fecha_corte ?></td>
                                                <td><?= $val->fecha_hora ?></td>
                                            </tr>  
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                            </div>
                            <div class="panel-footer text-right">  

                            </div>   
                        </div>
                    </div>
                </div>    
                <!-- TERMINA TABS -->
                  <div role="tabpanel" class="tab-pane" id="graficaMes">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <?php 
                                        $ano = $model->presupuesto->año;
                                        $gasto = $model->valor_gastado;
                                        $presupuesto = $model->presupuesto_mensual;
                                        $porcentaje = $model->porcentaje;
                                        include('graficapresupuesto.php'); 
                                        ?>
                                    </thead>   
                                </table>
                            </div>
                        </div>
                    </div>
                </div> 
                <!--termina tabs-->
            </div>  
    </div>
  <?php ActiveForm::end(); ?>  
</div>

   