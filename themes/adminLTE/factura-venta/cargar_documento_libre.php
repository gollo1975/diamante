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
$documentos = ArrayHelper::map(app\models\TipoFacturaVenta::find()->where(['=','documento_libre', 1])->orderBy('descripcion ASC')->all(), 'id_tipo_factura', 'descripcion');
$form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
            'template' => '{label}<div class="col-sm-8 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-3 control-label'],
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
                   Cargar documento libre.
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?= $form->field($model, 'tipo_documento')->dropdownList($documentos, ['prompt' => 'Seleccione una opcion...','required' => true]) ?>		
                    </div>
                   
                       
                </div>  
                    <div class="panel-footer text-right">
                       <?= Html::submitButton("<span class='glyphicon glyphicon-send'></span> Enviar documento", ["class" => "btn btn-primary", 'name' => 'enviar_documento']) ?>                    
                   </div>
                
            </div>
           
        </div>
    </div>
<?php $form->end() ?> 

