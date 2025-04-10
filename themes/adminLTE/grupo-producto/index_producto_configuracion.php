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

if($sw == 0){
    $this->title = 'PRODUCTOS (CONFIGURACION DE FORMULA)';
    $this->params['breadcrumbs'][] = $this->title;
}else{
   $this->title = 'PRODUCTOS (CONFIGURACION DE AUDITORIA)';
    $this->params['breadcrumbs'][] = $this->title; 
}    


?>
<script language="JavaScript">
    function mostrarfiltro() {
        divC = document.getElementById("filtroproveedor");
        if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
    }
</script>

<!--<h1>Lista proveedor</h1>-->
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute(["grupo-producto/index_producto_configuracion",'sw' => $sw]),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
$grupo = ArrayHelper::map(\app\models\GrupoProducto::find()->orderBy ('nombre_grupo ASC')->all(), 'id_grupo', 'nombre_grupo');

?>

<div class="panel panel-success panel-filters">
    <div class="panel-heading" onclick="mostrarfiltro()">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtroproveedor" style="display:block">
        <div class="row" >
            <?= $formulario->field($form, "nombre")->input("search") ?>
             <?= $formulario->field($form, 'grupo')->widget(Select2::classname(), [
                'data' => $grupo,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute(["grupo-producto/index_producto_configuracion",'sw' => $sw]) ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
           <tr style="font-size: 90%;">    
                <th scope="col" style='background-color:#B9D5CE;'>Nombre del producto</th>
                <th scope="col" style='background-color:#B9D5CE;'>Grupo</th>
                <th scope="col" style='background-color:#B9D5CE;'>Marca</th>
                <th scope="col" style='background-color:#B9D5CE;'>User name</th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr style="font-size: 90%;">                   
                <td><?= $val->nombre_producto ?></td>
                <td><?= $val->grupo->nombre_grupo ?></td>
                <td><?= $val->marca->marca ?></td>
                <td><?= $val->user_name ?></td>
                <?php
                if($sw == 0){?>
                    <td style= 'width: 25px; height: 20px;'>
                        <a href="<?= Url::toRoute(["grupo-producto/buscarmateriaprima", "id_grupo" => $val->id_grupo, 'sw' => $sw, 'id_producto' => $val->id_producto]) ?>" ><span class="glyphicon glyphicon-list" title="Permite cargar las materias primas al producto"></span></a>
                    </td> 
                    <?php if(\app\models\ConfiguracionProducto::find()->where(['=','id_grupo', $val->id_grupo])->one()){?>
                        <td style= 'width: 25px; height: 20px;'>
                            <a href="<?= Url::toRoute(["grupo-producto/view_configuracion", "id_producto" => $val->id_producto,  'sw' => $sw]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite ver la vista de las materias primas"></span></a>
                        </td> 
                    <?php }else{?>
                        <td style= 'width: 25px; height: 20px;'>

                        </td>
                    <?php } 
                }else{?>
                    <td style= 'width: 25px; height: 20px;'>
                        <a href="<?= Url::toRoute(["grupo-producto/buscar_concepto_analisis", "id_grupo" => $val->id_grupo, 'sw' => $sw, 'id_producto' => $val->id_producto]) ?>" ><span class="glyphicon glyphicon-level-up" title="Permite cargar los analisis para la auditoria del producto"></span></a>
                    </td>
                    <?php if(\app\models\ConfiguracionProductoProceso::find()->where(['=','id_producto', $val->id_producto])->one()){?>
                        <td style= 'width: 25px; height: 20px;'>
                            <a href="<?= Url::toRoute(["grupo-producto/view_analisis", "id_grupo" => $val->id_grupo,  'sw' => $sw, 'id_producto' => $val->id_producto]) ?>" ><span class="glyphicon glyphicon-eye-open" title="Permite ver la vista de los conceptos agregados"></span></a>
                        </td> 
                    <?php }else{?>
                        <td style= 'width: 25px; height: 20px;'>

                        </td>
                    <?php }   
                }?>    
                        
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
            <?php $form->end() ?>
            
        </div>
    </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>