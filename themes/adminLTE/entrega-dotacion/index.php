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

$this->title = 'ENTREGA DE DOTACION';
$this->params['breadcrumbs'][] = $this->title;

$conEmpleado = ArrayHelper::map(app\models\Empleados::find()->orderBy ('nombre_completo ASC')->all(), 'id_empleado', 'nombre_completo');
$TipoDotacion = ArrayHelper::map(app\models\TipoDotacion::find()->orderBy('descripcion')->all(), 'id_tipo_dotacion', 'descripcion');
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
    "action" => Url::toRoute("entrega-dotacion/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
?>
<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>

    <div class="panel-body" id="filtrocliente" style="display:block">
        <div class="row" >
            <?= $formulario->field($form, "numero")->input("search") ?>
            <?= $formulario->field($form, 'empleado')->widget(Select2::classname(), [
            'data' => $conEmpleado,
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
                    'todaHighlight' => true]])
            ?>
             <?= $formulario->field($form, 'hasta')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todaHighlight' => true]])
            ?>
            
        <?= $formulario->field($form, 'tipo_dotacion')->dropDownList($TipoDotacion,['prompt' => 'Seleccione'] ); ?>

        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("entrega-dotacion/index") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
        </div>
    </div>
</div>

<?php $formulario->end() ?>

<div class="table-responsive">
    <div class="panel panel-success ">
        <div class="panel-heading">
            Registros <span class="badge"><?= $pagination->totalCount ?></span>
        </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr style="font-size: 85%;">    
                     <th scope="col" style='background-color:#B9D5CE;'>Numero</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Empleado</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Tipo dotacion</th>
                      <th scope="col" style='background-color:#B9D5CE;'>Tipo proceso</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Fecha entrega</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Cantidad</th>
                     <th scope="col" style='background-color:#B9D5CE;'><span class="badge" title="Se descargo del inventario">Inv.</span></th>
                     <th scope="col" style='background-color:#B9D5CE;'></th>  
                     <th scope="col" style='background-color:#B9D5CE;'></th>  

                 </tr>
            </thead>
            <tbody>
            <?php 
            foreach ($model as $val):
                $detalle = app\models\EntregaDotacionDetalles::find()->where(['=','id_entrega', $val->id_entrega])->one();
                ?>
                    <tr style="font-size: 85%;">                   
                        <td><?= $val->numero_entrega ?></td>
                        <td><?= $val->empleado->nombre_completo ?></td>
                        <td><?= $val->tipoDotacion->descripcion ?></td>
                         <td><?= $val->tipoProceso ?></td>
                        <td><?= $val->fecha_entrega ?></td>
                        <td style="text-align: right"><?= $val->cantidad ?></td>
                         <td><?= $val->descargoInventario ?></td>
                        <td style= 'width: 20px; right: 20px;'>
                            <a href="<?= Url::toRoute(["entrega-dotacion/view", "id" => $val->id_entrega,'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                        </td>
                        <?php if(!$detalle){?>
                            <td style= 'width: 20px; right: 20px;'>
                                <a href="<?= Url::toRoute(["entrega-dotacion/update", "id" => $val->id_entrega]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>
                            </td>
                        <?php }else{?>
                            <td style= 'width: 20px; right: 20px;'></td>
                        <?php }?>    
                    </tr>
            <?php endforeach;?>
           </tbody>        
        </table>
    </div>
    <div class="panel-footer text-right" >
             <?php
                $form = ActiveForm::begin([
                            "method" => "post",                            
                        ]);
                ?> 
                <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>
                <a align="right" href="<?= Url::toRoute(["entrega-dotacion/create",'token' => $token]) ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>   
           
              <?php $form->end() ?>
            
        </div>
</div>    
<?= LinkPager::widget(['pagination' => $pagination]) ?>


