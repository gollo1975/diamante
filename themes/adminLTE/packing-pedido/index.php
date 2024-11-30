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
//Modelos...
$this->title = 'Listado de Packing';
$this->params['breadcrumbs'][] = $this->title;

?>
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtro");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "block";}
    }
</script>

<!--<h1>Lista Facturas</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("packing-pedido/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);

$Contras = ArrayHelper::map(app\models\Transportadora::find()->orderBy('razon_social ASC')->all(), 'id_transportadora', 'razon_social');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:block">
        <div class="row" >
            <?= $formulario->field($form, "numero_pedido")->input("search") ?>
            <?= $formulario->field($form, "numero_packing")->input("search") ?>
            <?= $formulario->field($form, 'fecha_inicio')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
            <?= $formulario->field($form, 'fecha_corte')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
             <?= $formulario->field($form, "cliente")->input("search") ?>
             <?= $formulario->field($form, 'transportadora')->widget(Select2::classname(), [
                'data' => $Contras,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("packing-pedido/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
            <?php if($model){?>
              Registros <span class="badge"><?= $pagination->totalCount ?></span>
            <?php }?>  
        </div>
        <table class="table table-bordered table-hover">
        <thead>
            <tr style ='font-size: 85%;'>         
            <th scope="col" style='background-color:#B9D5CE;'>Numero de packing</th>
            <th scope="col" style='background-color:#B9D5CE;'>Numero de pedido</th>
             <th scope="col" style='background-color:#B9D5CE;'>Transportadora</th> 
            <th scope="col" style='background-color:#B9D5CE;'>Cliente</th> 
            <th scope="col" style='background-color:#B9D5CE;'>Fecha packing</th>
            <th scope="col" style='background-color:#B9D5CE;'>Total unidades</th>
            <th scope="col" style='background-color:#B9D5CE;'>Total cajas</th>
            <th scope="col" style='background-color:#B9D5CE;'></th>


        </tr>
        </thead>
        <tbody>
        <?php 
        if($model){
            foreach ($model as $val):?>
                <tr style ='font-size: 85%;'>                
                    <td><?= $val->numero_packing?></td>
                    <td><?= $val->numero_pedido?></td>
                    <?php if($val->id_transportadora != ''){?>
                         <td><?= $val->transportadora->razon_social?></td>
                    <?php }else{?>
                        <td><?= 'NO FOUND'?></td>
                    <?php }?>
                    <td><?= $val->cliente?></td>
                    <td><?= $val->fecha_packing?></td>
                    <td><?= $val->total_unidades_packing?></td>
                    <td><?= $val->total_cajas?></td>
                    <td style= 'width: 25px; height: 20px;'>
                         <a href="<?= Url::toRoute(["packing-pedido/view", "id" => $val->id_packing]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                    </td>
                </tr>            
            <?php endforeach; 
        }   ?>
        </tbody>    
    </table> 
        <div class="panel-footer text-right" >
           <?php if($model){?>
               <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>
           <?php }
           $form->end() ?>
        </div>
    </div>    
</div>

<?= LinkPager::widget(['pagination' => $pagination]) ?>

