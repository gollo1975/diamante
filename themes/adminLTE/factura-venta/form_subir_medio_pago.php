<?php
//modelos
use app\models\DocumentoAlmacenamiento;
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
$medioPago = ArrayHelper::map(\app\models\MedioPago::find()->all(), 'id_medio_pago', 'concepto');
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body">        
        <div class="table table-responsive">
            <div class="panel panel-success ">
                <div class="panel-heading" style="text-align: left ">
                  Medio de pago factura   
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?= $form->field($model, 'medio_pago')->dropDownList($medioPago, ['prompt' => 'Seleccione...','required' => true]) ?>
                    </div>
                    <div class="row">
                       <?= $form->field($model, 'observacion', ['template' => '{label}<div class="col-sm-8 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?>
                    </div>
                </div>  
                    <div class="panel-footer text-right">
                       <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar", ["class" => "btn btn-primary", 'name' => 'medio_pago_factura']) ?>                    
                   </div>
                
            </div>
           
        </div>
    </div>
<?php $form->end() ?> 

