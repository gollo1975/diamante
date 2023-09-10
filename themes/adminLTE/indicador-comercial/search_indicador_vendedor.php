<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;

$this->title = 'CONSULTA (INDICADOR VENDEDOR)';
$this->params['breadcrumbs'][] = $this->title;
?>
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtropedido");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
    }
</script>

<!--<h1>Lista proveedor</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("indicador-comercial/search_indicador_vendedor"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtropedido" style="display:none">
        <div class="row" >
             <?= $formulario->field($form, "documento")->input("search") ?>
             <?= $formulario->field($form, "agente")->input("search") ?>
            <?= $formulario->field($form, 'desde')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
            <?= $formulario->field($form, 'hasta')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("indicador-comercial/search_indicador_vendedor") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
        </div>
    </div>
</div>
<?php $formulario->end() ?>
<?php
$form = ActiveForm::begin([
                "method" => "post",                            
            ]);
    ?>

<div class="table-responsive">
  <div class="panel panel-success ">
    <div class="panel-heading">
        Registros <span class="badge"><?= $pagination->totalCount ?></span>
    </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr style="font-size: 90%;">   
                    <th scope="col" style='background-color:#B9D5CE;'>Documento</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Agente comercial</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Año</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Desde</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Hasta</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Total visitas</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Total Reales</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Total pendientes</th>
                    <th scope="col" style='background-color:#B9D5CE;'>% Eficiencia</th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>  
                </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val):  ?>
            <tr style="font-size: 90%;">  
                    <td><?= $val->documento?></td>
                    <td><?= $val->agente ?></td>
                    <td><?= $val->indicador->anocierre ?></td>
                    <td><?= $val->desde ?></td>
                    <td><?= $val->hasta ?></td>
                    <td style="text-align: right"><?= $val->total_visitas ?></td>
                    <td style="text-align: right"><?= $val->total_realizadas ?></td>
                    <td style="text-align: right"><?= $val->total_no_realizadas ?></td>
                    <td style="text-align: right"><?= $val->total_porcentaje ?>%</td>
                    <td style= 'width: 25px; height: 25px;'>
                        <a href="<?= Url::toRoute(["indicador-comercial/view_grafica_vendedor", 'id' => $val->id, 'desde' => $val->desde, 'hasta' => $val->hasta,'id_agente' => $val->id_agente]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                    </td>
                   
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
            <div class="panel-footer text-right" >            
             <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>                
           </div>     
       <?php $form->end() ?>
    </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>