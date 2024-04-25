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
use app\models\EtapasAuditoria;

$this->title = 'AUDITORIAS DE CALIDAD (OE)';
$this->params['breadcrumbs'][] = $this->title;

?>
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtro");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
    }
</script>

<!--<h1>Lista Facturas</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("orden-ensamble-producto/index_auditoria_ensamble"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);


$conGrupo = ArrayHelper::map(app\models\GrupoProducto::find()->all(), 'id_grupo', 'nombre_grupo');

?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:block">
        <div class="row" >
            <?= $formulario->field($form, "numero_orden")->input("search") ?>
            <?= $formulario->field($form, "numero_auditoria")->input("search") ?>
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
             <?= $formulario->field($form, 'grupo')->widget(Select2::classname(), [
                'data' => $conGrupo,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
            
            <?= $formulario->field($form, "numero_lote")->input("search") ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("orden-ensamble-producto/index_auditoria_ensamble") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <tr style ='font-size: 90%;'>         
                    <th scope="col" style='background-color:#B9D5CE;'>Id</th>
                    <th scope="col" style='background-color:#B9D5CE;'>No auditoria</th>
                    <th scope="col" style='background-color:#B9D5CE;'>No lote</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Orden producción</th>
                    <th scope="col" style='background-color:#B9D5CE;'>F. cosmetica</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Grupo</th>
                    <th scope="col" style='background-color:#B9D5CE;'>F. proceso</th>
                    <th scope="col" style='background-color:#B9D5CE;'>F. analisis</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Etapa</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Analisis</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Cerrado</th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr style ='font-size: 90%;'>                
                <td><?= $val->id_auditoria?></td>
                <td><?= $val->numero_auditoria?></td>
                <td><?= $val->numero_lote?></td>
                <td><?= $val->numero_orden?></td>
                <?php if($val->id_forma == null){?>
                     <td><?= 'NO FOUND'?></td>
                <?php }else{?>
                    <td><?= $val->forma->concepto?></td>
                <?php }?>
                <td><?= $val->grupo->nombre_grupo?></td>
                <td><?= $val->fecha_proceso?></td>
                <td><?= $val->fecha_analisis?></td>
                <td><?= $val->etapa?></td>
                <td><?= $val->condicionAnalisis?></td>
                <td><?= $val->cerrarAuditoria?></td>
                <td style= 'width: 25px; height: 10px;'>
                    <a href="<?= Url::toRoute(["orden-ensamble-producto/view_auditoria", 'id_auditoria' => $val->id_auditoria]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite ver del detalle de l auditoria"></span></a>
                </td>
               
            </tr>            
            <?php endforeach; ?>
            </tbody>    
        </table> 
        <div class="panel-footer text-right" >            
           <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>                
        <?php $form->end() ?>
        </div>
     </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
