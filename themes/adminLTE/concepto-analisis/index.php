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
use app\models\EtapasAuditoria;
use kartik\select2\Select2;


$this->title = 'CONCEPTO DE ANALISIS';
$this->params['breadcrumbs'][] = $this->title;
$conEtapa = ArrayHelper::map(EtapasAuditoria::find()->all(), 'id_etapa', 'concepto');
?>
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtroproveedor");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
    }
</script>

<!--<h1>Lista proveedor</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("concepto-analisis/index"),
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
	
    <div class="panel-body" id="filtroproveedor" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "codigo")->input("search") ?>
            <?= $formulario->field($form, "concepto")->input("search") ?>
            <?= $formulario->field($form, 'etapa')->widget(Select2::classname(), [
                'data' => $conEtapa,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("concepto-analisis/index") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
           <tr style="font-size: 85%;">    
                <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                <th scope="col" style='background-color:#B9D5CE;'>Concepto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Nombre de etapa</th>
                <th scope="col" style='background-color:#B9D5CE;'>User_name</th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha registro</th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($model as $val): ?>
                    <tr style="font-size: 85%;">                   
                         <td><?= $val->id_analisis ?></td>
                        <td><?= $val->concepto ?></td>
                        <td><?= $val->etapaProceso->concepto?></td>
                        <td><?= $val->user_name ?></td>
                        <td><?= $val->fecha_registro ?></td>
                        <td style= 'width: 25px; height: 20px;'>
                            <a href="<?= Url::toRoute(["concepto-analisis/view", "id" => $val->id_analisis]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                        </td>
                        <td style= 'width: 25px; height: 20px;'>
                            <a href="<?= Url::toRoute(["concepto-analisis/update", "id" => $val->id_analisis])?>" ><span class="glyphicon glyphicon-pencil"></span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>        
        </table>
        <div class="panel-footer text-right" >
             <?php
                $form = ActiveForm::begin([
                            "method" => "post",                            
                        ]);
                ?>    
                <a align="right" href="<?= Url::toRoute("concepto-analisis/create") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>   
            <?php $form->end() ?>
            
        </div>
    </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>