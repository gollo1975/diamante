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
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
            'template' => '{label}<div class="col-sm-8 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-4 control-label'],
            'options' => []
        ],
        ]);
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body">        
        <div class="table table-responsive">
            <div class="panel panel-success ">
                <div class="panel-heading" style="text-align: left ">
                  LISTAR UNIDADES PARA ENTREGA   
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?= $form->field($model, 'cantidad_vendida')->textInput(['disabled' => true]) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'cantidad_despachada')->textInput(['required' => 'true']) ?>
                    </div>
                </div>  
                <div class="panel panel-success">
                    <div class="panel-heading">
                        LUGARES DE ALMACENAMIENTO
                    </div>
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Piso</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Rack</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Posicion</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Lote</th>
                                             <th scope="col" style='background-color:#B9D5CE;'>F. proceso</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                                            <th scope="col" style='background-color:#B9D5CE;'></th>
                                        </tr>
                                    </thead>
                                    <?php
                                       $almacenamiento = \app\models\AlmacenamientoProductoDetalles::find()
                                                                                       ->where(['=','id_inventario', $detalle->inventario->id_inventario])
                                                                                       ->andWhere(['>','cantidad', 0])->orderBy('fecha_almacenamiento ASC')->all();
                                        foreach ($almacenamiento as $val): ?>
                                            <tr style="font-size: 90%;">
                                                <td> <?= $val->codigo_producto ?></td>
                                                <td> <?= $val->producto ?></td>
                                                <td> <?= $val->piso->descripcion ?></td>
                                                <td> <?= $val->rack->descripcion ?></td>
                                                <td> <?= $val->posicion->posicion ?></td>
                                                <td> <?= $val->numero_lote ?></td>
                                                <td> <?= $val->fecha_almacenamiento ?></td>
                                                <td style="text-align: right"> <?= ''.number_format($val->cantidad,0) ?></td>
                                                <td style= 'width: 20px; height: 20px;'><input type="checkbox" name="seleccione[]" value="<?= $val->id ?>"></td> 
                                            </tr>
                                        <?php endforeach; ?>
                                </table>  
                                 <div class="panel-footer text-right">
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar", ["class" => "btn btn-primary", 'name' => 'unidades_despachadas']) ?>                    
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>  
            </div>
           
        </div>
    </div>
<?php $form->end() ?> 

