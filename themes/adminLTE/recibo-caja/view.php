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
        <?php if ($model->autorizado == 0 && $model->recibo_cerrado == 0) { ?>
            <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado_admon', 'id' => $model->id_recibo, 'token' =>$token], ['class' => 'btn btn-default btn-sm']);
        }else{
            if ($model->autorizado == 1 && $model->recibo_cerrado == 0){?>
                <?= Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado_admon', 'id' => $model->id_recibo, 'token' =>$token], ['class' => 'btn btn-default btn-sm']);?>
                <?= Html::a('<span class="glyphicon glyphicon-book"></span> Generar recibo', ['generar_recibo_pago_admon', 'id' => $model->id_recibo, 'token' =>$token],['class' => 'btn btn-default btn-sm',
                           'data' => ['confirm' => 'Esta seguro de generar el recibo de pago al cliente '.$model->cliente.'.', 'method' => 'post']]);
            }else{
               echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_recibo_caja', 'id' => $model->id_recibo,'token' => $token], ['class' => 'btn btn-default btn-sm']);             
               echo Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 13, 'codigo' => $model->id_recibo,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']);               
            }      
        }?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
           CLIENTES
        </div>
        <div class="panel-body">
                <table class="table table-bordered table-striped table-hover">
                    <tr style="font-size: 90%;">
                        <th style='background-color:#F0F3EF;'>Id:</th>
                        <td><?= $model->id_recibo ?></td>
                        <th style='background-color:#F0F3EF;'>Numero recibo:</th>
                        <td><?= $model->numero_recibo ?></td>
                        <th style='background-color:#F0F3EF;'>Tipo recibo:</th>
                        <td><?= $model->tipo->concepto ?></td>
                        <th style='background-color:#F0F3EF;' >Fecha_pago:</th>
                        <td><?= $model->fecha_pago ?></td>
                        <th style='background-color:#F0F3EF;' >Valor pago:</th>
                        <td style="text-align: right"><?= ''.number_format($model->valor_pago,0) ?></td>
                    </tr>
                    <tr style="font-size: 90%;">
                        <th style='background-color:#F0F3EF;'>Tipo documento:</th>
                        <td><?= $model->clienteRecibo->tipoDocumento->tipo_documento ?></td>
                        <th style='background-color:#F0F3EF;'>Documento:</th>
                        <td><?= $model->clienteRecibo->nit_cedula ?></td>
                        <th style='background-color:#F0F3EF;'>Cliente</th>
                        <td><?= $model->cliente ?></td>
                        <th style='background-color:#F0F3EF;'>Departamento</th>
                        <td><?= $model->codigoMunicipio->codigoDepartamento->departamento ?></td>
                        <th style='background-color:#F0F3EF;' >Municipio:</th>
                        <td><?= $model->codigoMunicipio->municipio?></td>
                    </tr>
                     <tr style="font-size: 90%;">
                        <th style='background-color:#F0F3EF;'>Banco:</th>
                        <td><?= $model->codigoBanco->entidad_bancaria ?></td>
                        <th style='background-color:#F0F3EF;'>Fecha_proceso:</th>
                        <td><?= $model->fecha_proceso ?></td>
                        <th style='background-color:#F0F3EF;'>User_name:</th>
                        <td><?= $model->user_name ?></td>
                        <th style='background-color:#F0F3EF;'>Autorizado:</th>
                        <td><?= $model->autorizadoRecibo ?></td>
                        <th style='background-color:#F0F3EF;'></th>
                        <td></td>
                    </tr>
                    <tr style="font-size: 90%;">
                        <th style='background-color:#F0F3EF;'>Observación:</th>
                        <td colspan="9"><?= $model->observacion ?></td>
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
                                        <th scope="col" style='background-color:#B9D5CE;'></th> 
                                        
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
                                        <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text" name="abono_factura[]" value="<?= $detalle->abono_factura?>" style="text-align: right" size="9" required="true"> </td> 
                                        <input type="hidden" name="actualizar_recibo_caja[]" value="<?= $detalle->id_detalle?>">  
                                        <td style= 'width: 25px; height: 25px;'>
                                            <?php if($model->autorizado == 0){?>
                                                <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_factura', 'id' => $model->id_recibo, 'detalle' => $detalle->id_detalle, 'token' => $token], [
                                                              'class' => '',
                                                              'data' => [
                                                                  'confirm' => 'Esta seguro de eliminar el registro?',
                                                                  'method' => 'post',

                                                              ],
                                                          ])?>

                                                <?php }?>         
                                        </td>     
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>      
                            </table>
                        </div>
                          <?php if($token == 0 && $model->autorizado == 0){?>
                            <div class="panel-footer text-right" >  
                               <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-warning btn-sm", 'name' => 'actualizarecibo']);?>    
                               <?= Html::a('<span class="glyphicon glyphicon-search"></span>Facturas', ['recibo-caja/buscar_facturas_admon', 'id' => $model->id_recibo, 'id_cliente' =>$model->id_cliente, 'token' => $token],[ 'class' => 'btn btn-success btn-sm']) ?>                                            
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
