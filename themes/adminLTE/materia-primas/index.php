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
use app\models\MedidaMateriaPrima;
use app\models\TipoSolicitud;



$this->title = 'MATERIA PRIMA';
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
    "action" => Url::toRoute("materia-primas/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);

$medida = ArrayHelper::map(MedidaMateriaPrima::find()->orderBy ('descripcion ASC')->all(), 'id_medida', 'descripcion');
$conSolicitud = ArrayHelper::map(TipoSolicitud::find()->orderBy ('id_solicitud ASC')->all(), 'id_solicitud', 'descripcion');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "codigo")->input("search") ?>
            <?= $formulario->field($form, "materia_prima")->input("search") ?>
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
            <?= $formulario->field($form, 'medida')->widget(Select2::classname(), [
                'data' => $medida,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <?= $formulario->field($form, 'aplica_inventario')->dropDownList(['0' => 'NO', '1' => 'SI'],['prompt' => 'Seleccione una opcion ...']) ?>
            <?= $formulario->field($form, "codigo_barra")->input("search") ?>
             <?= $formulario->field($form, 'tipo_solicitud')->widget(Select2::classname(), [
                'data' => $conSolicitud,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="row checkbox checkbox-success" align ="center">
                <?= $formulario->field($form, 'busqueda_vcto')->checkbox(['label' => 'Aplica fecha vencimiento', '1' =>'small', 'class'=>'bs_switch','style'=>'margin-bottom:10px;', 'id'=>'busqueda_vcto']) ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("materia-primas/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <tr style ='font-size: 85%;'>         
                
                <th scope="col" style='background-color:#B9D5CE;'>Código</th>
                <th scope="col" style='background-color:#B9D5CE;'>Nombre materia prima</th>
                <th scope="col" style='background-color:#B9D5CE;'>Medida</th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha entrada</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Aplica inventario">Ap. Inv.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'>Entrada</th>
                <th scope="col" style='background-color:#B9D5CE;'>Stock unidades</th>
                <th scope="col" style='background-color:#B9D5CE;'>Stock en gramos</th>
                <th scope="col" style='background-color:#B9D5CE;'>Salida </th>
                <th scope="col" style='background-color:#B9D5CE;'>Clasificacion </th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th score="col" style='background-color:#B9D5CE;'></th>                              
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr style ='font-size: 85%;'>                
              
                <td><?= $val->codigo_materia_prima?></td>
                <td><?= $val->materia_prima?></td>
                <td><?= $val->medida->descripcion?></td>
                <td><?= $val->fecha_entrada?></td>
                <td><?= $val->aplicaInventario?></td>
                <td style="text-align: right"><?= ''.number_format($val->total_cantidad,0)?></td>
                <?php if($val->stock > 0){?>
                   <td style="text-align: right; background-color:#F5EEF8;"><?= ''.number_format($val->stock,0)?></td>
                <?php }else{ ?>
                   <td style="text-align: right;"><?= ''.number_format($val->stock,0)?></td>
                <?php }?>   
                   <td style="text-align: right"><?= ''.number_format($val->stock_gramos,0)?></td>
                    <td style="text-align: right"><?= ''.number_format($val->salida_materia_prima,0)?></td>
                <td><?= $val->tipoSolicitud->descripcion?></td>
                <td style= 'width: 25px; height: 10px;'>
                    <a href="<?= Url::toRoute(["materia-primas/view", "id" => $val->id_materia_prima, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                </td>
                <?php if($val->stock == $val->total_cantidad){?>
                    <td style= 'width: 25px; height: 10px;'>
                       <a href="<?= Url::toRoute(["materia-primas/update", "id" => $val->id_materia_prima]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>                   
                    </td>
                <?php }else{?>
                    <td style= 'width: 25px; height: 10px;'></td>
                <?php }?>    
            </tr>            
            <?php endforeach; ?>
            </tbody>    
        </table> 
        <div class="panel-footer text-right" >            
           <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>                
            <a align="right" href="<?= Url::toRoute("materia-primas/create") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>
        <?php $form->end() ?>
        </div>
     </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
