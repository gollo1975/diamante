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
$this->title = 'ORDEN DE PRODUCCION (ALMACENAMIENTO)';
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
    "action" => Url::toRoute("almacenamiento-producto/cargar_orden_produccion"),
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
	
    <div class="panel-body" id="filtro" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "orden")->input("search") ?>
            <?= $formulario->field($form, "lote")->input("search") ?>
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
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("almacenamiento-producto/cargar_orden_produccion") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <tr style ='font-size: 90%;'>         
                 <th scope="col" style='background-color:#B9D5CE;'>Id</th>
                <th scope="col" style='background-color:#B9D5CE;'>No Orden</th>
                <th scope="col" style='background-color:#B9D5CE;'>Grupo producto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Bodega</th>
                <th scope="col" style='background-color:#B9D5CE;'>No lote</th>
                <th scope="col" style='background-color:#B9D5CE;'>F. proceso</th>
                <th scope="col" style='background-color:#B9D5CE;'>Unidades fabricadas</th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                         
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): 
                $almacenar = app\models\AlmacenamientoProducto::find()->where(['=','id_orden_produccion', $val->id_orden_produccion])->orderBy('id_orden_produccion DESC')->one();
                 ?>
            <tr style ='font-size: 90%;'>                
                <td><?= $val->id_orden_produccion?></td>
                <td><?= $val->numero_orden?></td>
                <td><?= $val->grupo->nombre_grupo?></td>
                 <td><?= $val->almacen->almacen?></td>
                <td><?= $val->numero_lote?></td>
                <td><?= $val->fecha_proceso?></td>
                <td style="text-align: right"><?= ''.number_format($val->unidades, 0)?></td>
                <?php if($almacenar){?>
                    <td style= 'width: 20px; height: 20px;'>
                             <a href="<?= Url::toRoute(["almacenamiento-producto/view_almacenamiento", "id_orden" => $val->id_orden_produccion]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite ver la vista de los lotes"></span></a>
                    </td>
                <?php }else{ ?>
                    <td style= 'width: 20px; height: 20px;'>
                         <?= Html::a('<span class="glyphicon glyphicon-list"></span>', ['enviar_lote_almacenar', 'id_orden' => $val->id_orden_produccion], [
                                'class' => '',
                                'title' => 'Permite almacenar los lotes de la orden de produccion',
                                'data' => [
                                    'confirm' => '¿Esta seguro que se desea ALMACENAR  los lotes que estan creados en la OP No ( '.$val->numero_orden.') ?',
                                    'method' => 'post',
                                ],
                                ])?>
                    </td>
                <?php }?>    
            </tr>            
            <?php endforeach; ?>
            </tbody>    
        </table> 
           
        <?php $form->end() ?>
     </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>
