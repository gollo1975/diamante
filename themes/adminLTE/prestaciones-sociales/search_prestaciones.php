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
use app\models\GrupoPago;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;

$this->title = 'Prestaciones sociales';
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
    "action" => Url::toRoute("prestaciones-sociales/search_prestaciones"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);

$grupo = ArrayHelper::map(GrupoPago::find()->all(), 'id_grupo_pago', 'grupo_pago');
$empleado = ArrayHelper::map(Empleados::find()->orderBy('nombre_completo ASC')->all(), 'id_empleado', 'nombre_completo');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "documento")->input("search") ?>
             <?= $formulario->field($form, 'id_empleado')->widget(Select2::classname(), [
                'data' => $empleado,
                'options' => ['prompt' => 'Seleccione el empleado...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
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
            <?= $formulario->field($form, 'id_grupo_pago')->widget(Select2::classname(), [
                'data' => $grupo,
                'options' => ['prompt' => 'Seleccione el grupo pago ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("prestaciones-sociales/search_prestaciones") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
        Registros: <span class="badge"> <?= $pagination->totalCount ?></span>
    </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr style ='font-size:85%;'>                
                <th scope="col" style='background-color:#B9D5CE;'>Nro pago</th>
                <th scope="col" style='background-color:#B9D5CE;'>Documento</th>
                <th scope="col" style='background-color:#B9D5CE;'>Empleado</th>
                <th scope="col" style='background-color:#B9D5CE;'>Grupo_pago</th>
                 <th scope="col" style='background-color:#B9D5CE;'>F. creacion</th> 
                <th scope="col" style='background-color:#B9D5CE;'>Inicio contrato</th>                
                <th scope="col" style='background-color:#B9D5CE;'>F. contrato</th>
                <th scope="col" style='background-color:#B9D5CE;'>Devengado</th>
                <th scope="col" style='background-color:#B9D5CE;'>Deducción</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total pagar</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Estado generado" >Eg</span></th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Estado aplicado" >Ea</span></th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Estado cerrado" >Ec</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                                            
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
                <tr style='font-size:85%;'>                
                <td><?= $val->nro_pago ?></td>
                <td><?= $val->documento?></td>
                <td><?= mb_strtoupper($val->empleado->nombre_completo)?></td>
                <td><?= $val->grupoPago->grupo_pago ?></td>
                <td><?= $val->fecha_creacion ?></td>
                <td><?= $val->fecha_inicio_contrato ?></td>
                <td><?= $val->fecha_termino_contrato ?></td>
                <td align="right"><?= ''.number_format($val->total_devengado,0) ?></td>
                <td align="right"><?= ''.number_format($val->total_deduccion,0) ?></td>
                <td align="right"><?= ''.number_format($val->total_pagar,0) ?></td>
                <td><?= $val->estadoGenerado?></td>
                <td><?= $val->estadoAplicado?></td>
                <td><?= $val->estadoCerrado?></td>
                <td style= 'width: 35px;'>
                        <a href="<?= Url::toRoute(["prestaciones-sociales/view", "id" => $val->id_prestacion, "pagina" =>$pagina]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                </td>
                
            </tbody>            
            <?php endforeach; ?>
        </table>    
        <div class="panel-footer text-right" >            
                
                <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar a excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm ']); ?>                
                
            <?php $form->end() ?>
        </div>
    </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>


