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


$this->title = 'CIERRE DE CAJA ('.$conPunto->nombre_punto.')';
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
    "action" => Url::toRoute("cierre-caja/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtrocliente" style="display:block">
        <div class="row" >
            <?= $formulario->field($form, "numero_cierre")->input("search") ?>
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
               
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("cierre-caja/index") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <th scope="col" style='background-color:#B9D5CE;'>No cierre</th>
                <th scope="col" style='background-color:#B9D5CE;'>Punto de venta</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. inicio</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. corte</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total remision</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total factura</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total caja</th>
                <th scope="col" style='background-color:#B9D5CE;'>Cerrado</th>
                <th scope="col" style='background-color:#B9D5CE;'></th>  
            </tr>
            </thead>
            <tbody>
            <?php
           
                foreach ($model as $val): ?>
            <tr style="font-size: 90%;">                   
                 <td><?= $val->numero_cierre ?></td>
                <td><?= $val->punto->nombre_punto ?></td>
                <td><?= $val->fecha_inicio ?></td>
                <td><?= $val->fecha_corte ?></td>
                <td style="text-align: right"><?= ''.number_format($val->total_remision,0) ?></td>
               <td style="text-align: right"><?= ''.number_format($val->total_factura,0) ?></td>
               <td style="text-align: right"><?= ''.number_format($val->total_cierre_caja,0) ?></td>
                <?php if($val->proceso_cerrado == 0){?>
                    <td style='background-color:#F7EDEA;'><?= $val->procesoCerrado ?></td>
                <?php }else{?>
                    <td style='background-color:#F6C8BE;'><?= $val->procesoCerrado ?></td>
                <?php }?>
                <td style= 'width: 25px; height: 20px;'>
                    <a href="<?= Url::toRoute(["cierre-caja/view", "id" => $val->id_cierre, 'accesoToken' => $accesoToken]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                </td>
                <td style= 'width: 25px; height: 20px;'>
                    
                </td>
            </tr>
            </tbody>
            <?php endforeach; ?>
          
        </table>
        <div class="panel-footer text-right" >
             <?php
                $form = ActiveForm::begin([
                            "method" => "post",                            
                        ]);
                ?> 
                <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>
                <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Crear cierre', ['create','accesoToken' => $accesoToken], [
                                           'class' => '',
                                           'title' => 'Proceso que permite crear el cierre de caja de cada punto.', 
                                           'class' => 'btn btn-success btn-sm',
                                           'data' => [
                                               'confirm' => 'Esta seguro de crear el cierre de caja del punto de venta ('.$conPunto->nombre_punto.').',
                                               'method' => 'post',
                                            ''   
                                           ], 
                                           
                ])?>
                
                <?php $form->end() ?>
            
        </div>
    </div>
</div>
 
   <?= LinkPager::widget(['pagination' => $pagination]) ?>
 