<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Producto */
$this->title = 'Tiempo extra';
$this->params['breadcrumbs'][] = ['label' => 'Novedades de nomina', 'url' => ['index']];
$this->params['breadcrumbs'][] = $id;
?>

<div class="programacion-nomina-view">
   
 <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php $form = ActiveForm::begin([
    'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
    'fieldConfig' => [
        'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
        'labelOptions' => ['class' => 'col-sm-3 control-label'],
        'options' => []
    ],
    ]);
    $contador = count($detalle); 
    ?>
    <div class="panel panel-success">
        <div class="panel-heading">
            Informacion periodo de pago
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_grupo_pago') ?></th>
                   <td><?= Html::encode($model->grupoPago->grupo_pago) ?></td>  
                 
                   <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Desde') ?>:</th>
                   <td><?= Html::encode($model->fecha_desde) ?></td>  
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Hasta') ?>:</th>
                   <td><?= Html::encode($model->fecha_hasta) ?></td> 
                   <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'dias_pago') ?>:</th>
                   <td><?= Html::encode($model->dias_pago) ?></td> 
                </tr>               
            </table>
        </div>
    </div>
    <div class="table-responsive">
        <div class="panel panel-success ">
            <div class="panel-heading">
                Listado de empleados
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr style='font-size:85%;'>
                        <th scope="col" style='background-color:#B9D5CE;'>Documento</th>                        
                        <th scope="col" style='background-color:#B9D5CE;'>Empleado</th>                        
                        <th scope="col" style='background-color:#B9D5CE;'>Desde</th>                        
                        <th scope="col" style='background-color:#B9D5CE;'>Hasta</th>                        
                        <th scope="col" style='background-color:#B9D5CE;'>Inicio Contrato</th> 
                        <th scope="col" style='background-color:#B9D5CE;'>Contrato</th> 
                        <th scope="col" style='background-color:#B9D5CE;'>Tipo_salario</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Salario</th>
                        <th scope="col" style='background-color:#B9D5CE;'></th>
                        <th scope="col" style='background-color:#B9D5CE;'></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($detalle as $val): 
                        if(\app\models\Contratos::find()->where(['=','id_contrato', $val->id_contrato])->andWhere(['=','id_tipo_salario', 2])->one()){?>
                           <tr style='font-size:85%;'>
                                <td><?= $val->cedula_empleado ?></td>
                                <td><?= $val->empleado->nombre_completo ?></td>
                                <td><?= $val->fecha_desde ?></td>
                                <td><?= $val->fecha_hasta ?></td>
                                <td><?= $val->fecha_inicio_contrato ?></td>
                                <td><?= $val->id_contrato ?></td>
                                <td><?= $val->contrato->tipoSalario->descripcion ?></td>
                                <td><?= '$'.number_format($val->salario_contrato,0) ?></td>
                                 <td style="width: 0.5%; height: 0.5%; ">  
                                    <?php
                                    if($val->contrato->tipoSalario->descripcion == 'VARIABLE'){?>
                                            <?= Html::a('<span class="glyphicon glyphicon-list"></span>',            
                                            ['/novedad-tiempo-extra/creartiempoextra','id' => $val->id_periodo_pago_nomina, 'id_programacion'=>$val->id_programacion, 'tipo_salario' => $val->contrato->tipoSalario->descripcion],
                                                [
                                                    'title' => 'Crear novedades',
                                                    'data-toggle'=>'modal',
                                                    'data-target'=>'#modalcreartiempoextra'.$val->id_periodo_pago_nomina,
                                                    'class' => ''
                                                ]
                                            );
                                            ?>
                                             <div class="modal remote fade" id="modalcreartiempoextra<?= $val->id_periodo_pago_nomina ?>">
                                                 <div class="modal-dialog modal-lg" style ="width: 900px;">
                                                    <div class="modal-content"></div>
                                                </div>
                                             </div>
                                    <?php }?>    
                                </td>
                               <td style="width: 0.5%; height: 0.5%; ">  
                                     <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>',            
                                     ['/novedad-tiempo-extra/editartiempoextra','id_empleado' => $val->id_empleado, 'id' => $val->id_periodo_pago_nomina],
                                         [
                                             'title' => 'Editar novedades',
                                             'data-toggle'=>'modal',
                                             'data-target'=>'#modaleditartiempoextra'.$val->id_empleado,
                                             'class' => ''
                                         ]
                                     );
                                     ?>
                                     <div class="modal remote fade" id="modaleditartiempoextra<?= $val->id_empleado ?>">
                                        <div class="modal-dialog modal-lg" style ="width: 800px;">
                                             <div class="modal-content"></div>
                                         </div>
                                   </div>
                                </td>   
                                
                           </tr>    
                        <?php }
                    endforeach; ?>
                     </tbody>   
                </table>
            </div>            
       
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

