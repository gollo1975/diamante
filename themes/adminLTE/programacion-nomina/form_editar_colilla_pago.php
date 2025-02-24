<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use app\models\Empleados;
use app\models\GrupoPago;
use app\models\ProgramacionNominaDetalle;
use app\models\ConceptoSalarios;
use app\models\PeriodoPagoNomina;
$this->title = 'Colilla de pago';
$this->params['breadcrumbs'][] = $this->title;
$empleado = app\models\Empleados::findOne($model->id_empleado); 
$grupo_pago = GrupoPago::findOne($model->id_grupo_pago); 
$periodo_pago = PeriodoPagoNomina::findOne($id); 
$tipo_pago = app\models\TipoNomina::findOne($periodo_pago->id_tipo_nomina); 
?>
<div class="programacion-nomina-editarcolilla">
   
       <?= Html::a('<span class="glyphicon glyphicon-check"></span> Actualizar colilla', ['/programacion-nomina/actualizar_colilla', 'id_programacion' => $id_programacion, 'id' => $id], ['class' => 'btn btn-success btn-sm']);?>
       
 <!--<h1><?= Html::encode($this->title) ?></h1>-->
   
    <div class="modal-body">
        <div class="panel panel-success">
            <div class="panel-heading">
               Registros del empleado.
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped table-hover" WIDTH="80%">
                    <tr style ='font-size:80%;'>
                        <th><?= Html::activeLabel($model, 'id_programacion') ?></th>
                        <td><?= Html::encode($model->id_programacion) ?></td>
                        <th><?= Html::activeLabel($model, 'nro_pago') ?></th>
                        <td><?= Html::encode($model->nro_pago) ?></td>
                         <th><?= Html::activeLabel($model, 'fecha_desde') ?></th>
                        <td><?= Html::encode($model->fecha_desde) ?></td>
                          <th><?= Html::activeLabel($model, 'fecha_hasta') ?></th>
                        <td><?= Html::encode($model->fecha_hasta) ?></td>
                        <th><?= Html::activeLabel($model, 'salario_contrato') ?></th>
                        <td><?= Html::encode('$'. number_format($model->salario_contrato,0)) ?></td>

                    </tr>   
                    <tr style ='font-size:80%;'>
                        <th><?= Html::activeLabel($model, 'cedula_empleado') ?></th>
                        <td><?= Html::encode($model->cedula_empleado) ?></td>
                        <th><?= Html::activeLabel($model, 'id_empleado') ?></th>
                        <td colspan="3"><?= Html::encode($empleado->nombre_completo) ?></td>
                        <th><?= Html::activeLabel($model, 'id_contrato') ?></th>
                        <td><?= Html::encode($model->id_contrato) ?></td>
                         <th><?= Html::activeLabel($model, 'Promedio') ?></th>
                        <td><?= Html::encode('$'. number_format($model->salario_promedio,0)) ?></td>

                    </tr>   
                    <tr style ='font-size:80%;'>
                          <th><?= Html::activeLabel($model, 'Tipo_pago') ?>:</th>
                        <td><?= Html::encode($tipo_pago->tipo_pago) ?></td>
                        <th><?= Html::activeLabel($model, 'id_grupo_pago') ?></th>
                        <td colspan="3"><?= Html::encode($grupo_pago->grupo_pago) ?></td>
                        <th><?= Html::activeLabel($model, 'dias_pago') ?></th>
                        <td><?= Html::encode($model->dias_pago) ?></td>
                         <th><?= Html::activeLabel($model, 'total_devengado') ?></th>
                        <td><?= Html::encode('$'. number_format($model->total_devengado,0)) ?></td>

                    </tr>   
                     <tr style ='font-size:80%;'>
                        <th><?= Html::activeLabel($model, 'fecha_inicio_contrato') ?></th>
                        <td><?= Html::encode($model->fecha_inicio_contrato) ?></td>
                        <th><?= Html::activeLabel($model, 'id_periodo_pago_nomina') ?></th>
                        <td colspan="3"><?= Html::encode($model->id_periodo_pago_nomina) ?></td>
                        <th><?= Html::activeLabel($model, 'dia_real_pagado') ?></th>
                        <td><?= Html::encode($model->dia_real_pagado) ?></td>
                         <th><?= Html::activeLabel($model, 'total_deduccion') ?></th>
                        <td><?= Html::encode('$'. number_format($model->total_deduccion,0)) ?></td>
                     </tr>   
                     <tr style ='font-size:80%;'>
                         <th><?= Html::activeLabel($model, 'fecha_creacion') ?></th>
                        <td><?= Html::encode($model->fecha_creacion) ?></td>
                        <th><?= Html::activeLabel($model, 'Usuario') ?>:</th>
                        <td  colspan="3"><?= Html::encode($model->user_name) ?></td>
                        <th><?= Html::activeLabel($model, 'dias_Ausentes') ?>:</th>
                        <td><?= Html::encode($model->dias_ausentes) ?></td>
                         <th><?= Html::activeLabel($model, 'total_pagar') ?></th>
                        <td><?= Html::encode('$'. number_format($model->total_pagar,0)) ?></td>

                    </tr>   
                </table>
            </div>
        </div>
    </div>
   <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
        'fieldConfig' => [
            'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-3 control-label'],
            'options' => []
        ],
    ]); ?>
    <div>
        <div class="modal-body">
            <div class="panel panel-success">
                <div class="panel-heading">
                   Detalle del pago.
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr style="font-size: 80%" >
                                <th scope="col" style="background-color:#DCFFFC;">Código</th>  
                                <th scope="col" style="background-color:#DCFFFC;">Concepto</th>
                                <th scope="col" style="background-color:#DCFFFC;">%</th>
                                <th scope="col" style="background-color:#DCFFFC;">Nro_Horas</th>
                                <th scope="col" style="background-color:#DCFFFC;">Vr. Hora</th>                        
                                 <th scope="col" style="background-color:#DCFFFC;">Nro_Dias</th>    
                                 <th scope="col" style="background-color:#DCFFFC;">Vr. Día</th>  
                                 <th scope="col" style="background-color:#DCFFFC;">Deducción</th>   
                                 <th scope="col" style="background-color:#DCFFFC;">Devengado</th> 
                                 <th scope="col" style="background-color:#DCFFFC;"></th> 
                                 <th scope="col" style="background-color:#DCFFFC;"></th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach ($detalle as $key => $val) {
                                $concepto = ConceptoSalarios::find()->where(['=','codigo_salario', $val->codigo_salario])->one();
                                if($concepto->auxilio_transporte == 1){ ?>
                                    <tr style ='font-size:80%;'>
                                        <td><?= $val->codigo_salario ?></td>  
                                        <td ><?= $concepto->nombre_concepto ?></td>
                                        <td ><?= ''. number_format($val->porcentaje,2) ?></td>
                                        <td align="center"><?= $val->horas_periodo_reales ?></td>
                                        <td align="right"><?= ''.number_format($val->vlr_hora, 2) ?></td>
                                        <td align="center"><?= $val->dias_transporte ?></td>
                                        <td align="right"><?= ''.number_format($val->vlr_dia, 2) ?></td>
                                        <td align="right"><?= '$'.number_format($val->vlr_deduccion,0) ?></td>
                                        <td align="right"><?= '$'.number_format($val->auxilio_transporte,0) ?></td>
                                        <td style="width: 20px; height: 20px">
                                            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>',            
                                                    ['programacion-nomina/editar_colilla_pagonomina', 'id' => $id,'id_programacion'=>$val->id_programacion, 'id_detalle' => $val->id_detalle],
                                                        [
                                                            'title' => 'Editar concepto salarial',
                                                            'data-toggle'=>'modal',
                                                            'data-target'=>'#modaleditarcolillapago'.$id_programacion,
                                                            'class' => ''
                                                        ]
                                                    );
                                                ?>
                                                <div class="modal remote fade" id="modaleditarcolillapago<?= $id_programacion ?>">
                                                   <div class="modal-dialog modal-lg" style ="width: 450px;">
                                                       <div class="modal-content"></div>
                                                   </div>
                                                </div>
                                        </td>                                      

                                        <td style="width: 25px;">
                                            <?= Html::a('', ['eliminar_concepto_salario', 'id_detalle' => $val->id_detalle, 'id_programacion' => $id_programacion, 'id' => $id], [
                                                'class' => 'glyphicon glyphicon-trash',
                                                'data' => [
                                                    'confirm' => 'Esta seguro de eliminar el registro?',
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                        </td>
                                   </tr>
                                 <?php
                                 }else{ ?>
                                    <tr style ='font-size:80%;'>
                                        <td><?= $val->codigo_salario ?></td>  
                                        <td ><?= $concepto->nombre_concepto ?></td>
                                        <td ><?= ''. number_format($val->porcentaje,2) ?></td>
                                        <td align="center"><?= $val->horas_periodo_reales ?></td>
                                        <td align="right"><?= ''.number_format($val->vlr_hora, 2) ?></td>
                                        <td align="center"><?= $val->dias_reales ?></td>
                                        <td align="right"><?= ''.number_format($val->vlr_dia, 2) ?></td>
                                        <td align="right"><?= '$'.number_format($val->vlr_deduccion,0) ?></td>
                                        <td align="right"><?= '$'.number_format($val->vlr_devengado,0) ?></td>
                                        <td style="width: 20px; height: 20px">
                                            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>',            
                                                    ['programacion-nomina/editar_colilla_pagonomina', 'id' => $id,'id_programacion'=>$val->id_programacion, 'id_detalle' => $val->id_detalle],
                                                        [
                                                            'title' => 'Editar concepto salarial',
                                                            'data-toggle'=>'modal',
                                                            'data-target'=>'#modaleditarcolillapago'.$id_programacion,
                                                            'class' => ''
                                                        ]
                                                    );
                                                ?>
                                                <div class="modal remote fade" id="modaleditarcolillapago<?= $id_programacion ?>">
                                                   <div class="modal-dialog modal-lg" style ="width: 450px;">
                                                       <div class="modal-content"></div>
                                                   </div>
                                                </div>
                                        </td>                                      

                                        <td style="width: 25px;">
                                            <?= Html::a('', ['eliminar_concepto_salario', 'id_detalle' => $val->id_detalle, 'id_programacion' => $id_programacion, 'id' => $id], [
                                                'class' => 'glyphicon glyphicon-trash',
                                                'data' => [
                                                    'confirm' => 'Esta seguro de eliminar el registro?',
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                        </td>
                                   </tr>
                                   
                                <?php }?>
                                
                           <?php } ?>
                        </tbody>
                    </table>
                </div>    
                
            </div> 
        </div>
    </div>  
</div>  
<?php $form->end() ?>

    


