<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;


$this->title = 'PROVEEDORES';
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
    "action" => Url::toRoute("proveedor/index"),
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
            <a align="right" href="<?= Url::toRoute("proveedor/index") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <th scope="col" style='background-color:#B9D5CE;'>Proveedor</th>
                <th scope="col" style='background-color:#B9D5CE;'>Dirección</th>
                 <th scope="col" style='background-color:#B9D5CE;'>Teléfono</th>
                 <th scope="col" style='background-color:#B9D5CE;'>Celular</th>
                 <th scope="col" style='background-color:#B9D5CE;'>Email</th>
                <th scope="col" style='background-color:#B9D5CE;'>Departamento</th>
                <th scope="col" style='background-color:#B9D5CE;'>Municipio</th>
                <th scope="col" style='background-color:#B9D5CE;'><span title="Requisitos validados">Val.</span></th>
                <th scope="col" style='background-color:#B9D5CE;'></th>  
                <th scope="col" style='background-color:#B9D5CE;'></th>  
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr style="font-size: 90%;">                   
                 <td><?= $val->tipoDocumento->tipo_documento ?></td>
                <td><?= $val->nit_cedula ?></td>
                <td><?= $val->nombre_completo ?></td>
                <td><?= $val->direccion ?></td>
                <td><?= $val->telefono ?></td>
                <td><?= $val->celular ?></td>
                 <td><?= $val->email ?></td>
                <td><?= $val->codigoDepartamento->departamento ?></td>
                <td><?= $val->codigoMunicipio->municipio ?></td>
                <td><?= $val->requisitoValidado ?></td>
                <?php if($val->email == null || $val->direccion == null || $val->codigo_banco == null){?>
                    <td style= 'width: 25px; height: 20px;'>
                        <a href="<?= Url::toRoute(["proveedor/update", "id" => $val->id_proveedor,'msg' => 0])?>" ><span class="glyphicon glyphicon-pencil"></span></a>
                    </td>
                    <td style= 'width: 25px; height: 20px;'></td>
                <?php }else{ ?>
                    <td style= 'width: 25px; height: 20px;'>
                            <a href="<?= Url::toRoute(["proveedor/view", "id" => $val->id_proveedor, 'token' => $token]) ?>" ><span class="glyphicon glyphicon-eye-open"></span></a>
                    </td>    
                    <td style= 'width: 25px; height: 20px;'>
                        <a href="<?= Url::toRoute(["proveedor/update", "id" => $val->id_proveedor,'msg' => 0])?>" ><span class="glyphicon glyphicon-pencil"></span></a>
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
            <?= Html::a('<span class="glyphicon glyphicon-import"></span> Importar proveedor',
                    ['/proveedor/validar_requisitos'],
                    [
                        'title' => 'Permite importa el proveedor validado',
                        'data-toggle'=>'modal',
                        'data-target'=>'#modalvalidarrequisitos',
                        'class' => 'btn btn-success btn-sm'
                    ])    
                    ?>
             <div class="modal remote fade" id="modalvalidarrequisitos">
                     <div class="modal-dialog modal-lg-centered">
                         <div class="modal-content"></div>
                     </div>
             </div>
            <a align="right" href="<?= Url::toRoute("proveedor/create") ?>" class="btn btn-success btn-sm"><span class='glyphicon glyphicon-plus'></span> Nuevo</a>
              <?php $form->end() ?>
            
        </div>
    </div>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>