<?php
use app\models\FormatoContenido;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Contrato */

$this->title = 'PARAMETROS DEL CONTRATO';
$this->params['breadcrumbs'][] = ['label' => 'Contratos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_contrato;
$view = 'contrato';
?>
<div class="contrato-viewParameters">
    <!--<?= Html::encode($this->title) ?>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['parametro_contrato'], ['class' => 'btn btn-primary btn-sm']) ?>
        <!-- parametros -->
         <?= Html::a('<span class="glyphicon glyphicon-cog"></span> Parametros..',            
             ['/contratos/acumulado_devengado','id' => $model->id_contrato],
             [
                 'title' => 'parametro de acumulado',
                 'data-toggle'=>'modal',
                 'data-target'=>'#modalacumuladodevengado'.$model->id_contrato,
                 'class' => 'btn btn-info btn-sm'
             ]
         );
         ?>
        <div class="modal remote fade" id="modalacumuladodevengado<?= $model->id_contrato ?>">
            <div class="modal-dialog modal-lg">
                <div class="modal-content"></div>
            </div>
        </div>
    </p>    
    <div class="panel panel-success">
        <div class="panel-heading">
            Parametros del contrato
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style ='font-size:85%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_contrato') ?></th>
                    <td><?= Html::encode($model->id_contrato) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tiempo') ?></th>
                    <td><?= Html::encode($model->tiempo->tiempo_servicio) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_contrato') ?></th>
                    <td><?= Html::encode($model->tipoContrato->contrato) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_inicio') ?></th>
                    <td><?= Html::encode($model->fecha_inicio) ?></td>
                </tr>
                <tr style ='font-size:85%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_centro_trabajo') ?></th>
                    <td><?= Html::encode($model->centroTrabajo->centro_trabajo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_cargo') ?></th>
                    <td><?= Html::encode($model->cargo->nombre_cargo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'descripcion') ?></th>
                    <td><?= Html::encode($model->descripcion) ?></td>
                    <?php
                    if($model->fecha_final == '2099-12-31'){?>
                         <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_final') ?></th>
                         <td><?= Html::encode('INDEFINIDO') ?></td>
                    <?php }else{?>
                         <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_final') ?></th>
                         <td><?= Html::encode($model->fecha_final) ?></td>
                    <?php }?>     
                </tr>
                <tr style ='font-size:85%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_preaviso') ?></th>
                    <td><?= Html::encode($model->fecha_preaviso) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_grupo_pago') ?></th>
                    <td><?= Html::encode($model->grupoPago->grupo_pago) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'auxilio_transporte') ?></th>
                    <td><?= Html::encode($model->aplicaAuxilio) ?></td>  
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'horario_trabajo') ?></th>
                    <td><?= Html::encode($model->horario_trabajo) ?></td>           
                </tr>                
                <tr style ='font-size:85%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'tipo_salario') ?></th>
                    <td><?= Html::encode($model->tipoSalario->descripcion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'salario') ?></th>
                    <td><?= Html::encode('$ '.number_format($model->salario,0)) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_cotizante') ?></th>
                    <td><?= Html::encode($model->tipoCotizante->tipo) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_subtipo_cotizante') ?></th>
                    <td><?= Html::encode($model->subtipoCotizante->subtipo) ?></td>
                                     
                             
                </tr>
                <tr style ='font-size:85%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_entidad_salud') ?></th>
                    <td><?= Html::encode($model->entidadSalud->entidad_salud) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_entidad_pension') ?></th>
                    <td><?= Html::encode($model->entidadPension->entidad) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_caja_compensacion') ?></th>
                    <td><?= Html::encode($model->cajaCompensacion->caja) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_cesantia') ?></th>
                    <td><?= Html::encode($model->cesantia->entidad) ?></td>
                </tr>
                <tr style ='font-size:85%;'>
                   
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_arl') ?> %</th>
                    <td><?= Html::encode($model->arl->descripcion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'contrato_activo') ?></th>
                    <td><?= Html::encode($model->activo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>
                    <th style='background-color:#F0F3EF;'></th>
                    <td></td>
                    
                </tr>
                <tr style ='font-size:85%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_ciudad_laboral') ?></th>
                    <td><?= Html::encode($model->codigoMunicipioLaboral->municipio) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'ultimo_pago_nomina') ?></th>
                    <td><?= Html::encode($model->ultimo_pago_nomina) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'ultima_pago_prima') ?></th>
                    <td><?= Html::encode($model->ultima_pago_prima) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Usuario_Editado') ?></th>
                    <td><?= Html::encode($model->user_name_editado) ?></td>
                </tr>                
                <tr style ='font-size:85%;'>
                  
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'ultima_pago_cesantia') ?></th>
                    <td><?= Html::encode($model->ultima_pago_cesantia) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'ultima_pago_vacacion') ?></th>
                    <td><?= Html::encode($model->ultima_pago_vacacion) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Genera_prorroga') ?></th>
                    <td><?= Html::encode($model->tipoContrato->prorrogacontrato) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'dias_contrato') ?></th>
                    <td><?= Html::encode($model->dias_contrato) ?></td>
                </tr>
                <tr style ='font-size:85%;'>
                    
                    
                </tr>
                <tr style ='font-size:85%;'>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_municipio_laboral') ?></th>
                    <td><?= Html::encode($model->codigoMunicipioLaboral->municipio) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nit_cedula') ?></th>
                    <td><?= Html::encode($model->nit_cedula) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_empleado') ?></th>
                     <td><?= Html::encode($model->empleado->nombre_completo) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_municipio_contratado') ?></th>
                    <td><?= Html::encode($model->codigoMunicipioContratado->municipio) ?></td>
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
            <!-- INICIO DEL TABS-->
            <ul class="nav nav-tabs" role="tablist">
               <?php $con = count($cambioeps);
                $conPension = count($cambiopension);
                $conFecha = 1;
                $conGrupo = 1;?>
                <li role="presentation" class="active"><a href="#cambioeps" aria-controls="cambioeps" role="tab" data-toggle="tab">Cambio Eps <span class="badge"><?= $con ?></span></a></li>
                <li role="presentation"><a href="#cambiopension" aria-controls="cambiopension" role="tab" data-toggle="tab">Cambio pensión <span class="badge"><?= $conPension ?></span></a></li>

            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="cambioeps">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead >
                                        <tr style='font-size:85%;'>
                                            <th scope="col" style='background-color:#B9D5CE;'><b>Código</b></th>                        
                                            <th scope="col" style='background-color:#B9D5CE;'><b>Eps Anterior</b></th>                        
                                            <th scope="col" style='background-color:#B9D5CE;'>Nueva Eps</th> 
                                            <th scope="col" style='background-color:#B9D5CE;'>Fecha/Hora</th> 
                                            <th scope="col" style='background-color:#B9D5CE;'>Nota</th> 
                                            <th scope="col" style='background-color:#B9D5CE;'><b>Usuario</b></th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                       <?php
                                        foreach ($cambioeps as $eps):?>
                                            <tr style='font-size:85%;'>
                                                 <td><?= $eps->id_cambio ?></td>
                                                <td><?= $eps->entidadSaludAnterior->entidad_salud?></td>
                                                <td><?= $eps->entidadSaludNueva->entidad_salud ?></td>
                                                <td><?= $eps->fecha_cambio ?></td>
                                                <td><?= $eps->observacion ?></td>
                                                <td><?= $eps->user_name ?></td>
                                        <?php endforeach; ?>    
                                    </tbody>  
                                </table>
                                 <div class="panel-footer text-right">
                                    <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Crear', ['contratos/cambioeps', 'id' => $model->id_contrato], ['class' => 'btn btn-primary btn-sm']) ?>
                                 </div>
                            </div>
                        </div>    
                    </div>
                     
                </div>    
                <!--TERMINA EL TABS-->
                <div role="tabpanel" class="tab-pane" id="cambiopension">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead >
                                        <tr style='font-size:85%;'>
                                            <th scope="col" style='background-color:#B9D5CE;'><b>Código</b></th>                        
                                            <th scope="col" style='background-color:#B9D5CE;'><b>Pensión Anterior</b></th>                        
                                            <th scope="col" style='background-color:#B9D5CE;'>Nuevo fondo de pension</th> 
                                            <th scope="col" style='background-color:#B9D5CE;'>Fecha/Hora</th> 
                                            <th scope="col" style='background-color:#B9D5CE;'>Nota</th> 
                                            <th scope="col" style='background-color:#B9D5CE;'><b>Usuario</b></th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                       <?php
                                        foreach ($cambiopension as $pension):?>
                                            <tr style='font-size:85%;'>
                                                 <td><?= $pension->id_cambio ?></td>
                                                <td><?= $pension->entidadPensionAnterior->entidad?></td>
                                                <td><?= $pension->entidadPensionNueva->entidad ?></td>
                                                <td><?= $pension->fecha_cambio ?></td>
                                                <td><?= $pension->observacion ?></td>
                                                <td><?= $pension->user_name ?></td>
                                        <?php endforeach; ?>    
                                    </tbody>  
                                </table>
                                 <div class="panel-footer text-right">
                                    <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Crear', ['contratos/cambiopension', 'id' => $model->id_contrato], ['class' => 'btn btn-primary btn-sm']) ?>
                                 </div>
                            </div>
                        </div>    
                    </div>
                </div>  
                <!-- TERMINA EL TABS-->
            </div>    
        </div>    
   <?php ActiveForm::end(); ?>      
</div>    
  
    


