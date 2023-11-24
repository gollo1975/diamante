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


$this->title = 'RACKS';
$this->params['breadcrumbs'][] = $this->title;


?>
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtrorack");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
    }
</script>

<!--<h1>Lista proveedor</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("tipo-rack/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$pisos = ArrayHelper::map(\app\models\Pisos::find()->all(), 'id_piso', 'descripcion'); 
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtrorack" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "numero")->input("search") ?>
            <?= $formulario->field($form, "descripcion")->input("search") ?>
            <?= $formulario->field($form, 'piso')->widget(Select2::classname(), [
                   'data' => $pisos,
                   'options' => ['prompt' => 'Seleccione...'],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
            ]); ?> 
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("tipo-rack/index") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
        </div>
    </div>
</div>

<?php $formulario->end() ?>

<div class="table-responsive">
<div class="panel panel-success ">
    <div class="panel-heading">
        Registros <span class="badge"><?= $pagination->totalCount ?></span>
    </div>
        <table class="table table-bordered table-hover">
            <thead>
           <tr style="font-size: 90%;">    
                <th scope="col" style='background-color:#B9D5CE;'>Id</th>
                <th scope="col" style='background-color:#B9D5CE;'>Numero rack</th>
                <th scope="col" style='background-color:#B9D5CE;'>Descripcion</th>
                 <th scope="col" style='background-color:#B9D5CE;'>Medidas</th>
                <th scope="col" style='background-color:#B9D5CE;'>Capacidad instalada</th>
                <th scope="col" style='background-color:#B9D5CE;'>Unidades almacenadas</th>
                <th scope="col" style='background-color:#B9D5CE;'>Numero piso</th>
                 <th scope="col" style='background-color:#B9D5CE;'>Controla capacidad</th>
                <th scope="col" style='background-color:#B9D5CE;'>User name</th>
                <th scope="col" style='background-color:#B9D5CE;'>Activo</th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr style="font-size: 90%;">                   
                <td><?= $val->id_rack ?></td>
                <td><?= $val->numero_rack ?></td>
                <td><?= $val->descripcion ?></td>
                <td><?= $val->medidas ?></td>
                <td style="text-align: right"><?= $val->capacidad_instalada ?></td>
                <td style ="text-align: right"><?= $val->capacidad_actual ?></td>
                <td><?= $val->pisos->descripcion?></td>
                 <td><?= $val->controlarCapacidad?></td>
                <td><?= $val->user_name?></td>
                <td><?= $val->estadoActivo?></td>
                <td style= 'width: 25px; height: 10px;'>
                    <a href="<?= Url::toRoute(["tipo-rack/view", "id" => $val->id_rack, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                </td>
                <td style= 'width: 25px; height: 10px;'>
                       <a href="<?= Url::toRoute(["tipo-rack/update", "id" => $val->id_rack]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>                   
                </td>
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>
        <div class="panel-footer text-right" >
             <?php
                $form = ActiveForm::begin([
                            "method" => "post",                            
                        ]);
                ?>    
            <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>
            <a align="right" href="<?= Url::toRoute("tipo-rack/create") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>
            <?php $form->end() ?>
            
        </div>
    </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>