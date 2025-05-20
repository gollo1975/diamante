<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

$this->title = 'EMPLEADO: ' .$model->nombre_completo;
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_empleado;
$view = 'empleados';
?>
<div class="empleados-view">

    <!--<?= Html::encode($this->title) ?>-->

    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
            <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 10, 'codigo' => $model->id_empleado,'view' => $view, 'token' => $token], ['class' => 'btn btn-default btn-sm']) ?>
        <?php }else{?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['indexconsulta'], ['class' => 'btn btn-primary btn-sm']) ?>
            <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 22, 'codigo' => $model->id_empleado,'view' => $view, 'token' => $token], ['class' => 'btn btn-default btn-sm']) ?>
        <?php }?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            Detalle
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_empleado') ?></th>
                    <td><?= Html::encode($model->id_empleado) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_documento') ?></th>
                    <td><?= Html::encode($model->tipoDocumento->tipo_documento) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nit_cedula') ?></th>
                    <td><?= Html::encode($model->nit_cedula) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'dv') ?></th>
                    <td><?= Html::encode($model->dv) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'tipo_empleado') ?></th>
                    <td><?= Html::encode($model->tipoEmpleado->descripcion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_expedicion_documento') ?></th>
                    <td><?= Html::encode($model->fecha_expedicion_documento) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_municipio_expedicion') ?></th>
                    <td><?= Html::encode($model->codigoMunicipioExpedicion->municipio) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_grupo') ?></th>
                    <td><?= Html::encode($model->grupo->clasificacion) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre1') ?></th>
                    <td><?= Html::encode($model->nombre1) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre2') ?></th>
                    <td><?= Html::encode($model->nombre2) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'apellido1') ?></th>
                    <td><?= Html::encode($model->apellido1) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'apellido2') ?></th>
                    <td><?= Html::encode($model->apellido2) ?></td>                    
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'direccion') ?></th>
                    <td><?= Html::encode($model->direccion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'telefono') ?></th>
                    <td><?= Html::encode($model->telefono) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_departamento_residencia') ?></th>
                    <td><?= Html::encode($model->codigoDepartamentoResidencia->departamento) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_municipio_residencia') ?></th>
                    <td><?= Html::encode($model->codigoMunicipioResidencia->municipio) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'barrio') ?></th>
                    <td><?= Html::encode($model->barrio) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'celular') ?></th>
                    <td><?= Html::encode($model->celular) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'genero') ?></th>
                    <td><?= Html::encode($model->generoEmpleado) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'estado_civil') ?></th>
                    <td><?= Html::encode($model->estadoCivil) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_nacimiento') ?></th>
                    <td><?= Html::encode($model->fecha_nacimiento) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_municipio_nacimiento') ?></th>
                    <td><?= Html::encode($model->codigoMunicipioNacimiento->municipio) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'padre_familia') ?></th>
                    <td><?= Html::encode($model->padreFamilia) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'cabeza_hogar') ?></th>
                    <td><?= Html::encode($model->cabezaHogar) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'discapacidad') ?></th>
                    <td><?= Html::encode($model->discapacidadEmpleado) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_banco') ?></th>
                    <td><?= Html::encode($model->banco->entidad) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'tipo_cuenta') ?></th>
                    <td><?= Html::encode($model->tipoCuenta) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_cuenta') ?></th>
                    <td><?= Html::encode($model->numero_cuenta) ?></td>
                    </td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name_editado') ?>:</th>
                    <td><?= Html::encode($model->user_name_editado) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td colspan="3"><?= Html::encode($model->observacion) ?></td>  
                    
                </tr>
               
            </table>
        </div>
    </div>
    <!--INICIO LOS TABS-->
    <div>
        <ul class="nav nav-tabs" role="tablist">
           
            <li role="presentation" class="active"><a href="#contrato" aria-controls="contrato" role="tab" data-toggle="tab">Contratos <span class="badge"><?= count($contrato) ?></span></a></li>
            <li role="presentation"><a href="#incapacidad" aria-controls="incapacidad" role="tab" data-toggle="tab">Incapacidades <span class="badge"><?= count($incapacidad) ?></span></a></li>
            <li role="presentation"><a href="#licencia" aria-controls="licencia" role="tab" data-toggle="tab">Licencias <span class="badge"><?= count($licencias) ?></span></a></li>
            <li role="presentation"><a href="#credito" aria-controls="licencia" role="tab" data-toggle="tab">Créditos <span class="badge"><?= count($creditos) ?></span></a></li>
            <li role="presentation"><a href="#estudio" aria-controls="estudio" role="tab" data-toggle="tab">Estudios <span class="badge"><?= count($estudios) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="contrato">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:85%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Número</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Tipo contrato</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Tiempo</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Grupo pago</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha inicio</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha final</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Cargo</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Salario</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Activo</th>
                                        <th scope="col" style='background-color:#B9D5CE;'></th>    
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                     foreach ($contrato as $valor):?>
                                        <tr style='font-size:85%;'>
                                            <td><?= $valor->id_contrato ?></td>
                                            <td><?= $valor->tipoContrato->abreviatura?></td>
                                            <td><?= $valor->tiempo->tiempo_servicio ?></td>
                                            <td><?= $valor->grupoPago->grupo_pago ?></td>
                                            <td><?= $valor->fecha_inicio ?></td>
                                            <?php if($valor->fecha_final == '2099-12-30'){?>
                                                <td style='background-color:#B9D5CE;'><?= 'INDEFINIDO'?></td>
                                            <?php }else{?>
                                                <td style='background-color:#B9D5DE;'><?= $valor->fecha_final ?></td>
                                            <?php } ?>                                                
                                            
                                            <td><?= $valor->cargo->nombre_cargo ?></td>
                                            <td align="right"><?= '$'.number_format($valor->salario,0) ?></td>
                                            <td><?= $valor->activo ?></td>
                                            <td style="width: 20px; height: 20px">	
                                            <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>',            
                                                    ['/contratos/detalle_contrato','id_contrato' => $valor->id_contrato],
                                                    [
                                                        'title' => 'Detalle del contrato',
                                                        'data-toggle'=>'modal',
                                                        'data-target'=>'#modaldetallecontrato'.$valor->id_contrato,
                                                        'class' => ''
                                                    ]
                                                );
                                                ?>
                                               <div class="modal remote fade" id = "modaldetallecontrato<?= $valor->id_contrato ?>">
                                                   <div class="modal-dialog modal-lg">
                                                       <div class="modal-content"></div>
                                                   </div>
                                               </div>
                                            </td>    
                                        </tr>
                                   <?php endforeach; ?>    
                                </tbody>      
                            </table>
                        </div>
                    </div>   
                </div>
            </div>
            <!--INICIO EL OTRO TABS -->
            <div role="tabpanel" class="tab-pane" id="incapacidad">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:85%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Número</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Tipo</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Codigo</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha inicio</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha final</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Dias</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Medico</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>No contrato</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Grupo pago</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Salario</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Vlr_Incapacidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($incapacidad as $valor):?>
                                        <tr style='font-size:85%;'>
                                            <td><?= $valor->numero_incapacidad ?></td>
                                            <td><?= $valor->codigoIncapacidad->nombre?></td>
                                            <td><?= $valor->codigo_diagnostico ?></td>
                                            <td><?= $valor->fecha_inicio ?></td>
                                            <td><?= $valor->fecha_final ?></td>
                                            <td><?= $valor->dias_incapacidad ?></td>
                                            <td><?= $valor->nombre_medico ?></td>
                                            <td><?= $valor->id_contrato ?></td>
                                            <td><?= $valor->grupoPago->grupo_pago ?></td>
                                            <td style="text-align: right"><?= '$'.number_format($valor->salario,0) ?></td>
                                            <td style="text-align: right"><?= '$'.number_format($valor->vlr_liquidado,0) ?></td>
                                            
                                        </tr>
                                   <?php endforeach; ?>    
                                </tbody>      
                            </table>
                        </div>
                    </div>   
                </div>
            </div>
            <!--INICIO EL OTRO TABS -->
            <div role="tabpanel" class="tab-pane" id="licencia">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:85%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Número</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Tipo</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Nro contrato</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha inicio</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha final</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Dias</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Grupo pago</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Salario</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Vlr_licencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                     foreach ($licencias as $valor):?>
                                        <tr style='font-size:85%;'>
                                            <td><?= $valor->id_licencia_pk ?></td>
                                            <td><?= $valor->codigoLicencia->concepto?></td>
                                            <td><?= $valor->id_contrato ?></td>
                                            <td><?= $valor->fecha_desde ?></td>
                                            <td><?= $valor->fecha_hasta ?></td>
                                            <td><?= $valor->dias_licencia ?></td>
                                            <td><?= $valor->grupoPago->grupo_pago ?></td>
                                            <td style="text-align: right"><?= '$'.number_format($valor->salario,0) ?></td>
                                            <td style="text-align: right"><?= '$'.number_format($valor->vlr_licencia,0) ?></td>
                                        </tr>
                                   <?php endforeach; ?>    
                                </tbody>      
                            </table>
                        </div>
                    </div>   
                </div>
            </div>
            <!--INICIO EL OTRO TABS -->
            <div role="tabpanel" class="tab-pane" id="credito">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:85%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Número</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Tipo_crédito</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Forma pago</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha inicio</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Vr. Crédito</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Vr. Cuota</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Nro cuotas</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Saldo</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Grupo pago</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($creditos as $valor):?>
                                        <tr style='font-size:85%;'>
                                            <td><?= $valor->id_credito ?></td>
                                            <td><?= $valor->codigoCredito->nombre_credito?></td>
                                            <td><?= $valor->tipoPago->descripcion ?></td>
                                            <td><?= $valor->fecha_inicio ?></td>
                                            <td style="text-align: right"><?= '$'.number_format($valor->valor_credito,0) ?></td>
                                            <td style="text-align: right"><?= '$'.number_format($valor->valor_cuota,0) ?></td>
                                            <td><?= $valor->numero_cuotas ?></td>
                                            <td style="text-align: right"><?= '$'.number_format($valor->saldo_credito,0) ?></td>
                                            <td><?= $valor->grupoPago->grupo_pago ?></td>
                                            <td><?= $valor->user_name ?></td>
                                            
                                        </tr>
                                   <?php endforeach; ?>       
                                </tbody>      
                            </table>
                        </div>
                    </div>   
                </div>
            </div>
            <!-- COMIENZA OTRO TABS-->
             <div role="tabpanel" class="tab-pane" id="estudio">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:85%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Id</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Titulo obtenido</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Institucion educativa</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Municipio</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha inicio</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha terminación</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Estudio/Profesión</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Año cursado</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Graduado</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Registro</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                     foreach ($estudios as $valor):?>
                                        <tr style='font-size:85%;'>
                                            <td><?= $valor->id ?></td>
                                            <td><?= $valor->titulo_obtenido?></td>
                                            <td><?= $valor->institucion_educativa ?></td>
                                            <td><?= $valor->codigoMunicipio->municipio ?></td>
                                             <td><?= $valor->fecha_inicio ?></td>
                                             <td><?= $valor->fecha_terminacion ?></td>
                                            <td><?= $valor->profesion->profesion ?></td>
                                            <td><?= $valor->anio_cursado ?></td>
                                            <td><?= $valor->graduadoestudio ?></td>
                                            <td><?= $valor->fecha_registro ?></td>
                                            <td><?= $valor->user_name ?></td>
                                            
                                        </tr>
                                   <?php endforeach; ?>    
                                </tbody>      
                            </table>
                        </div>
                    </div>   
                </div>
            </div>
            
            
        </div>
    </div>    
</div>
