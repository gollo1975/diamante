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
$this->params['breadcrumbs'][] = ['label' => 'Recibo de caja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_recibo;
$view = 'recibo-caja';
?>
<div class="recibo-caja-view">

    <!--<?= Html::encode($this->title) ?>-->
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }else{?>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_clientes'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }?> 
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
           CLIENTES
        </div>
        <div class="panel-body">
                 <table class="table table-bordered table-striped table-hover">
                    <tr style="font-size: 90%;">
                        <th style='background-color:#F0F3EF;'>Numero recibo:</th>
                        <td><?= $model->numero_recibo ?></td>
                        <th style='background-color:#F0F3EF;'>Tipo recibo:</th>
                        <td><?= $model->tipo->concepto ?></td>
                    </tr>
                     <tr style="font-size: 90%;">
                        <th style='background-color:#F0F3EF;' >Fecha pago:</th>
                        <td><?= $model->fecha_pago ?></td>
                        <th style='background-color:#F0F3EF;' >Valor pago:</th>
                        <td style="text-align: right"><?= ''.number_format($model->valor_pago,0) ?></td>
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
            <li role="presentation" class="active"><a href="#detallafacturas" aria-controls="detallafacturas" role="tab" data-toggle="tab">Detalle facturas  <span class="badge"><?= count($detalle_recibo) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="detallafacturas">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>No factura</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha inicio</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha vcto</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Saldo</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Abono</th> 
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($detalle_recibo as $detalle):?>
                                    <tr style='font-size:90%;'>
                                        <td> <?= $detalle->numero_factura?></td>
                                         <td> <?= $detalle->facturaRecibo->fecha_inicio?></td>
                                        <td> <?= $detalle->facturaRecibo->fecha_vencimiento?></td>
                                        <td style="text-align: right"> <?= '$'.number_format($detalle->saldo_factura,0)?></td>
                                        <td style="padding-right: 1;padding-right: 0; text-align: right"> <input type="text" name="abono_factura[]" value="<?= $detalle->saldo_factura?>" style="text-align: right" size="9" required="true"> </td> 
                                        <input type="hidden" name="actualizar_saldo[]" value="<?= $detalle->id_detalle?>">  
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>      
                            </table>
                        </div>
                        <?php if($token == 0){?>
                            <div class="panel-footer text-right" >  
                               <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-warning btn-sm", 'name' => 'actualizasaldos']);?>    
                               <?= Html::a('<span class="glyphicon glyphicon-search"></span>Facturas', ['recibo-caja/buscar_facturas', 'id' => $model->id_recibo, 'id_cliente' =>$model->id_cliente, 'token' => $token, 'tokenAcceso' => $tokenAcceso],[ 'class' => 'btn btn-success btn-sm']) ?>                                            
                            </div>     
                        <?php }?>
                    </div>   
                </div>
            </div>
            <!--FIN-->

        </div>
    </div> 
    <?php ActiveForm::end(); ?>  
</div>
