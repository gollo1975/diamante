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
use app\models\TipoNomina;
use app\models\InteresesCesantia;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;

$this->title = 'Intereses.';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="programacion-nomina-view">
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtro");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
    }
</script>

<!--<h1>Lista Facturas</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("programacion-nomina/search_comprobante_cesantias"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$empleado = ArrayHelper::map(\app\models\Empleados::find()->orderBy('nombre_completo ASC')->all(), 'id_empleado', 'nombre_completo');
$grupo = ArrayHelper::map(GrupoPago::find()->orderBy('grupo_pago ASC')->all(), 'id_grupo_pago', 'grupo_pago');
$interes = ArrayHelper::map(\app\models\ConfiguracionSalario::find()->orderBy('id_salario DESC')->all(), 'anio', 'anio');
$tipo_pago = ArrayHelper::map(TipoNomina::find()->where(['=','ver_registro', 1])->orderBy('id_tipo_nomina ASC')->all(), 'id_tipo_nomina', 'tipo_pago');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
             <?= $formulario->field($form, "cedula_empleado")->input("search") ?>
            <?= $formulario->field($form, 'id_empleado')->widget(Select2::classname(), [
                'data' => $empleado,
                'options' => ['prompt' => 'Seleccione el empleado ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <?= $formulario->field($form, 'anio')->dropDownList($interes,['prompt' => 'Seleccione ...']) ?>
            <?= $formulario->field($form, 'id_grupo_pago')->widget(Select2::classname(), [
                'data' => $grupo,
                'options' => ['prompt' => 'Seleccione el grupo de pago...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            
             
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("programacion-nomina/search_comprobante_cesantias") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
        </div>
    </div>
</div>
<?php $formulario->end() ?>
<div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#intereses" aria-controls="intereses" role="tab" data-toggle="tab">Interes a las cesantias <span class="badge"><?= $pagination->totalCount ?></span></a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="intereses">
            <div class="table-responsive">
                <div class="panel panel-success">
                    <div class="panel-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                               <tr style='font-size:85%;'>                
                                    <th scope="col" style='background-color:#B9D5CE;'>Id</th>
                                    <th scope="col" style='background-color:#B9D5CE;'>No programacion</th>
                                    <th scope="col" style='background-color:#B9D5CE;'>Documento</th>                
                                    <th scope="col" style='background-color:#B9D5CE;'>Empleado</th>  
                                    <th scope="col" style='background-color:#B9D5CE;'>Desde</th>  
                                    <th scope="col" style='background-color:#B9D5CE;'>Hasta</th>  
                                    <th scope="col" style='background-color:#B9D5CE;'>Año</th> 
                                    <th scope="col" style='background-color:#B9D5CE;'>Valor cesantia</th> 
                                    <th scope="col" style='background-color:#B9D5CE;'>Valor interes</th> 
                                    <th scope="col" style='background-color:#B9D5CE;'>% pago</th> 
                                    <th scope="col" style='background-color:#B9D5CE;'>Nro dias</th> 
                                    <th scope="col" style='background-color:#B9D5CE;'>User name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($modelo as $val): ?>
                                    <tr style='font-size:85%;'>
                                         <td><?= $val->id_interes?></td>
                                         <td><?= $val->id_programacion?></td>
                                         <td><?= $val->documento?></td>
                                         <td><?= $val->empleado->nombre_completo?></td>
                                         <td><?= $val->fecha_inicio ?></td>
                                         <td><?= $val->fecha_corte ?></td>
                                          <td><?= $val->anio ?></td>
                                         <td style="text-align: right"><?= '$'.number_format($val->valor_cesantias,0)?></td>
                                         <td style="text-align: right"><?= '$'.number_format($val->valor_intereses,0)?></td>
                                         <td><?= $val->porcentaje?></td>
                                         <td style="text-align: right"><?= $val->dias_generados?></td>
                                         <td><?= $val->user_name?></td>
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
                                <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar a excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>
                               
                            <?php $form->end() ?>
                        </div>
                    </div>    
                </div>
            </div>    
        </div>
        <!-- TERMINA EL TABS-->
    </div>
</div>

<?= LinkPager::widget(['pagination' => $pagination]) ?>







