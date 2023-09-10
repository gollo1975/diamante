<?php

//modelos
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
/* @var $model app\models\Ordenproduccion */

$this->title = 'Detalle Indicador comercial';
$this->params['breadcrumbs'][] = ['label' => 'Indicador comercial', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_indicador;
?>
<div class="indicador-comercial-view">
    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?php if($token == 0){
            echo Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']);
        } else{ 
           echo Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_indicador_comercial'], ['class' => 'btn btn-primary btn-sm']);
         } 
       if(count($clientes) > 0 && count($vendedores) > 0 && $model->estado_indicador == 0){
           echo Html::a('<span class="glyphicon glyphicon-remove"></span> Cerrar indicador', ['cerrar_indicador', 'id' => $model->id_indicador],['class' => 'btn btn-default btn-sm',
                           'data' => ['confirm' => 'Esta seguro de cerrar el indicador comercial desde el '. $model->fecha_inicio. ' hasta el '. $model->fecha_cierre.'.', 'method' => 'post']]); 
        }?>
    </p>          
    <div class="panel panel-success">
        <div class="panel-heading">
            INDICADOR COMERCIAL
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_indicador") ?></th>
                    <td><?= Html::encode($model->id_indicador) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_inicio') ?></th>
                    <td><?= Html::encode($model->fecha_inicio) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_cierre') ?></th>
                    <td><?= Html::encode($model->fecha_cierre) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'anocierre') ?></th>
                     <td style="text-align: right;"><?= Html::encode($model->anocierre) ?></td>
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
    <!-- comienza los tabs -->
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#listado_vendedores" aria-controls="listado_vendedores" role="tab" data-toggle="tab">Vendedores <span class="badge"><?= count($vendedores) ?></span></a></li>
            <li role="presentation"><a href="#gestioncliente" aria-controls="gestioncliente" role="tab" data-toggle="tab"> Clientes <span class="badge"><?= count($clientes)?></span></a></li>
        </ul>
            <div class="tab-content">
                 <div role="tabpanel" class="tab-pane active" id="listado_vendedores">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Documento</th>       
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Agente comerciales</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Total visitas</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Total Reales</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Total pendientes</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>% Eficiencia</th>
                                            <th scope="col" style='background-color:#B9D5CE;'></th>
                                            <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"/></th>
                                         
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($vendedores as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->documento ?></td>
                                                <td><?= $val->agente ?></td>
                                                <td style="text-align: right"><?= $val->total_visitas ?></td>
                                                <td style="text-align: right"><?= $val->total_realizadas ?></td>
                                                <td style="text-align: right"><?= $val->total_no_realizadas ?></td>
                                                <td style="text-align: right"><?= $val->total_porcentaje ?>%</td>
                                               <?php if($val->total_visitas > 0 && $model->estado_indicador == 0){?>
                                                    <td style= 'width: 25px; height: 25px;'>
                                                        <?= Html::a('<span class="glyphicon glyphicon-list-alt"></span> ', ['indicador_clientes', "id" => $model->id_indicador, 'desde' => $model->fecha_inicio, 'hasta' => $model->fecha_cierre, 'agente' =>$val->id_agente, 'token' => $token], [
                                                            'class' => '',
                                                            'data' => [
                                                                'confirm' => 'Esta seguro de crear el indicador para los clientes?',
                                                                'method' => 'post',
                                                            ],
                                                        ])?> 
                                                    </td>
                                               <?php }else { ?>
                                                    <td style= 'width: 25px; height: 25px;'>
                                                    </td>                                                    
                                                <?php }?>    
                                                <td style= 'width: 25px; height: 25px;'><input type="checkbox" name="agente_comercial[]" value="<?= $val->id ?>"></td> 
                                            </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                            </div>
                            <?php if($model->estado_indicador == 0){?>
                                <div class="panel-footer text-right">
                                    <?= Html::a('<span class="glyphicon glyphicon-download-alt"></span> Expotar vendedores', ['excel_indicador_vendedores', 'id' => $model->id_indicador, 'sw' => 0], ['class' => 'btn btn-primary btn-sm']);?>
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-log-in'></span> Generar", ["class" => "btn btn-success btn-sm", 'name' => 'generar_registros']) ?>
                                </div>  
                            <?php }else {?>
                            <div class="panel-footer text-right">
                                    <?= Html::a('<span class="glyphicon glyphicon-download-alt"></span> Expotar vendedores', ['excel_indicador_vendedores', 'id' => $model->id_indicador, 'sw' => 0], ['class' => 'btn btn-primary btn-sm']);?>
                            </div>  
                            <?php }?>  
                        </div>
                    </div>
                </div>    
               
                <!--TERMINA TBAS-->
                <div role="tabpanel" class="tab-pane" id="gestioncliente">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Documento</th>       
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Cliente</th>
                                             <th scope="col" align="center" style='background-color:#B9D5CE;'>Agente</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Total visitas</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Total Reales</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Total pendientes</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>% Eficiencia</th>
                                            <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"/></th>
                                         
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($clientes as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->cliente->nit_cedula ?></td>
                                                <td><?= $val->cliente->nombre_completo ?></td>
                                                <td><?= $val->agente->nombre_completo ?></td>
                                                <td style="text-align: right"><?= $val->total_visitas ?></td>
                                                <td style="text-align: right"><?= $val->visita_real ?></td>
                                                <td style="text-align: right"><?= $val->visita_no_real ?></td>
                                                <td style="text-align: right"><?= $val->porcentaje ?>%</td>
                                                <td style= 'width: 25px; height: 25px;'><input type="checkbox" name="cliente_comercial[]" value="<?= $val->id_detalle ?>"></td> 
                                            </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                            </div>  
                            <?php if($model->estado_indicador == 0){?>
                                <div class="panel-footer text-right">
                                   <?= Html::a('<span class="glyphicon glyphicon-download-alt"></span> Expotar clientes', ['excel_indicador_vendedores', 'id' => $model->id_indicador, 'sw' => 1], ['class' => 'btn btn-primary btn-sm']);?>
                                   <?= Html::submitButton("<span class='glyphicon glyphicon-log-in'></span> Procesar", ["class" => "btn btn-warning btn-sm", 'name' => 'procesar_indicador']) ?>
                               </div>  
                            <?php }else{?>
                                <div class="panel-footer text-right">
                                       <?= Html::a('<span class="glyphicon glyphicon-download-alt"></span> Expotar clientes', ['excel_indicador_vendedores', 'id' => $model->id_indicador, 'sw' => 1], ['class' => 'btn btn-primary btn-sm']);?>
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

<script type="text/javascript">
	function marcar(source) 
	{
		checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}
</script>

   