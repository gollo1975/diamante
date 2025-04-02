<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use app\models\Empleados;
use app\models\TipoPagoCredito;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;

$this->title = 'CONCEPTOS DE NOMINA';
$this->params['breadcrumbs'][] = $this->title;


$agrupar = ArrayHelper::map(app\models\AgruparConceptoSalario::find()->orderBy('concepto ASC')->all(), 'id_agrupado', 'concepto');
?>
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtro");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
    }
</script>

<!--<h1>Lista Facturas</h1>-->
<?php 

    $formulario = ActiveForm::begin([
        "method" => "get",
        "action" => Url::toRoute(["concepto-salarios/index"]),
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
	
    <div class="panel-body" id="filtro" style="display:block">
        <div class="row" >
            <?= $formulario->field($form, "codigo")->input("search") ?>
            <?= $formulario->field($form, "concepto")->input("search") ?>
            <?= $formulario->field($form, 'agrupado')->widget(Select2::classname(), [
                'data' => $agrupar,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <?= $formulario->field($form, 'prestacional')->dropDownList(['0' => 'NO', '1' => 'SI'],['prompt' => 'Seleccione...']) ?>
            <?= $formulario->field($form, 'debito_credito')->dropDownList(['1' => 'Credito', '2' => 'Debito'],['prompt' => 'Seleccione...']) ?>
          
        </div>
      
            <div class="panel-footer text-right">
                <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
                <a align="right" href="<?= Url::toRoute(["concepto-salarios/index"]) ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
        Registros: <span class="badge"><?= $pagination->totalCount ?></span>
    </div>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>                
                <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                <th scope="col" style='background-color:#B9D5CE;'>Concepto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Prestacional</th>
                <th scope="col" style='background-color:#B9D5CE;'>Debito/Credito</th>
                <th scope="col" style='background-color:#B9D5CE;'>Grupo concepto</th>                
                <th colspan="2" style='background-color:#B9D5CE;'><p style="color:blue;" align="center">Opciones</p></th>
                
            </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($model as $val): ?>
                    <tr style= 'font-size:85%;'>                
                        <td><?= $val->codigo_salario ?></td>
                        <td><?= $val->nombre_concepto ?></td>
                        <td><?= $val->datoPrestacional ?></td>
                        <td><?= $val->devengadoDeduccion ?></td>
                        <?php if($val->id_agrupado <> null){?>
                            <td><?= $val->conceptoSalario->concepto ?></td>
                        <?php }else{?>
                             <td><?= 'NO FOUND' ?></td>
                        <?php }?>    
                        <td style='width: 20px; height: 20px'>
                               <a href="<?= Url::toRoute(["concepto-salarios/view", "id" => $val->codigo_salario]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>                   
                        </td>
                        <td style='width: 20px; height: 20px'>
                            <a href="<?= Url::toRoute(["concepto-salarios/update", "id" => $val->codigo_salario]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>                   
                        </td>

                    </tr>            
                <?php endforeach; ?>
            </tbody>                  
        </table> 
            <div class="panel-footer text-right" >     
                 <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar a excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>                
                    <a align="right" href="<?= Url::toRoute("concepto-salarios/create") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>
            </div>       
     </div>
</div>
 <?php $form->end() ?>
<?= LinkPager::widget(['pagination' => $pagination]) ?>

