<?php
//modelos

//clases
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
?>
<?php
 $form = ActiveForm::begin([

    'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
    'fieldConfig' => [
        'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
        'labelOptions' => ['class' => 'col-sm-3 control-label'],
        'options' => []
    ],
]); ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"></h4>
</div>
<div class="modal-body">        
    <div class="table table-responsive">
        <div class="panel panel-success ">
            <div class="panel-heading" style="text-align: left ">
               Crear orden de ensamble
            </div>
            
    </div>
 
<div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#listadoentrega" aria-controls="listadoentrega" role="tab" data-toggle="tab">Listado de entrega <span class="badge"><?= count($solicitud) ?></span></a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane  active" id="listadosolicitud">
            <div class="table-responsive">
                <div class="panel panel-success">
                    <div class="panel-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr style="font-size: 85%;">
                                    
                                    <th scope="col"  style='background-color:#B9D5CE;'><b>Presentacion del producto</b></th>                        
                                    <th scope="col"  style='background-color:#B9D5CE;'>Total productos</th> 
                                    <th scope="col"  style='background-color:#B9D5CE;'><b>Total kits</b></th>
                                    <th scope="col" style='background-color:#B9D5CE;'></th>
                                </tr>
                            </thead>
                            <body>
                                <?php if (!empty($solicitud)): ?>
                                
                                <?php foreach ($solicitud as $val):?>
                                    <tr style="font-size: 85%;">
                                       
                                        <td style='text-align: left'><?= $val->presentacion->descripcion ?></td>
                                        <td style='text-align: right'><?= ''.number_format($val->total_unidades_entregadas,0) ?></td>
                                        <td style='text-align: right'><?= ''.number_format($val->cantidad_despachada,0) ?></td>
                                        <td style= 'width: 20px; height: 20px;'><input type="radio" name="nueva_solicitud[]" value="<?= $val->id_entrega_kits ?>"></td> 
                                    </tr>
                                 <?php endforeach;  ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" style="background-color: #4bb1cf">No hay solicitudes de kits disponibles para importar.</td>
                                    </tr>
                                <?php endif; ?>    
                            </body>
                        </table>
                    </div>
                    <?php if(count($solicitud)> 0){?>
                        <div class="panel-footer text-right">  
                          <?= Html::submitButton("<span class='glyphicon glyphicon-send'></span> Enviar documento", ["class" => "btn btn-primary", 'name' => 'enviar_documento']) ?>                    
                       </div>
                    <?php }?>
                </div>
            </div>
        </div>    
    </div>  
</div>
</div>
 <?php ActiveForm::end(); ?>
</div>
