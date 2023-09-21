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
$this->params['breadcrumbs'][] = ['label' => 'Agentes comerciales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_agente;
$view = 'agentes-comerciales';
?>
<div class="agentes-comerciales-view">

    <!--<?= Html::encode($this->title) ?>-->
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }else{?>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_agentes'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }?> 
        <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 9, 'codigo' => $model->id_agente,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
           AGENTES COMERCIALES
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Id:</th>
                    <td><?= $model->id_agente ?></td>
                    <th style='background-color:#F0F3EF;'>Tipo documento:</th>
                    <td><?= $model->tipoDocumento->tipo_documento ?></td>
                    <th style='background-color:#F0F3EF;'>Documento:</th>
                    <td><?= $model->nit_cedula ?></td>
                    <th style='background-color:#F0F3EF;' >Agente comercial:</th>
                    <td><?= $model->nombre_completo ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Email:</th>
                    <td><?= $model->email_agente ?></td>
                    <th style='background-color:#F0F3EF;'>Celular:</th>
                    <td><?= $model->celular_agente ?></td>
                    <th style='background-color:#F0F3EF;'>Activo:</th>
                    <td><?= $model->estadoRegistro ?></td>
                     <th style='background-color:#F0F3EF;'>Direccion:</th>
                    <td><?= $model->direccion ?></td>
                 

                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>User name:</th>
                    <td><?= $model->user_name ?></td>
                    <th style='background-color:#F0F3EF;'>Departamento:</th>
                    <td><?= $model->codigoDepartamento->departamento ?></td>
                    <th style='background-color:#F0F3EF;'>Municipio:</th>
                    <td><?= $model->codigoMunicipio->municipio ?></td>
                    <th style='background-color:#F0F3EF;'>Cargo:</th>
                    <td><?= $model->cargo->nombre_cargo ?></td>
                </tr>
                <tr style="font-size: 90%">
                    <th style='background-color:#F0F3EF;'>Fecha registro:</th>
                    <td><?= $model->fecha_registro ?></td>
                    <th style='background-color:#F0F3EF;'>Gestion diaria:</th>
                    <td><?= $model->gestionDiaria ?></td>
                     <th style='background-color:#F0F3EF;'>Getiona pedido:</th>
                     <td><?= $model->gestionPedido?></td> 
                     <th style='background-color:#F0F3EF;'>Coordinador:</th>
                     <td><?= $model->coordinador->nombre_completo ?></td>
                    
                    
                </tr>
                 
                 
            </table>
        </div>
    </div>
    <!--INICIO LOS TABS-->
    <?php if($token == 1){?>
        <div>
        <ul class="nav nav-tabs" role="tablist">
            <?php
             ?>
            <li role="presentation" class="active"><a href="#listadoCliente" aria-controls="listadoCliente" role="tab" data-toggle="tab">Listado de clientes  <span class="badge"><?= count($listo_cliente) ?></span></a></li>

        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="listadoCliente">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>   
                                        <th scope="col" style='background-color:#B9D5CE;'>Nit/Cedula</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Nombre cliente</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Dirección</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Celular</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Email</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Telefono</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Activo</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                
                                foreach ($listo_cliente as $clientes):?>
                                    <tr style="font-size: 90%">
                                        <td><?= $clientes->id_cliente?></td>
                                        <td><?= $clientes->nit_cedula?></td>
                                        <td><?= $clientes->nombre_completo?></td>
                                        <td><?= $clientes->direccion?></td>
                                        <td><?= $clientes->celular?></td>
                                        <td><?= $clientes->email_cliente?></td>
                                        <td><?= $clientes->telefono?></td>
                                        <?php if($clientes->estado_cliente == 0){?>
                                             <td><?= $clientes->estadoCliente?></td>
                                        <?php }else{?>
                                             <td style='background-color:#CEECD4;'><?= $clientes->estadoCliente?></td>
                                        <?php } ?>     
                                    </tr>
                                <?php endforeach;?>
                                 
                                </tbody>      
                            </table>
                        </div>
                        <?php if(count($listo_cliente) > 0){?>
                            <div class="panel-footer text-right" >
                                <a href="<?= Url::toRoute(["/agentes-comerciales/consultaclientes", "id" => $clientes->id_agente])?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-export"></span>Exportar excel</a>
                            </div>
                        <?php }?>
                    </div>   
                </div>
            </div>
            <!--INICIO EL OTRO TABS -->
        </div>
    </div>   
    <?= LinkPager::widget(['pagination' => $pagination]) ?>
    <?php }?>
</div>
