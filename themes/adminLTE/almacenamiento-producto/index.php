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
$this->title = 'CONSULTA (ALMACENAMIENTO CON OP )';
$this->params['breadcrumbs'][] = $this->title;

?>
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtro");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "block";}
    }
</script>

<!--<h1>Lista Facturas</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("almacenamiento-producto/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$tipo_rack = ArrayHelper::map(app\models\TipoRack::find()->all(), 'id_rack', 'descripcion');
$conPosicion = ArrayHelper::map(app\models\Posiciones::find()->all(), 'id_posicion', 'posicion');
$conPiso = ArrayHelper::map(app\models\Pisos::find()->all(), 'id_piso', 'descripcion');
?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtro" style="display:block">
        <div class="row" >
            <?= $formulario->field($form, "codigo")->input("search") ?>
            <?= $formulario->field($form, "producto")->input("search") ?>
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
            <?= $formulario->field($form, 'rack')->widget(Select2::classname(), [
                'data' => $tipo_rack,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <?= $formulario->field($form, 'posicion')->widget(Select2::classname(), [
                'data' => $conPosicion,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
             <?= $formulario->field($form, 'piso')->widget(Select2::classname(), [
                'data' => $conPiso,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
              <?= $formulario->field($form, "lote")->input("search") ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
            <a align="right" href="<?= Url::toRoute("almacenamiento-producto/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
            <?php if($model){?>
              Registros <span class="badge"><?= $pagination->totalCount ?></span>
            <?php }?>  
        </div>
        <table class="table table-bordered table-hover">
        <thead>
            <tr style ='font-size: 85%;'>         
            <th scope="col" style='background-color:#B9D5CE;'>Id alm.</th>
            <th scope="col" style='background-color:#B9D5CE;'>Piso</th> 
            <th scope="col" style='background-color:#B9D5CE;'>Rack</th>
             <th scope="col" style='background-color:#B9D5CE;'>Capacidad</th>
            <th scope="col" style='background-color:#B9D5CE;'>U. rack</th>
             <th scope="col" style='background-color:#B9D5CE;'>Posición</th>
            <th scope="col" style='background-color:#B9D5CE;'>Op</th>
            <th scope="col" style='background-color:#B9D5CE;'>No lote</th>
            <th scope="col" style='background-color:#B9D5CE;'>F. almacenamiento</th>
            <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
            <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
            <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
             <th scope="col" style='background-color:#B9D5CE;'></th>


        </tr>
        </thead>
        <tbody>
        <?php 
        if($model){
            foreach ($model as $val):?>
                <tr style ='font-size: 85%;'>                
                    <td><?= $val->id_almacenamiento?></td>
                    <td><?= $val->piso->descripcion?></td>
                    <td><?= $val->rack->descripcion?></td>
                    <td><?= $val->rack->capacidad_instalada?></td>
                    <td><?= $val->rack->capacidad_actual?></td>
                    <td><?= $val->posicion->posicion?></td>
                    <?php if($val->id_orden_produccion <> null){?>
                        <td><?= $val->ordenProduccion->numero_orden?></td>
                    <?php }else{?>     
                        <td><?= $val->devolucionProducto->numero_devolucion?></td>    
                    <?php }?>    
                    <td><?= $val->numero_lote?></td>
                    <td><?= $val->fecha_almacenamiento?></td>
                    <td><?= $val->codigo_producto?></td>
                    <td><?= $val->producto?></td>
                    <td style="text-align: right"><?= ''.number_format($val->cantidad, 0)?></td>
                    <?php if($val->id_orden_produccion <> null){?>
                        <td style= 'width: 25px; height: 20px;'>
                             <a href="<?= Url::toRoute(["almacenamiento-producto/view_almacenamiento_search", "id_orden" => $val->id_orden_produccion, 'token' => 2]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                        </td>
                    <?php }else{?>    
                        <td style= 'width: 25px; height: 20px;'>
                             <a href="<?= Url::toRoute(["almacenamiento-producto/view_almacenamiento_devolucion", "id_devolucion" => $val->id_devolucion, 'token' => 1]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                        </td>
                    <?php }?>        
                </tr>            
            <?php endforeach; 
        }   ?>
        </tbody>    
    </table> 
        <div class="panel-footer text-right" >
           <?php if($model){?>
               <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> Exportar excel", ['name' => 'excel','class' => 'btn btn-primary btn-sm']); ?>
           <?php }
           $form->end() ?>
        </div>
    </div>    
</div>
<?php if($model){?>
    <?= LinkPager::widget(['pagination' => $pagination]) ?>
<?php }?>
