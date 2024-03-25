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
$this->params['breadcrumbs'][] = ['label' => 'Proveedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_proveedor;
$view = 'proveedor';
?>
<div class="proveedor-view">

    <!--<?= Html::encode($this->title) ?>-->
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }else{?>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_proveedor'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }?> 
        <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 2, 'codigo' => $model->id_proveedor,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
           PROVEEDORES
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Id</th>
                    <td><?= $model->id_proveedor ?></td>
                    <th style='background-color:#F0F3EF;'>Tipo documento:</th>
                    <td><?= $model->tipoDocumento->tipo_documento ?></td>
                    <th style='background-color:#F0F3EF;'>Nit/Cedula</th>
                    <td><?= $model->nit_cedula ?></td>
                    <th style='background-color:#F0F3EF;' >Dv</th>
                    <td><?= $model->dv ?></td>
                      <th style='background-color:#F0F3EF;' >Proveedor</th>
                    <td><?= $model->nombre_completo ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Direccion</th>
                    <td><?= $model->direccion ?></td>
                    <th style='background-color:#F0F3EF;'>Email</th>
                    <td><?= $model->email ?></td>
                    <th style='background-color:#F0F3EF;'>Telefono</th>
                    <td><?= $model->telefono ?></td>
                    <th style='background-color:#F0F3EF;'>Celular</th>
                    <td><?= $model->celular ?></td>
                     <th style='background-color:#F0F3EF;'>Forma pago</th>
                    <td><?= $model->formaPago ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Departamento</th>
                    <td><?= $model->codigoDepartamento->departamento ?></td>
                    <th style='background-color:#F0F3EF;'>Municipio</th>
                    <td><?= $model->codigoMunicipio->municipio ?></td>
                     <th style='background-color:#F0F3EF;'>Tipo regimen</th>
                    <td><?= $model->tipoRegimen ?></td>
                    <th style='background-color:#F0F3EF;'>Celular contacto</th>
                    <td><?= $model->celular_contacto ?></td>
                       <th style='background-color:#F0F3EF;'>Contacto</th>
                    <td><?= $model->nombre_contacto ?></td>
                    
                </tr>
                 <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Plazo</th>
                    <td><?= $model->plazo ?></td>
                    <th style='background-color:#F0F3EF;'>Autoretenedor</th>
                    <td><?= $model->autoretenedorVenta ?></td>
                    <th style='background-color:#F0F3EF;'>Tipo empresa</th>
                    <td><?= $model->naturaleza->naturaleza ?></td>
                    <th style='background-color:#F0F3EF;'>Tipo sociedad</th>
                    <td><?= $model->tipoSociedad ?></td>
                     <th style='background-color:#F0F3EF;'>Fecha registro</th>
                    <td><?= $model->fecha_creacion ?></td>
                </tr>
                 <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Banco</th>
                    <?php if($model->codigo_banco == null){?>
                        <td><?= 'NO FOUND' ?></td>
                    <?php }else{?>
                        <td><?= $model->codigoBanco->entidad_bancaria ?></td>
                    <?php }?>
                    
                    <th style='background-color:#F0F3EF;'>Tipo cuenta</th>
                    <td><?= $model->tipoCuenta ?></td>
                    <th style='background-color:#F0F3EF;'>Producto</th>
                    <td><?= $model->producto ?></td>
                    <th style='background-color:#F0F3EF;'>User name</th>
                    <td><?= $model->user_name ?></td>
                    <th style='background-color:#F0F3EF;'>Tipo transación</th>
                    <td><?= $model->tipoTransacion ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Predeterminado</th>
                    <td><?= $model->proveedorPredeterminado ?></td>
                    <th style='background-color:#F0F3EF;'>Observación</th>
                    <td colspan="8"><?= $model->observacion ?></td>
                </tr>
            </table>
        </div>
    </div>
    <!--INICIO LOS TABS-->
   
</div>
