<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Contratos */

$this->title = 'CONTRATOS No: ' . $model->id_contrato;
$this->params['breadcrumbs'][] = ['label' => 'Contratos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$view = 'contratos';
?>
<div class="contratos-view">
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_contrato_laboral', 'id' => $model->id_contrato], ['class' => 'btn btn-default btn-sm']); ?>
        <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index', 'numero' => 23, 'codigo' => $model->id_contrato, 'view' => $view, 'token' => $token], ['class' => 'btn btn-default btn-sm']) ?>        
        <?php if ($model->contrato_activo == 0) { ?>
            <!-- Inicio Cerrar contrato -->
            <?=
            Html::a('<span class="glyphicon glyphicon-remove"></span> Cerrar Contrato',
                    ['/contratos/cerrar_contrato_trabajo', 'id' => $model->id_contrato, 'token' => $token],
                    [
                        'title' => 'Cerrar Contrato',
                        'data-toggle' => 'modal',
                        'data-target' => '#modalcerrarcontrato' . $model->id_contrato,
                        'class' => 'btn btn-default btn-sm'
                    ]
            );
            ?>
        <div class="modal remote fade" id="modalcerrarcontrato<?= $model->id_contrato ?>">
            <div class="modal-dialog modal-lg_centered">
                <div class="modal-content"></div>
            </div>
        </div>
    <?php } else { ?>
        <!-- Abrir contrato-->
        <?= Html::a('<span class="glyphicon glyphicon-open"></span> Abrir contrato', ['abrir_contrato_laboral', 'id' => $model->id_contrato, 'token' => $token], ['class' => 'btn btn-default btn-sm']); ?>
<?php } ?>   
    <!-- Fin Cerrar contrato -->
</p>
<div class="panel panel-success">
    <div class="panel-heading">
        Contrato
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped table-hover">
            <tr style ='font-size:85%;'>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_contrato') ?></th>
                <td><?= Html::encode($model->id_contrato) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_contrato') ?>:</th>
                <td><?= Html::encode($model->tipoContrato->contrato) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tiempo') ?></th>
                <td><?= Html::encode($model->tiempo->tiempo_servicio) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_inicio') ?>:</th>
                <td><?= Html::encode($model->fecha_inicio) ?></td>
            </tr>
            <tr style ='font-size:85%;'>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nit_cedula') ?></th>
                <td><?= Html::encode($model->nit_cedula) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_empleado') ?></th>
                <td><?= Html::encode($model->empleado->nombre_completo) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_cargo') ?></th>
                <td><?= Html::encode($model->cargo->nombre_cargo) ?></td>
                <?php if ($model->fecha_final == '2099-12-30') { ?>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_final') ?></th>
                    <td><?= Html::encode('INDEFINIDO') ?></td>
                <?php } else { ?>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_final') ?></th>
                    <td><?= Html::encode($model->fecha_final) ?></td>
                <?php } ?>     
            </tr>
            <tr style ='font-size:85%;'>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_salario') ?></th>
                <td><?= Html::encode($model->tipoSalario->descripcion) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'salario') ?></th>
                <td style="text-align: right"><?= Html::encode('$ ' . number_format($model->salario, 0)) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Auxilio_transporte') ?></th>
                <td><?= Html::encode($model->aplicaAuxilio) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'contrato_activo') ?></th>
                <td><?= Html::encode($model->activo) ?></td>
            </tr>
            <tr style ='font-size:85%;'>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_entidad_salud') ?></th>
                <td><?= Html::encode($model->entidadSalud->entidad_salud) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_entidad_pension') ?></th>
                <td><?= Html::encode($model->entidadPension->entidad) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_grupo_pago') ?></th>
                <td><?= Html::encode($model->grupoPago->grupo_pago) ?></td>  
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_preaviso') ?></th>
                <td><?= Html::encode($model->fecha_preaviso) ?></td>           
            </tr> 
            <tr style ='font-size:85%;'>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_caja_compensacion') ?></th>
                <td><?= Html::encode($model->cajaCompensacion->caja) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_cesantia') ?></th>
                <td><?= Html::encode($model->cesantia->entidad) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_arl') ?></th>
                <td><?= Html::encode($model->arl->descripcion) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'dias_contrato') ?></th>
                <td><?= Html::encode($model->dias_contrato) ?></td>

            </tr>
        </table>
    </div>
</div>  
<?php
$form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
                'options' => []
            ],
        ]);
?> 
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Cambio de salario: <span class="badge"><?= count($cambio_salario) ?></span>
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
                <table class="table table-bordered table-hover">
                    <thead >
                        <tr style ='font-size:85%;'>
                            <th scope="col" style='background-color:#B9D5CE;'><b>Id</b></td>                        
                            <th scope="col" style='background-color:#B9D5CE;'><b>Nuevo salario</b></td>                        
                            <th scope="col" style='background-color:#B9D5CE;'>Fecha_Aplicación</th>                        
                            <th scope="col" style='background-color:#B9D5CE;'>Formato de impresión</th> 
                            <th scope="col" style='background-color:#B9D5CE;'>Fecha proceso</th> 
                            <th scope="col" style='background-color:#B9D5CE;'>Usuario</th>                        
                            <th scope="col" style='background-color:#B9D5CE;'>Observación</th>   
                            <th scope="col" style='background-color:#B9D5CE;'></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cambio_salario as $val): ?>
                            <tr style ='font-size:85%;'>
                                <td><?= $val->id_cambio_salario ?></td>
                                <td><?= '$' . number_format($val->nuevo_salario, 0) ?></td>
                                <td><?= $val->fecha_aplicacion ?></td>
                                <td><?= $val->formatoContenido->nombre_formato ?></td>
                                <td><?= $val->fecha_creacion ?></td>
                                <td><?= $val->user_name ?></td>
                                <td><?= $val->observacion ?></td>
                                <td style= 'width: 25px; height: 20px;'>
                                    <a href="<?= Url::toRoute(["imprimircambiosalario", 'id_cambio_salario' => $val->id_cambio_salario, 'id' => $model->id_contrato]) ?>" ><span class="glyphicon glyphicon-print" title="Imprimir "></span></a>
                                </td>    
                            </tr>
                        <?php endforeach; ?>
                    </tbody>  
                    <?php if ($model->contrato_activo == 0) { ?>
                        <div align="right">  
                            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Cambio de salario', ['contratos/nuevo_cambio_salario', 'id' => $model->id_contrato, 'token' => $token], ['class' => 'btn btn-info btn-sm']) ?>                    
                        </div>
                    <?php } ?>
                </table>  
            </div>
        </div>  
    </div>
    <!--TERMINA EL PRIMER ACORDION-->
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingTwo">
            <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Adicion al contrato: <span class="badge"><?= count($adicion_salario) ?></span>
                </a>
            </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
            <div class="panel-body">
                <table class="table table-bordered table-hover">
                    <thead >
                        <tr style ='font-size:85%;'>
                            <th scope="col" style='background-color:#B9D5CE;'>Id</th>                        
                            <th scope="col" style='background-color:#B9D5CE;'>Valor adicional</th>
                            <th scope="col" style='background-color:#B9D5CE;'>Concepto salarial</th>  
                            <th scope="col" style='background-color:#B9D5CE;'>Fecha aplicación</th>   
                            <th scope="col" style='background-color:#B9D5CE;'>Fecha proceso</th>  
                            <th sscope="col" style='background-color:#B9D5CE;'>Formato impresion</th>  
                            <th scope="col" style='background-color:#B9D5CE;'>Usuario</th>                        
                            <th scope="col" style='background-color:#B9D5CE;'></th>
                            <th scope="col" style='background-color:#B9D5CE;'></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($adicion_salario as $val): ?>
                            <tr style ='font-size:85%;'>
                                <td><?= $val->id_pago_adicion ?></td>
                                <td style = "text-align: right"><?= '$' . number_format($val->valor_adicion, 0) ?></td>
                                <td><?= $val->codigoSalario->nombre_concepto ?></td>
                                <td><?= $val->fecha_aplicacion ?></td>
                                <td><?= $val->fecha_proceso ?></td>
                                <td><?= $val->formatoContenido->nombre_formato ?></td>
                                <td><?= $val->user_name ?></td>
                                <?php if ($val->estado_adicion == 0) { ?>
                                    <td style= 'width: 25px; height: 20px;'>
                                        <a href="<?= Url::toRoute(["contratos/imprimirotrosi", 'id_pago_adicion' => $val->id_pago_adicion]) ?>" ><span class="glyphicon glyphicon-print" title="Imprimir "></span></a>
                                    </td>   
                                   <td style= 'width: 25px; height: 20px;'>
                                        <a href="<?= Url::toRoute(['contratos/editar_pago_adicion', 'id_pago_adicion' => $val->id_pago_adicion, 'id' => $model->id_contrato, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-pencil" title="Editar pago"></span> </a>                   

                                    </td>
                                <?php } else { ?>        
                                   <td style= 'width: 25px; height: 20px;'>
                                        <a href="<?= Url::toRoute(["contratos/imprimir", 'id_pago_adicion' => $val->id_pago_adicion]) ?>" ><span class="glyphicon glyphicon-print" title="Imprimir "></span></a>
                                    </td>  
                                    <td style= 'width: 25px; height: 20px;'></td>
                                <?php } ?>     
                            </tr>
                            <?php endforeach; ?>
                    </tbody>
                        <?php if ($model->contrato_activo == 0) { ?>
                        <div align="right">  
                        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Adicion salarial', ['contratos/nueva_adicion_contrato', 'id' => $model->id_contrato, 'token' => $token], ['class' => 'btn btn-info btn-sm']) ?>                    
                        </div>
                        <?php } ?>
                </table>  
            </div>
        </div>

    </div>
    <!--TERMINA SEGUNDO ACORDION-->
    <?php
    if ($model->tipoContrato->prorroga == 1) { ?>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingThree">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Prorroga al contrato: <span class="badge"><?= count($prorrogas) ?></span>
                    </a>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                <div class="panel-body">
                    <table class="table table-bordered table-responsive">
                        <thead >
                            <tr style ='font-size:90%;'>
                                <th scope="col" style='background-color:#B9D5CE;'>Id</th>                        
                                <th scope="col" style='background-color:#B9D5CE;'>Nueva fecha de inicio</th>                        
                                <th scope="col" style='background-color:#B9D5CE;'>Fecha terminación</th>                        
                                <th scope="col" style='background-color:#B9D5CE;'>Fecha preaviso</th>       
                                <th scope="col" style='background-color:#B9D5CE;'>Dias preaviso</th>                        
                                <th scope="col" style='background-color:#B9D5CE;'>Dias contratados</th> 
                                <th scope="col" style='background-color:#B9D5CE;'>Formato de impresion</th> 
                                <th scope="col" style='background-color:#B9D5CE;'>User name</th> 
                                <th scope="col" style='background-color:#B9D5CE;'></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($prorrogas as $prorroga): ?>
                                <tr style ='font-size:85%;'>
                                    <td><?= $prorroga->id_prorroga_contrato ?></td>
                                    <td><?= $prorroga->fecha_desde ?></td>
                                    <td><?= $prorroga->fecha_hasta ?></td>
                                    <td><?= $prorroga->fecha_preaviso ?></td>
                                    <td><?= $prorroga->dias_preaviso ?></td>
                                    <td><?= $prorroga->dias_contratados ?></td>
                                    <td><?= $prorroga->formatoContenido->nombre_formato ?></td>
                                    <td><?= $prorroga->user_name ?></td>  

                                    <td>
                                        <a href="<?= Url::toRoute(["contratos/imprimirprorroga", 'id_prorroga_contrato' => $prorroga->id_prorroga_contrato]) ?>" ><span class="glyphicon glyphicon-print" title="Imprimir "></span></a>
                                    </td>   
                                </tr>
                            <?php endforeach; ?>
                        </tbody> 
                        <?php
                        if ($model->contrato_activo == 0) {
                            if (count($prorrogas) < 3) {
                                ?>
                                <div align="right">  
                                    <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Renovar contrato', ['contratos/nueva_prorroga', 'id' => $model->id_contrato, 'token' => $token], ['class' => 'btn btn-info btn-sm']) ?>                    
                                </div>
                            <?php } else { ?>
                                <div align="right">  
                                <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Contrato 1 año', ['contratos/nueva_prorroga_ano', 'id' => $model->id_contrato, 'token' => $token], ['class' => 'btn btn-warning btn-sm']) ?>                    
                                </div>
                             <?php }
                        } ?>
                    </table>  
                </div>
            </div>
        </div>  
        <!--TERMINA EL TERCER ACORDION->
    <?php } ?>   
</div>    
<?php $form->end() ?>    
</div>
