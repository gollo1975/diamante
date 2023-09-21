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
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_cliente;
$view = 'clientes';
?>
<div class="clientes-view">

    <!--<?= Html::encode($this->title) ?>-->
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }else{?>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_clientes'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }?> 
        <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 10, 'codigo' => $model->id_cliente,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
           CLIENTES
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Id:</th>
                    <td><?= $model->id_cliente ?></td>
                    <th style='background-color:#F0F3EF;'>Tipo documento:</th>
                    <td><?= $model->tipoDocumento->tipo_documento ?></td>
                    <th style='background-color:#F0F3EF;'>Nit/Cedula:</th>
                    <td><?= $model->nit_cedula ?></td>
                    <th style='background-color:#F0F3EF;' >Dv:</th>
                    <td><?= $model->dv ?></td>
                      <th style='background-color:#F0F3EF;' >Cliente:</th>
                    <td><?= $model->nombre_completo ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Forma pago:</th>
                    <td><?= $model->formaPago ?></td>
                    <th style='background-color:#F0F3EF;'>Telefono:</th>
                    <td><?= $model->telefono ?></td>
                    <th style='background-color:#F0F3EF;'>Celular:</th>
                    <td><?= $model->celular ?></td>
                        <th style='background-color:#F0F3EF;'>Email:</th>
                    <td><?= $model->email_cliente ?></td>
                       <th style='background-color:#F0F3EF;'>Direccion:</th>
                    <td><?= $model->direccion ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Departamento:</th>
                    <td><?= $model->codigoDepartamento->departamento ?></td>
                    <th style='background-color:#F0F3EF;'>Municipio.</th>
                    <td><?= $model->codigoMunicipio->municipio ?></td>
                     <th style='background-color:#F0F3EF;'>Tipo regimen:</th>
                    <td><?= $model->tipoRegimen ?></td>
                    <th style='background-color:#F0F3EF;'>Tipo empresa:</th>
                    <td><?= $model->naturaleza->naturaleza ?></td>
                    <th style='background-color:#F0F3EF;'>Tipo sociedad:</th>
                    <td><?= $model->tipoSociedad ?></td>
                    
                </tr>
                 <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Plazo:</th>
                    <td><?= $model->plazo ?></td>
                    <th style='background-color:#F0F3EF;'>Autoretenedor:</th>
                    <td><?= $model->autoretenedorVenta ?></td>
                    <th style='background-color:#F0F3EF;'>Fecha registro:</th>
                    <td><?= $model->fecha_creacion ?></td>
                    <th style='background-color:#F0F3EF;'>User nuevo:</th>
                    <td><?= $model->user_name ?></td>
                    <th style='background-color:#F0F3EF;'>User editado:</th>
                    <td><?= $model->user_name_editar ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Fecha editado:</th>
                    <td><?= $model->fecha_editado ?></td>
                    <th style='background-color:#F0F3EF;'>Posicion venta:</th>
                    <td><?= $model->posicion->posicion ?></td>
                    <th style='background-color:#F0F3EF;'>Activo:</th>
                    <td><?= $model->estadoCliente ?></td>
                    <th style='background-color:#F0F3EF;'>Agente comercial:</th>
                    <td><?= $model->agenteComercial->nombre_completo ?></td>
                    <th style='background-color:#F0F3EF;'>Presupuesto:</th>
                    <td style="text-align: right"><?= ''.number_format($model->presupuesto_comercial,0) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>G. presupuesto:</th>
                    <td style="text-align: right"><?= ''.number_format($model->gasto_presupuesto_comercial,0) ?></td>
                    <th style='background-color:#F0F3EF;'>Tipo cliente:</th>
                    <td ><?= $model->tipoCliente->concepto ?></td>
                    <th style='background-color:#F0F3EF;'>Vender en mora:</th>
                    <td ><?= $model->ventaMora ?></td>
                    <th style='background-color:#F0F3EF;'></th>
                    <td></td>
                     <th style='background-color:#F0F3EF;'>Cupo asignado:</th>
                    <td style="text-align: right"><?= ''.number_format($model->cupo_asignado,0) ?></td>
                    
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
            <?php
              $contMaquina = 1;
             ?>
            <li role="presentation" class="active"><a href="#asignacioncupo" aria-controls="asignacioncupo" role="tab" data-toggle="tab">Asignacion de cupo  <span class="badge"><?= count($cupo) ?></span></a></li>
            <li role="presentation"><a href="#anotaciones" aria-controls="anotaciones" role="tab" data-toggle="tab">Anotaciones  <span class="badge"><?= count($cupo) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="asignacioncupo">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Código</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Descripcion comercial</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha proceso</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Usuario</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Cupo</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Activo</th> 
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($cupo as $cupos):?>
                                    <tr style='font-size:90%;'>
                                        <td> <?= $cupos->id_cupo?></td>
                                        <?php if($cupos->descripcion == 0){?>
                                              <td> <?= 'CUPO INICIAL'?></td>
                                        <?php }else{?>
                                              <td> <?= 'AUMENTO DE CUPO'?></td>
                                        <?php }?>      
                                        <td> <?= $cupos->fecha_registro?></td>
                                        <td> <?= $cupos->user_name?></td>
                                        <?php if($cupos->estado_registro == 0){?>
                                            <td style="padding-right: 1;padding-right: 0; text-align: right"> <input type="text" name="valor_cupo[]" value="<?= $cupos->valor_cupo ?>" style="text-align: right" size="9" required="true"> </td> 
                                            <td align="center"><select name="estado_registro[]" style="width: 70px">
                                                        <?php if ($cupos->estado_registro == 0){
                                                             echo 'SI';   
                                                            } else {
                                                              echo 'NO';
                                                         }?>    
                                                        <option value="<?= $cupos->estado_registro ?>"><?= $cupos->estadoRegistro ?></option>
                                                        <option value="0">SI</option>
                                                        <option value="1">NO</option>
                                            </select></td>
                                        <?php }else{?>
                                            <td style="text-align: right"> <?= ''.number_format($cupos->valor_cupo, 0)?></td>
                                             <td> <?= $cupos->estadoRegistro?></td>
                                        <?php }?>    
                                         <input type="hidden" name="listado_cupo[]" value="<?= $cupos->id_cupo?>">  
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>      
                            </table>
                        </div>
                        <?php if($token == 0){?>
                            <div class="panel-footer text-right" >  
                                <!-- Inicio Nuevo Detalle proceso -->
                                  <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Crear cupo',
                                      ['/clientes/nuevo_cupo_cliente','id' => $model->id_cliente, 'token' =>$token],
                                      [
                                          'title' => 'Crear cupo para el cliente',
                                          'data-toggle'=>'modal',
                                          'data-target'=>'#modalnuevocupocliente'.$model->id_cliente,
                                          'class' => 'btn btn-info btn-sm'
                                      ])    
                                 ?>
                                <div class="modal remote fade" id="modalnuevocupocliente<?= $model->id_cliente ?>">
                                    <div class="modal-dialog modal-lg" style ="width: 500px;">
                                         <div class="modal-content"></div>
                                    </div>
                                </div> 
                                <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-warning btn-sm", 'name' => 'actualizarcupo']);?>    
                            </div>   
                        <?php }?>                        
                    </div>   
                </div>
            </div>
            <!--INICIO EL OTRO TABS -->
            <div role="tabpanel" class="tab-pane" id="anotaciones">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE; width: 15px'>Código</th>                        
                                        <th scope="col" style='background-color:#B9D5CE; width: 80px'>Fecha proceso</th> 
                                        <th scope="col" style='background-color:#B9D5CE; width: 30px'>Usuario</th> 
                                          <th scope="col" style='background-color:#B9D5CE; width: 450px'>Anotación</th>    
                                    </tr>
                                </thead>
                                <body>
                                    <?php 
                                    foreach ($anotacion as $anotaciones):?>
                                    <tr style='font-size:90%;'>
                                        <td> <?= $anotaciones->id_anotacion?></td>
                                        <td> <?= $anotaciones->fecha_registro?></td>
                                        <td> <?= $anotaciones->user_name?></td>
                                        <td> <?= $anotaciones->anotacion?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </body>    
                            </table>
                        </div>
                        <?php if($token == 0){?>
                            <div class="panel-footer text-right" >  
                                <!-- Inicio Nuevo Detalle proceso -->
                                  <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Anotaciones',
                                      ['/clientes/anotacion_cliente','id' => $model->id_cliente, 'token' =>$token],
                                      [
                                          'title' => 'Crea las anotaciones del cliente',
                                          'data-toggle'=>'modal',
                                          'data-target'=>'#modalanotacioncliente'.$model->id_cliente,
                                          'class' => 'btn btn-info btn-sm'
                                      ])    
                                 ?>
                                <div class="modal remote fade" id="modalanotacioncliente<?= $model->id_cliente ?>">
                                    <div class="modal-dialog modal-lg" style ="width: 750px;">
                                         <div class="modal-content"></div>
                                    </div>
                                </div> 
                            </div>   
                        <?php }?>
                    </div>
                </div>
            </div>  
            <!--TERMINA TABS-->
        </div>
    </div> 
    <?php ActiveForm::end(); ?>  
</div>
