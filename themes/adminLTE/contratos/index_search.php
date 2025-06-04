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
use app\models\Departamentos;
use app\models\Municipios;


$this->title = 'CONSULTA / CONTRATOS';
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
    "action" => Url::toRoute("contratos/index_search"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$conEmpleado = ArrayHelper::map(\app\models\Empleados::find()->orderBy('nombre_completo ASC')->all(), 'id_empleado', 'nombre_completo');
$conTipoContrato = ArrayHelper::map(\app\models\TipoContrato::find()->all(), 'id_tipo_contrato', 'contrato');
$conGrupo = ArrayHelper::map(\app\models\GrupoPago::find()->where(['=','estado', 0])->all(), 'id_grupo_pago', 'grupo_pago');
$conEps = ArrayHelper::map(\app\models\EntidadSalud::find()->where(['=','estado', 0])->all(), 'id_entidad_salud', 'entidad_salud');
$conPension = ArrayHelper::map(\app\models\EntidadPension::find()->where(['=','estado', 0])->all(), 'id_entidad_pension', 'entidad');


?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtrocliente" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, 'empleado')->widget(Select2::classname(), [
                'data' => $conEmpleado,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
            <?= $formulario->field($form, 'tipo_contrato')->widget(Select2::classname(), [
                'data' => $conTipoContrato,
                'options' => ['prompt' => 'Seleccione...'],
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
            <?= $formulario->field($form, 'grupo_pago')->widget(Select2::classname(), [
                'data' => $conGrupo,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
            <?= $formulario->field($form, 'eps')->widget(Select2::classname(), [
                'data' => $conEps,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
            <?= $formulario->field($form, 'pension')->widget(Select2::classname(), [
                'data' => $conPension,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
            <?= $formulario->field($form, 'estado')->dropdownList(['' => 'TODOS','0' => 'SI', '1' => 'NO'], ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("contratos/index_search") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
        </div>
    </div>
</div>

<?php $formulario->end() ?>

<div class="table-responsive">
<div class="panel panel-success ">
    <div class="panel-heading">
        <?php if($model){?> 
            Registros <span class="badge"><?= $pagination->totalCount ?></span>
        <?php } ?>     
    </div>
        <table class="table table-bordered table-hover">
            <thead>
           <tr style="font-size: 85%;">    
                <th scope="col" style='background-color:#B9D5CE;'>Nro contrato</th>
                <th scope="col" style='background-color:#B9D5CE;'>Tipo contrato</th>
                <th scope="col" style='background-color:#B9D5CE;'>Empleado</th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha inicio</th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha final</th>
                <th scope="col" style='background-color:#B9D5CE;'>Salario</th>
                <th scope="col" style='background-color:#B9D5CE;'>Grupo pago</th>
                <th scope="col" style='background-color:#B9D5CE;'></th> 
                
            </tr>
            </thead>
            <tbody>
                <?php
                foreach ($model as $val): ?>
                    <tr style="font-size: 85%;">                   
                         <td><?= $val->id_contrato ?></td>
                        <td><?= $val->tipoContrato->contrato ?></td>
                        <td><?= $val->empleado->nombre_completo ?></td>
                        <td><?= $val->fecha_inicio ?></td>
                         <?php if($val->fecha_final == '2099-12-30'){?>
                        <td style="background-color: #d7d9ff;"><?= 'INDEFINIDO' ?></td>
                        <?php }else{?>
                            <td><?= $val->fecha_final ?></td>
                        <?php }?>    
                        <td style = "text-align: right"><?= ''.number_format($val->salario, 0) ?></td>
                        <td><?= $val->grupoPago->grupo_pago?></td>
                        <td style= 'width: 25px; height: 20px;'>
                            <a href="<?= Url::toRoute(["contratos/view", "id" => $val->id_contrato, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
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
                <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>
           
              <?php $form->end() ?>
            
        </div>
    </div>
</div>
 
   <?= LinkPager::widget(['pagination' => $pagination]) ?>
