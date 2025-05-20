<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\ConceptoSalarios;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
?>


<?php $form = ActiveForm::begin([
            "method" => "post",
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-3 control-label'],
                'options' => []
            ],
        ]); ?>
    <?php
$contador = count($concepto_salario);
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body">        
        <div class="table table-responsive">
            <div class="panel panel-success ">
                <div class="panel-heading">
                    Conceptos de tiempo extra <span class="badge"><?= $contador ?></span>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-hover">  
                        <thead>
                            <tr>                
                                <th scope="col">Código</th>
                                <th scope="col">Concepto de salario</th>
                                <th scope="col">% de pago</th>
                                <th scope="col">Nro_Horas</th>

                                                       
                            </tr>
                         </thead>
                        <tbody>
                                <?php foreach ($concepto_salario as $val): ?>
                                  <tr>
                                        <td><input type="hidden" name="codigo_salario[]" value="<?= $val->codigo_salario ?>" readonly="readonly"><?= $val->codigo_salario ?></td>
                                        <td><input type="hidden" name="concepto[]" value="<?= $val->nombre_concepto ?>" readonly="readonly"><?= $val->nombre_concepto ?></td>
                                        <td><input type="hidden" name="porcentaje[]" value="<?= $val->porcentaje_tiempo_extra ?>" readonly="readonly"><?= $val->porcentaje_tiempo_extra?>%</td>
                                        <td align ="right"><input type="text" style="text-align:right" name="horas[]" value="0" size="4" required></td>
                                  </tr>

                              <?php endforeach; ?>   
                        </tbody>   
                    </table>    
                        
                </div>                
                <div class="panel-footer text-right">			
                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span>  Crear", ["class" => "btn btn-primary", 'name' => 'crearnovedad']) ?>                    
                </div>
            </div>
        </div>
    </div>
<?php $form->end() ?> 