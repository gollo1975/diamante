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

$this->title = 'Pago adicional por fecha';
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
    "action" => Url::toRoute("pago-adicional-fecha/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-4 control-label'],
                    'options' => []
                ],

]);
?>
<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
              <?= $formulario->field($form, 'estado_proceso')->dropDownList(["0" => 'ABIERTO', "1" => 'CERRADO'],['prompt' => 'Seleccione una opcion ...']) ?>
        </div> 
        <div class="row">
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
            <a align="right" href="<?= Url::toRoute("pago-adicional-fecha/index") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
         Registros:<span class="badge"><?= $pagination->totalCount ?></span>
       
    </div>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>                
                <th scope="col" style='background-color:#B9D5CE;'>Id_pago</th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha corte</th>
                <th scope="col" style='background-color:#B9D5CE;'>Fecha hora registro</th>
                <th scope="col" style='background-color:#B9D5CE;'>Detalle</th>
                <th scope="col" style='background-color:#B9D5CE;'>Abierto/Cerrado</th>                
                <th scope="col" style='background-color:#B9D5CE;'>Usuario</th> 
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th score="col" style='background-color:#B9D5CE;'></th>                              
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val):
                $buscar = \app\models\PagoAdicionalPermanente::find()->where(['=','id_pago_fecha', $val->id_pago_fecha])->one();
                ?>
            <tr>                
                <td><?= $val->id_pago_fecha?></td>
                <td><?= $val->fecha_corte?></td>
                <td><?= $val->fecha_hora_creacion?></td>
                <td><?= $val->detalle?></td>
                <td><?= $val->estadoRegistro?></td>
                <td><?= $val->user_name?></td>
                <?php if($val->estado_registro == 1){?>
                    <td style="width: 25px;">
                        <a href="<?= Url::toRoute(["pago-adicional-fecha/view", "id" => $val->id_pago_fecha, 'fecha_corte' => $val->fecha_corte]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>                   
                   </td>
                   <td>
                   </td>
                   <td>
                   </td>
                <?php }else{
                    if($buscar){?>
                        <td style="width: 25px;">
                            <a href="<?= Url::toRoute(["pago-adicional-fecha/view", "id" => $val->id_pago_fecha, 'fecha_corte' => $val->fecha_corte]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>                   
                       </td>
                       <td style="width: 25px;">
                            <?= Html::a('<span class="glyphicon glyphicon-eye-close"></span> ', ['pago-adicional-fecha/cerrar_proceso', 'id' => $val->id_pago_fecha], [
                                                   'class' => '',
                                                   'title' => 'Proceso que cierra el registro de los adicionales.', 
                                                   'data' => [
                                                       'confirm' => 'Esta seguro de CERRAR el registro que permite el ingreso de ADICIONALES al pago de nomina.',
                                                       'method' => 'post',
                                                   ],
                             ]);?>
                       </td>   
                       <td style="width: 25px;"></td>
                    <?php }else{?> 
                        <td style="width: 25px;">
                            <a href="<?= Url::toRoute(["pago-adicional-fecha/view", "id" => $val->id_pago_fecha, 'fecha_corte' => $val->fecha_corte]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>                   
                        </td>
                        <td style="width: 25px;">
                             <a href="<?= Url::toRoute(["pago-adicional-fecha/update", "id" => $val->id_pago_fecha, 'fecha_corte' => $val->fecha_corte]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>                   
                        </td>
                        <td style="width: 25px;">
                           <?= Html::a('', ['eliminar', 'id' => $val->id_pago_fecha, 'fecha_corte' => $val->fecha_corte], [
                             'class' => 'glyphicon glyphicon-trash',
                             'data' => [
                                 'confirm' => 'Esta seguro de eliminar el registro?',
                                 'method' => 'post',
                             ],
                           ]) ?>
                       </td>
                    <?php }   
                }?>    
             </tr>            
            </tbody>            
            <?php endforeach; ?>
        </table> 
      <div class="panel-footer text-right" >            
                <a align="right" href="<?= Url::toRoute("pago-adicional-fecha/create") ?>" class="btn btn-success"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>
            <?php $form->end() ?>
        </div>
     </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>

