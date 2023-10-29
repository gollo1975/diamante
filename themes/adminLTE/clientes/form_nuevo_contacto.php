<?php
//modelos
use app\models\Cargos;
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
$cargo = ArrayHelper::map(Cargos::find()->orderBy('nombre_cargo ASC' )->all(), 'id_cargo', 'nombre_cargo');
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body">        
        <div class="table table-responsive">
            <div class="panel panel-success ">
                <div class="panel-heading" style="text-align: left ">
                  NUEVO CONTACTO  
                </div>
                <div class="panel-body">
                    <div class="row">
                         <?= $form->field($model, 'nombres')->input("text") ?>
                    </div>
                    <div class="row">
                         <?= $form->field($model, 'apellidos')->input("text") ?>
                    </div>
                    <div class="row">
                         <?= $form->field($model, 'celular')->input("text") ?>
                    </div>
                    <div class="row">
                         <?= $form->field($model, 'email')->input("text") ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'cargo')->dropDownList($cargo, ['prompt' => 'Seleccione...', 'required' => 'true']) ?>
                    </div>
                    <div class="row">
                           <?= $form->field($model, 'fecha_nacimiento')->widget(DatePicker::className(), ['name' => 'check_issue_date', 
                               'value' => date('d-M-Y', strtotime('+2 days')),
                               'options' => ['placeholder' => 'Seleccione una fecha ...', 'required' => 'true'], 
                               'pluginOptions' => [
                                   'format' => 'yyyy-m-d',
                                   'todayHighlight' => true,
                                   'required' => true]])
                           ?>
                    </div>
                </div>  
                    <div class="panel-footer text-right">
                       <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar", ["class" => "btn btn-primary", 'name' => 'nuevo_contacto_cliente']) ?>                    
                   </div>
                
            </div>
           
        </div>
    </div>
<?php $form->end() ?> 

