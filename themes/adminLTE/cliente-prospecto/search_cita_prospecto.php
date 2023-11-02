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
use app\models\AgentesComerciales;
use app\models\TipoCliente;


$this->title = 'CONSULTA - CITAS PROSPECTOS';
$this->params['breadcrumbs'][] = $this->title;


?>
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtrocliente");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
    }
</script>

<!--<h1>Lista proveedor</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("cliente-prospecto/search_cita_prospecto"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$agente = ArrayHelper::map(app\models\AgentesComerciales::find()->orderBy('nombre_completo ASC')->all(), 'id_agente', 'nombre_completo');
$prospecto = ArrayHelper::map(app\models\ClienteProspecto::find()->orderBy('nombre_completo ASC')->all(), 'id_prospecto', 'nombre_completo');
$tipoVisita = ArrayHelper::map(app\models\TipoVisitaComercial::find()->orderBy('nombre_visita ASC')->all(), 'id_tipo_visita', 'nombre_visita');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtrocliente" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, 'prospecto')->widget(Select2::classname(), [
                'data' => $prospecto,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
            <?= $formulario->field($form, 'tipo_visita')->widget(Select2::classname(), [
                'data' => $tipoVisita,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
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
            <?= $formulario->field($form, 'vendedor')->widget(Select2::classname(), [
                'data' => $agente,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 

        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("cliente-prospecto/search_cita_prospecto") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
        </div>
    </div>
</div>

<?php $formulario->end() ?>

<div class="table-responsive">
<div class="panel panel-success ">
    <div class="panel-heading">
        <?php if($model){?>
            Registros <span class="badge"><?= count($model) ?></span>
        <?php }?>    
    </div>
        <table class="table table-bordered table-hover">
            <thead>
           <tr style="font-size: 90%;">    
                <th scope="col" style='background-color:#B9D5CE;'>Documento</th>
                <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                 <th scope="col" style='background-color:#B9D5CE;'>Agente</th>
                <th scope="col" style='background-color:#B9D5CE;'>H. visita</th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha visita</th>
                 <th scope="col" style='background-color:#B9D5CE;'>Fecha/hora gestión</th>
                <th scope="col" style='background-color:#B9D5CE;'>Tipo visita</th>
                <th scope="col" style='background-color:#B9D5CE;'>Forma visita</th>
                <th scope="col" style='background-color:#B9D5CE;'>Cumplida</th>
            </tr>
            </thead>
            <tbody>
                <?php
                if($model){
                    foreach ($model as $val): ?>
                        <tr style="font-size: 90%;">                   
                            <td><?= $val->prospecto->nit_cedula ?></td>
                            <td><?= $val->prospecto->nombre_completo ?></td>
                             <td><?= $val->agenteCita->nombre_completo ?></td>
                            <td><?= $val->hora_cita ?></td>
                            <td><?= $val->fecha_cita ?></td>
                             <td><?= $val->fecha_hora_informe ?></td>
                            <td><?= $val->tipoVisita->nombre_visita ?></td>
                             <td><?= $val->visitaCliente ?></td>
                            <td><?= $val->citaCumplida ?></td> 
                        </tr>
                    <?php endforeach; 
                }?>
            </tbody>        
        </table>
        <div class="panel-footer text-right" >
             <?php
                $form = ActiveForm::begin([
                            "method" => "post",                            
                        ]);
                ?>    
            <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar a excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>
              <?php $form->end() ?>
            
        </div>
    </div>
</div>
<?php if($model){?>
     <?= LinkPager::widget(['pagination' => $pagination]) ?>
 <?php }?>