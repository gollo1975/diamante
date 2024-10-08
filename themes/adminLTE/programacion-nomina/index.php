<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use app\models\PeriodoPago;
use app\models\PeriodoPagoNomina;
use app\models\GrupoPago;
use app\models\TipoNomina;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;

$this->title = 'Periodos de Nómina';
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
    "action" => Url::toRoute("programacion-nomina/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);

$grupo = ArrayHelper::map(GrupoPago::find()->orderBy ('grupo_pago ASC')->all(), 'id_grupo_pago', 'grupo_pago');
$periodo = ArrayHelper::map(PeriodoPago::find()->all(), 'id_periodo_pago', 'nombre_periodo');
$tiponomina = ArrayHelper::map(TipoNomina::find()->all(), 'id_tipo_nomina', 'tipo_pago');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, 'id_grupo_pago')->widget(Select2::classname(), [
                'data' => $grupo,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
             <?= $formulario->field($form, 'id_periodo_pago')->widget(Select2::classname(), [
                'data' => $periodo,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
             <?= $formulario->field($form, 'id_tipo_nomina')->widget(Select2::classname(), [
                'data' => $tiponomina,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <?= $formulario->field($form, 'estado_periodo')->dropDownList(['0' => 'Todos'],['prompt' => 'Seleccione el estado ...']) ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("programacion-nomina/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
        Registros:<span class="badge"> <?= $pagination->totalCount ?></span>
    </div>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>                
                <th scope="col" style='background-color:#B9D5CE;'>Id</th>
                <th scope="col" style='background-color:#B9D5CE;'>Tipo de pago</th>
                <th scope="col" style='background-color:#B9D5CE;'>Grupo de pago</th>
                <th scope="col" style='background-color:#B9D5CE;'>Periodo de pago</th>
                <th scope="col" style='background-color:#B9D5CE;'>Desde</th>                
                <th scope="col" style='background-color:#B9D5CE;'>Hasta</th>
                <th scope="col" style='background-color:#B9D5CE;'>Dias</th>
                <th scope="col" style='background-color:#B9D5CE;'>No empleados</th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
 
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr style='font-size:85%;'>                
                <td><?= $val->id_periodo_pago_nomina ?></td>
                <td><?= $val->tipoNomina->tipo_pago ?></td>
                <td><?= $val->grupoPago->grupo_pago ?></td>
                <td><?= $val->periodoPago->nombre_periodo?></td>
                <td><?= $val->fecha_desde ?></td>
                <td><?= $val->fecha_hasta?></td>
                <td><?= $val->dias_periodo?></td>
                 <td><?= $val->cantidad_empleado?></td>
                 <?php
                 if($val->estado_periodo == 1){?>
                 
                     <td style= 'width: 25px;'>
                        <a href="<?= Url::toRoute(["programacion-nomina/view", "id" => $val->id_periodo_pago_nomina, 'id_grupo_pago' => $val->id_grupo_pago, 'fecha_desde' => $val->fecha_desde, 'fecha_hasta' => $val->fecha_hasta]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                     </td>
                     <td colspan="2"><p style="color:green;" align = "center">Closed</p></td>
                      
                 <?php }else{?>
                        <td style= 'width: 30px;'>
                        <a href="<?= Url::toRoute(["programacion-nomina/view", "id" => $val->id_periodo_pago_nomina, 'id_grupo_pago' => $val->id_grupo_pago, 'fecha_desde' => $val->fecha_desde, 'fecha_hasta' => $val->fecha_hasta]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                     </td>
                     <td style= 'width: 30px;'>
                         <a href="<?= Url::toRoute(["programacion-nomina/editar", "id" => $val->id_periodo_pago_nomina]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>                   
                    </td>
                  
                <?php }?>        
            </tr>            
            </tbody>            
            <?php endforeach; ?>
        </table> 
      <div class="panel-footer text-right" >            
                <a align="right" href="<?= Url::toRoute("programacion-nomina/nuevo") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>
           
        </div>
     </div>
     <?php $form->end() ?>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
