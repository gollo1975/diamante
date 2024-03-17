<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;


$this->title = 'APROBAR(Proveedores)';
$this->params['breadcrumbs'][] = $this->title;


?>
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
           <tr style="font-size: 90%;">    
                <th scope="col" style='background-color:#B9D5CE;'>Tipo documento</th>
                <th scope="col" style='background-color:#B9D5CE;'>Cedula/Nit</th>
                <th scope="col" style='background-color:#B9D5CE;'>Nombre del proveedor</th>
                <th scope="col" style='background-color:#B9D5CE;'>Total porcentaje</th>
                <th scope="col" style='background-color:#B9D5CE;'>Validado</th>
                <th scope="col" style='background-color:#B9D5CE;'>Aprobado</th>
               <th scope="col" style='background-color:#B9D5CE;'>Proceso cerrado</th>
               <th scope="col" style='background-color:#B9D5CE;'></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr style="font-size: 90%;">                   
                 <td><?= $val->tipoDocumento->tipo_documento ?></td>
                <td><?= $val->nit_cedula ?></td>
                <td><?= $val->nombre_completo ?></td>
                <td style="text-align: right"><?= $val->total_porcentaje ?></td>
                <td><?= $val->validadoEstudio ?></td>
                <td><?= $val->aprobadoEstudio ?></td>
                <td><?= $val->procesoCerrado ?></td>
                <td style= 'width: 20px; height: 20px;'>
                    <?= Html::a('<span class="glyphicon glyphicon-check"></span>',
                       ['/proveedor-estudios/aprobar_proveedor', 'id' => $val->id_estudio],
                         ['title' => 'Permite aprobar el proveedor.',
                          'data-toggle'=>'modal',
                          'data-target'=>'#modalaprobarproveedor',
                         ])    
                   ?>
                   <div class="modal remote fade" id="modalaprobarproveedor">
                        <div class="modal-dialog modal-lg" style ="width: 550px;">    
                            <div class="modal-content"></div>
                        </div>
                   </div>
                </td>       
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>
      
    </div>
     <?php $form->end() ?>
</div>
<?= LinkPager::widget(['pagination' => $pagination]) ?>