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

$this->title = 'PRESENTACION DEL PRODUCTO';
$this->params['breadcrumbs'][] = $this->title;

$producto = ArrayHelper::map(app\models\Productos::find()->orderBy ('nombre_producto ASC')->all(), 'id_producto', 'nombre_producto');
$grupo = ArrayHelper::map(app\models\GrupoProducto::find()->orderBy('nombre_grupo ASC')->all(), 'id_grupo', 'nombre_grupo');
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
    "action" => Url::toRoute("presentacion-producto/index"),
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
            <?= $formulario->field($form, "presentacion")->input("search") ?>
            <?= $formulario->field($form, 'producto')->widget(Select2::classname(), [
            'data' => $producto,
            'options' => ['prompt' => 'Seleccione...'],
            'pluginOptions' => [
                'allowClear' => true
                             ],
             ]); ?> 
             <?= $formulario->field($form, 'grupo')->widget(Select2::classname(), [
            'data' => $grupo,
            'options' => ['prompt' => 'Seleccione...'],
            'pluginOptions' => [
                'allowClear' => true
                             ],
             ]);?>
            <?php 
                    $ordenamiento = [
                    'id_presentacion DESC' => 'Codigo',
                    'id_producto ASC' => 'Producto',
                    'id_grupo DESC' => 'Grupo',
                    'descripcion ASC' => 'Presentacion',
                    'id_medida_producto ASC' => 'Medida',
                    // Agrega aquí cualquier otro criterio de ordenamiento que necesites
                ]; ?>
        <?= $formulario->field($form, 'orden')->dropDownList($ordenamiento,['prompt' => 'Seleccione'] ); ?>

        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("presentacion-producto/index") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                     <th scope="col" style='background-color:#B9D5CE;'>Presentacion producto</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Nombre producto</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Nombre grupo</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Medida</th>
                      <th scope="col" style='background-color:#B9D5CE;'>Tipo de venta</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Total items</th>
                     <th scope="col" style='background-color:#B9D5CE;'></th>  
                     <th scope="col" style='background-color:#B9D5CE;'></th> 
                     <th scope="col" style='background-color:#B9D5CE;'></th>

                 </tr>
            </thead>
            <tbody>
            <?php 
            foreach ($model as $val): ?>
                    <tr style="font-size: 85%;">                   
                        <td><?= $val->descripcion ?></td>
                        <td><?= $val->producto->nombre_producto ?></td>
                        <td><?= $val->grupo->nombre_grupo ?></td>
                        <td><?= $val->medidaProducto->descripcion ?></td>
                        <?php if($val->tipo_venta == 0){?>
                            <td><?= $val->tipoVenta ?></td>
                            <td style="text-align: right"><?= $val->total_item ?></td>
                        <?php }else {?>
                            <td style="background-color: #e8f4d4"><?= $val->tipoVenta ?></td>
                            <td style="background-color: #e8f4d4; text-align: right""><?= $val->total_item ?></td>
                        <?php }?>    
                      
                        <td style= 'width: 10px; right: 10px;'>
                            <a href="<?= Url::toRoute(["presentacion-producto/view", "id" => $val->id_presentacion]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                        </td>
                        <td style= 'width: 20px; right: 20px;'>
                            <a href="<?= Url::toRoute(["presentacion-producto/update", "id" => $val->id_presentacion]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>
                        </td>
                        <td style= 'width: 20px; right: 20px'>
                            <?= Html::a('', ['eliminar_linea', 'id' => $val->id_presentacion], [
                                'class' => 'glyphicon glyphicon-trash',
                                'data' => [
                                    'confirm' => 'Esta seguro de eliminar el registro?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </td>
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
                <a align="right" href="<?= Url::toRoute("presentacion-producto/create") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>   
           
              <?php $form->end() ?>
            
        </div>
</div>    
<?= LinkPager::widget(['pagination' => $pagination]) ?>


