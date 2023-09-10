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
use app\models\Clientes;
use app\models\User;

$this->title = 'PRESUPUESTO MENSUAL';
$this->params['breadcrumbs'][] = $this->title;
?>
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtropedido");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
    }
</script>

<!--<h1>Lista proveedor</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("presupuesto-empresarial/presupuesto_mensual"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$presupueso = ArrayHelper::map(app\models\PresupuestoEmpresarial::find()->orderBy ('id_presupuesto ASC')->all(), 'id_presupuesto', 'descripcion');

?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtropedido" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, 'presupuesto')->widget(Select2::classname(), [
                'data' => $presupueso,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                ]);
            ?> 
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
           
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("presupuesto-empresarial/presupuesto_mensual") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr style="font-size: 90%;">   
                    <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Departamento</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Desde</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Hasta</th>
                    <th scope="col" style='background-color:#B9D5CE;'>V. gastado</th>
                    <th scope="col" style='background-color:#B9D5CE;'>Registros</th>
                    <th scope="col" style='background-color:#B9D5CE;'><span title="Proceso autorizado">Aut.</span></th>
                    <th scope="col" style='background-color:#B9D5CE;'><span title="Proceso cerrado">Cerr.</span></th>
                    <th scope="col" style='background-color:#B9D5CE;'></th>  
                    <th scope="col" style='background-color:#B9D5CE;'></th>  
                     </tr>
            </thead>    
            <tbody>
                <?php foreach ($model as $val):
                    $detalle = \app\models\PresupuestoMensualDetalle::find()->where(['=','id_mensual', $val->id_mensual])->all();
                    ?>
                    <tr style="font-size: 90%;">  
                        <td><?= $val->id_mensual ?></td>
                        <td><?= $val->presupuesto->descripcion ?></td>
                        <td><?= $val->fecha_inicio ?></td>
                        <td><?= $val->fecha_corte ?></td>
                        <td style="text-align:right"><?= ''.number_format($val->valor_gastado, 0) ?></td>
                        <td style="text-align:right"><?= ''.number_format($val->total_registro,0) ?></td>
                        <td><?= $val->autorizadoMes ?></td>
                        <td><?= $val->cerradoMes ?></td>
                        <td style= 'width: 25px; height: 25px;'>
                            <a href="<?= Url::toRoute(["presupuesto-empresarial/view_cliente", "id" => $val->id_mensual, 'id_presupuesto' => $val->presupuesto->id_presupuesto, 'desde' => $val->fecha_inicio, 'hasta' => $val->fecha_corte,'cerrado' => $val->cerrado]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                        </td>
                        <?php if(count($detalle) > 0){?>
                            <td style= 'width: 25px; height: 25px;'></td>
                        <?php }else{?>
                            <td style= 'width: 25px; height: 25px;'>
                                <a href="<?= Url::toRoute(["presupuesto-empresarial/editar_cliente", "id" => $val->id_mensual]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>
                            </td>
                        <?php }?>    
                    </tr>    
                <?php endforeach;?>
            </tbody>
        </table>   
      <div class="panel-footer text-right">
        <a align="right" href="<?= Url::toRoute("presupuesto-empresarial/crear_fechas") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>
      </div>  
    </div>
    <?php $form->end() ?>
</div>

<?= LinkPager::widget(['pagination' => $pagination]) ?>