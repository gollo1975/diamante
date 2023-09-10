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
$this->params['breadcrumbs'][] = ['label' => 'Entidad bancaria', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->codigo_banco;
$view = 'entidad-bancarias';
?>
<div class="proveedor-view">

    <!--<?= Html::encode($this->title) ?>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
	<?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->codigo_banco], ['class' => 'btn btn-success btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 3, 'codigo' => $model->codigo_banco,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            ENTIDAD FINANCIERA
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Codigo banco:</th>
                    <td><?= $model->codigo_banco ?></td>
                    <th style='background-color:#F0F3EF;'>Tipo documento:</th>
                    <td><?= $model->tipoDocumento->tipo_documento ?></td>
                    <th style='background-color:#F0F3EF;'>Documento:</th>
                    <td><?= $model->nit_cedula ?>-<?= $model->dv ?></td>
                      <th style='background-color:#F0F3EF;' >Entidad bancaria:</th>
                    <td><?= $model->entidad_bancaria ?></td>
                     <th style='background-color:#F0F3EF;' >Dirección:</th>
                    <td><?= $model->direccion_banco ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Telefono:</th>
                    <td><?= $model->telefono_banco ?></td>
                    <th style='background-color:#F0F3EF;'>Departamento:</th>
                    <td><?= $model->departamento->departamento ?></td>
                    <th style='background-color:#F0F3EF;'>Municipio:</th>
                    <td><?= $model->municipio->municipio?></td>
                      <th style='background-color:#F0F3EF;' >Tipo producto:</th>
                    <td><?= $model->tipoCuenta ?></td>
                     <th style='background-color:#F0F3EF;' >Producto:</th>
                    <td><?= $model->producto ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Convenio nomina:</th>
                    <td><?= $model->convenioNomina ?></td>
                    <th style='background-color:#F0F3EF;'>Convenio provedor:</th>
                    <td><?= $model->convenioProveedor ?></td>
                    <th style='background-color:#F0F3EF;'>Convenio empresa:</th>
                    <td><?= $model->convenioEmpresa ?></td>
                      <th style='background-color:#F0F3EF;'>Activo:</th>
                    <td><?= $model->estadoRegistro ?></td>
                     <th style='background-color:#F0F3EF;' >Codigo interfaz:</th>
                    <td><?= $model->codigo_interfaz ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>User name:</th>
                    <td><?= $model->user_name?></td>
                    <th style='background-color:#F0F3EF;'>Numero digitos:</th>
                    <td><?= $model->validador_digitos ?></td>
                    <th style='background-color:#F0F3EF;'>Fecha creación:</th>
                    <td><?= $model->fecha_creacion ?></td>
                    <th style='background-color:#F0F3EF;'></th>
                    <td></td>
                    <th style='background-color:#F0F3EF;'</th>
                    <td></td>
                      
                </tr>
            </table>
        </div>
    </div>
    <!--INICIO LOS TABS-->
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <?php
              $contMaquina = 1;
             ?>
            <li role="presentation" class="active"><a href="#entradamateria" aria-controls="entradamateria" role="tab" data-toggle="tab">Entradas  <span class="badge"><?= $contMaquina ?></span></a></li>

        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="entradamateria">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:85%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Código</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Descripción de maquina</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Cantidad</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha proceso</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Usuario</th> 
                                          <th scope="col" style='background-color:#B9D5CE;'></th> 
                                        
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
        </div>
    </div>    
</div>
