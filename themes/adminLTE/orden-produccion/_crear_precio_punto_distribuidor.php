<?php
//modelos
use app\models\TipoVenta;
//clases
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
$tipoventa = ArrayHelper::map(TipoVenta::find()->where(['=','abreviatura', 'PV'])->orderBy('concepto ASC')->all(), 'id_tipo_venta', 'concepto');
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
                  PRECIOS PARA PUNTO Y DISTRIBUCION
                </div>
                <div class="panel-body">
                    <div class="row">
                          <?= $form->field($model, 'tipo_precio')->dropDownList($tipoventa, ['prompt' => 'Seleccione...']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'nuevo_precio')->input('text'); ?>
                    </div>
                </div>  
                    <div class="panel-footer text-right">
                       <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-primary", 'name' => 'crear_precio_unico']) ?>                    
                   </div>
                
            </div>
           
        </div>
    </div>
<?php $form->end() ?> 

