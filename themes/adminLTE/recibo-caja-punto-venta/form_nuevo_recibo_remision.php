<?php
//modelos
use app\models\TipoReciboCaja;
use app\models\EntidadBancarias;
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
$tiporecibo = ArrayHelper::map(TipoReciboCaja::find()->all(), 'id_tipo', 'concepto');
$conFormaPago = ArrayHelper::map(app\models\FormaPago::find()->orderBy('concepto ASC')->all(), 'id_forma_pago', 'concepto');
$entidad = ArrayHelper::map(EntidadBancarias::find()->where(['=','convenio_proveedor',  1])->orderBy('entidad_bancaria ASC')->all(), 'codigo_banco', 'entidad_bancaria');
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body">        
        <div class="table table-responsive">
            <div class="panel panel-success ">
                <div class="panel-heading" style="text-align: left ">
                 RECIBO DE PAGO (REMISIONES)   
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?= $form->field($model, 'banco')->dropDownList($entidad, ['prompt' => 'Seleccione...', 'required' => 'true']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'tipo_recibo')->dropdownList($tiporecibo, ['prompt' => 'Seleccione...', 'required' => 'true']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'forma_pago')->dropdownList($conFormaPago, ['prompt' => 'Seleccione...', 'required' => 'true']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'numero_transacion')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'valor_pago')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="panel-footer text-right">
                       <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar", ["class" => "btn btn-primary", 'name' => 'nuevo_recibo_pago_remision']) ?>                    
                   </div>
                </div>  
            </div>
           
        </div>
    </div>
<?php $form->end() ?> 

