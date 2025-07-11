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
                  Observaciones de la entrega  
                </div>
                <div class="panel-body">
                    <div class="row">
                      
                        <?= $form->field($model, 'observacion', ['template' => '{label}<div class="col-sm-9 form-group">{input}{error}</div>'])->textarea(['rows' => 3, 'maxlength' => 100]) ?>
                       
                    </div>
                </div>  
                    <div class="panel-footer text-right">
                       <?= Html::submitButton("<span class='glyphicon glyphicon-send'></span> Enviar", ["class" => "btn btn-primary", 'name' => 'enviar_nota']) ?>                    
                   </div>
                
            </div>
           
        </div>
    </div>
 <?php ActiveForm::end(); ?>

