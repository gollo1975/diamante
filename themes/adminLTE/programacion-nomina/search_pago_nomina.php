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

$this->title = 'Comprobante de pago';
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
    "action" => Url::toRoute("programacion-nomina/search_comprobante_nomina"),
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
$tipo_pago = ArrayHelper::map(TipoNomina::find()->where(['=','ver_registro', 1])->orderBy('id_tipo_nomina ASC')->all(), 'id_tipo_nomina', 'tipo_pago');
$interes = ArrayHelper::map(\app\models\ConfiguracionSalario::find()->orderBy('id_salario DESC')->all(), 'anio', 'anio');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, 'id_empleado')->widget(Select2::classname(), [
                'data' => $empleado,
                'options' => ['prompt' => 'Seleccione el empleado ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
          
            <?= $formulario->field($form, 'id_grupo_pago')->widget(Select2::classname(), [
                'data' => $grupo,
                'options' => ['prompt' => 'Seleccione el grupo de pago...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            
             <?= $formulario->field($form, 'fecha_desde')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
            <?= $formulario->field($form, 'fecha_hasta')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
              <?= $formulario->field($form, "cedula_empleado")->input("search") ?>
             <?= $formulario->field($form, 'id_tipo_nomina')->widget(Select2::classname(), [
                'data' => $tipo_pago,
                'options' => ['prompt' => 'Tipo de pago.'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <?= $formulario->field($form, 'anio')->dropDownList($interes,['prompt' => 'Seleccione ...']) ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("programacion-nomina/search_comprobante_nomina") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
        </div>
    </div>
</div>
<?php $formulario->end() ?>
<div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#pagogeneral" aria-controls="pagogeneral" role="tab" data-toggle="tab">Soporte de pago nomina <span class="badge"><?= $pagination->totalCount ?></span></a></li>
         
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="pagogeneral">
            <div class="table-responsive">
                <div class="panel panel-success">
                    <div class="panel-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr style='font-size:85%;'align="center" >                
                                    <th scope="col" style='background-color:#B9D5CE;'>Nro_pago</th>
                                    <th scope="col" style='background-color:#B9D5CE;'>Tipo pago</th>
                                    <th scope="col" style='background-color:#B9D5CE;'>Documento</th>
                                    <th scope="col" style='background-color:#B9D5CE;'>Empleado</th>
                                    <th scope="col" style='background-color:#B9D5CE;'>Grupo pago</th>
                                    <th scope="col" style='background-color:#B9D5CE;'>Fecha inicio</th>
                                    <th scope="col" style='background-color:#B9D5CE;'>Fecha final</th>
                                    <th scope="col" style='background-color:#B9D5CE;'>Devengado</th>
                                    <th scope="col" style='background-color:#B9D5CE;'>Deducciones</th>
                                    <th scope="col" style='background-color:#B9D5CE;'>Neto pagar</th>
                                    <th scope="col" style='background-color:#B9D5CE;'></th>                               
                                </tr>
                            </thead>
                            <tbody>
                                <?php $nomina = 0;
                                     foreach ($modelo as $val):
                                         $nomina += $val->total_pagar;
                                         ?>
                                    <tr style='font-size:85%;'>                
                                        <td><?= $val->nro_pago ?></td>
                                        <td><?= $val->tipoNomina->tipo_pago ?></td>
                                        <td><?= $val->cedula_empleado ?></td>
                                        <td><?= $val->empleado->nombre_completo ?></td>
                                        <td><?= $val->grupoPago->grupo_pago ?></td>
                                        <td><?= $val->fecha_desde ?></td>
                                        <td><?= $val->fecha_hasta ?></td>
                                        <td align="right"><?= number_format($val->total_devengado,0) ?></td>
                                        <td align="right"><?= number_format($val->total_deduccion,0) ?></td>
                                        <td align="right"><?= number_format($val->total_pagar,0) ?></td>
                                        <td style= 'width: 20px; height: 20px'>				
                                        <a href="<?= Url::toRoute(["programacion-nomina/detallepagonomina", "id_programacion" => $val->id_programacion]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>                
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>        
                             <tr>
                                  <td colspan="8"></td>
                                  <td align="right"><b>Valor Nomina</b></td>
                                  <td align="right" ><b><?= '$ '.number_format($nomina,0); ?></b></td>
                                  <td colspan="1"></td>
                              </tr>
                        </table>
                        <div class="panel-footer text-right" >            
                            <?php
                                $form = ActiveForm::begin([
                                            "method" => "post",                            
                                        ]);
                                ?>    
                                <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>
                                <a align="right" href="<?= Url::toRoute(["programacion-nomina/detalle_nomina",'empleado' => $nombre_empleado,'fecha_inicio' => $fecha_inicio, 'fecha_corte' => $fecha_corte, 'grupo_pago' => $grupo_pago, 'tipo_nomina' => $tipo_nomina]) ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-export'></span> Excel detalle</a>
                                
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







