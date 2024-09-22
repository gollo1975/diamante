<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Contrato;
use app\models\Incapacidad;
use app\models\EstudioEmpleado;
use app\models\Licencia;
use app\models\Credito;

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

$this->title = 'EMPLEADO: ' .$model->nombre_completo;
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_empleado;
$view = 'empleados';
//$contrato = Contrato::find()->where(['=','id_empleado', $model->id_empleado])->orderBy('id_contrato DESC')->all();
/*$incapacidad = Incapacidad::find()->where(['=','id_empleado', $model->id_empleado])->orderBy('id_incapacidad DESC')->all();
$licencia = Licencia::find()->where(['=','id_empleado', $model->id_empleado])->orderBy('id_licencia_pk DESC')->all();
$credito = Credito::find()->where(['=','id_empleado', $model->id_empleado])->orderBy('id_credito DESC')->all();
$estudio = EstudioEmpleado::find()->where(['=','id_empleado', $model->id_empleado])->orderBy('id DESC')->all();*/
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
           
            <li role="presentation" class="active"><a href="#contrato" aria-controls="contrato" role="tab" data-toggle="tab">Contratos <span class="badge"><?= 1 ?></span></a></li>
            <li role="presentation"><a href="#incapacidad" aria-controls="incapacidad" role="tab" data-toggle="tab">Incapacidades <span class="badge"><?= 1 ?></span></a></li>
            <li role="presentation"><a href="#licencia" aria-controls="licencia" role="tab" data-toggle="tab">Licencias <span class="badge"><?= 1 ?></span></a></li>
            <li role="presentation"><a href="#credito" aria-controls="licencia" role="tab" data-toggle="tab">Créditos <span class="badge"><?= 1 ?></span></a></li>
            <li role="presentation"><a href="#estudio" aria-controls="estudio" role="tab" data-toggle="tab">Estudios <span class="badge"><?= 1 ?></span></a></li>
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
                                        <th scope="col" style='background-color:#B9D5CE;'Tipo contrato</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'Tiempo</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Grupo pago</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha inicio</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha final</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Cargo</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Salario</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Activo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
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
                                        <th scope="col" style='background-color:#B9D5CE;'>Grupo pago</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Salario</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Vlr_Incapacidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
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
                                    
                                </tbody>      
                            </table>
                        </div>
                    </div>   
                </div>
            </div>
            
            
        </div>
    </div>    
</div>
