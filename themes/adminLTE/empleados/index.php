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


$this->title = 'EMPLEADOS';
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
    "action" => Url::toRoute("empleados/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$tipoEmpleado = ArrayHelper::map(\app\models\TipoEmpleado::find()->orderBy('descripcion ASC')->all(), 'tipo_empleado', 'descripcion');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtrocliente" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "documento")->input("search") ?>
            <?= $formulario->field($form, "empleado")->input("search") ?>
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
            <?= $formulario->field($form, 'tipo_empleado')->widget(Select2::classname(), [
                'data' => $tipoEmpleado,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
            <?= $formulario->field($form, 'estado')->dropdownList(['' => 'TODOS','0' => 'SI', '1' => 'NO'], ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("empleados/index") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
           <tr style="font-size: 90%;">    
                <th scope="col" style='background-color:#B9D5CE;'>Tipo</th>
                <th scope="col" style='background-color:#B9D5CE;'>Documento</th>
                <th scope="col" style='background-color:#B9D5CE;'>Empleado</th>
                <th scope="col" style='background-color:#B9D5CE;'>Dirección</th>
                <th scope="col" style='background-color:#B9D5CE;'>Celular</th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha ingreso</th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha retiro</th>
                <th scope="col" style='background-color:#B9D5CE;'>Dpto residencia</th>
                <th scope="col" style='background-color:#B9D5CE;'>Municipio residencia</th>
                <th scope="col" style='background-color:#B9D5CE;'></th>  
                <th scope="col" style='background-color:#B9D5CE;'></th> 
                <th scope="col" style='background-color:#B9D5CE;'></th> 
            </tr>
            </thead>
            <tbody>
                <?php
                foreach ($model as $val): ?>
                    <tr style="font-size: 90%;">                   
                         <td><?= $val->tipoDocumento->tipo_documento ?></td>
                        <td><?= $val->nit_cedula ?></td>
                        <td><?= $val->nombre_completo ?></td>
                        <td><?= $val->direccion ?></td>
                        <td><?= $val->celular ?></td>
                        <td><?= $val->fecha_ingreso ?></td>
                        <?php if($val->fecha_retiro == '2099-12-30'){?>
                        <td style="background-color: #d7d9ff;"><?= 'INDEFINIDO' ?></td>
                        <?php }else{?>
                            <td><?= $val->fecha_retiro ?></td>
                        <?php }?>    
                        <td><?= $val->codigoDepartamentoResidencia->departamento ?></td>
                        <td><?= $val->codigoMunicipioResidencia->municipio ?></td>
                        <td style= 'width: 25px; height: 20px;'>
                            <a href="<?= Url::toRoute(["empleados/view", "id" => $val->id_empleado, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                        </td>
                        <td style= 'width: 25px; height: 20px;'>
                            <a href="<?= Url::toRoute(["empleados/update", "id" => $val->id_empleado])?>" ><span class="glyphicon glyphicon-pencil"></span></a>
                        </td>
                        <?php if($val->estado == '' || $val->estado == 1){?>
                            <td style= 'width: 25px; height: 10px;'>
                                    <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ', ['contratos/create', 'id_empleado' => $val->id_empleado], [
                                                  'class' => '',
                                                  'title' => 'Proceso que permite crear contratos de trabajo.', 
                                              'data' => [
                                                  'confirm' => 'Esta seguro de crear el contrato de trabajo al empleado :  ('.$val->nombre_completo.').',
                                                  'method' => 'post',
                                              ],
                                ]);?>
                            </td> 
                        <?php }else{?>
                            <td style= 'width: 25px; height: 10px;'></td>
                        <?php }?>
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
                <a align="right" href="<?= Url::toRoute("empleados/create") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>   
           
              <?php $form->end() ?>
            
        </div>
    </div>
</div>
 
   <?= LinkPager::widget(['pagination' => $pagination]) ?>
