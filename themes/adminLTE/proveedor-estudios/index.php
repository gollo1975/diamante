<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use app\models\ProveedorEstudioDetalles;

$this->title = 'ESTUDIOS(Proveedores)';
$this->params['breadcrumbs'][] = $this->title;

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
    "action" => Url::toRoute("proveedor-estudios/index"),
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
	
    <div class="panel-body" id="filtroproveedor" style="display:none">
        <div class="row" >
            <?= $formulario->field($form, "nitcedula")->input("search") ?>
            <?= $formulario->field($form, "nombre_completo")->input("search") ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("proveedor-estudios/index") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <th scope="col" style='background-color:#B9D5CE;'>Tipo documento</th>
                <th scope="col" style='background-color:#B9D5CE;'>Cedula/Nit</th>
                <th scope="col" style='background-color:#B9D5CE;'>Nombre del proveedor</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total porcentaje</th>
                <th scope="col" style='background-color:#B9D5CE;'>Validado</th>
                <th scope="col" style='background-color:#B9D5CE;'>Aprobado</th>
                 <th scope="col" style='background-color:#B9D5CE;'>Cerrado</th>
                <th scope="col" style='background-color:#B9D5CE;'></th>  
                <th scope="col" style='background-color:#B9D5CE;'></th>  
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): 
                $registro = ProveedorEstudioDetalles::find()->where(['=','id_estudio', $val->id_estudio])->all();
                ?>
            <tr style="font-size: 90%;">                   
                 <td><?= $val->tipoDocumento->tipo_documento ?></td>
                <td><?= $val->nit_cedula ?></td>
                <td><?= $val->nombre_completo ?></td>
                <td style="text-align: right"><?= $val->total_porcentaje ?></td>
                <td><?= $val->validadoEstudio ?></td>
                <td><?= $val->aprobadoEstudio ?></td>
                <td><?= $val->procesoCerrado ?>
                <td style= 'width: 25px; height: 20px;'>
                    <a href="<?= Url::toRoute(["proveedor-estudios/view", "id" => $val->id_estudio, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                </td>
                <?php if($registro){?>
                    <td style= 'width: 25px; height: 20px;'></td>
                <?php }else{?>    
                    <td style= 'width: 25px; height: 20px;'>
                        <a href="<?= Url::toRoute(["proveedor-estudios/update", "id" => $val->id_estudio]) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>
                    </td>
                <?php }?>    
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
            <a align="right" href="<?= Url::toRoute("proveedor-estudios/create") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>
             <?php $form->end() ?>
            
        </div>
    </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>