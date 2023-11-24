<?php
//modelos
use app\models\Pisos;
//clases
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Url;
?>
<?php
$form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
            'template' => '{label}<div class="col-sm-9 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-3 control-label'],
            'options' => []
        ],
        ]);
$conPosicion = ArrayHelper::map(\app\models\Posiciones::find()->orderBy('posicion ASC')->all(), 'id_posicion', 'posicion');
$conPiso = ArrayHelper::map(Pisos::find()->all(), 'id_piso', 'descripcion');
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body">        
        <div class="table table-responsive">
            <div class="panel panel-success ">
                <div class="panel-heading" style="text-align: left ">
                  PROCESO DE ALMACENAMIENTO    
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?= $form->field($model, 'piso')->dropDownList($conPiso,['prompt'=>'Seleccione ...', 'onchange'=>' $.get( "'.Url::toRoute('almacenamiento-producto/piso_rack').'", { id: $(this).val() } ) .done(function( data ) {
                        $( "#'.Html::getInputId($model, 'rack',['required', 'class' => 'select-2']).'" ).html( data ); });']); ?>
                    </div>    
                    <div class="row">
                        <?= $form->field($model, 'rack')->dropDownList($tipo_rack, ['prompt' => 'Seleccione...']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'posicion')->dropDownList($conPosicion, ['prompt' => 'Seleccione...','required' => 'true']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'cantidad')->input ('text', ['required' => 'true']); ?>
                    </div>
                </div>  
                    <div class="panel-footer text-right">
                       <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar", ["class" => "btn btn-primary", 'name' => 'crear_almacenamiento']) ?>                    
                   </div>
                
            </div>
           
        </div>
    </div>
<?php $form->end() ?> 

