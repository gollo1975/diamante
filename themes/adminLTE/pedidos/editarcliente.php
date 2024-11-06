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
if($tokenAcceso == 3){
    $cliente = ArrayHelper::map(app\models\Clientes::find()->where(['=','estado_cliente', 0])
                                                ->andWhere(['=','id_agente', $agente_comercial])
                                                ->orderBy ('nombre_completo ASC')->all(), 'id_cliente', 'nombre_completo');
}    
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body">        
        <div class="table table-responsive">
            <div class="panel panel-success ">
                <div class="panel-heading" style="text-align: left ">
                   EDITAR REGISTROS DEL PEDIDO
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?= $form->field($model, 'cliente')->dropDownList($cliente, ['prompt' => 'Seleccione...']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'pedido_virtual')->dropdownList(['0' => 'NO', '1' => 'SI'], ['prompt' => 'Seleccione...']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'tipo_pedido')->dropdownList(['0' => 'COMERCIAL', '1' => 'REQUERIMIENTO', '2' => 'ACONDICIONADO'], ['prompt' => 'Seleccione...']) ?>
                    </div>
                </div>  
                    <div class="panel-footer text-right">
                       <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-primary", 'name' => 'editarcliente']) ?>                    
                   </div>
                
            </div>
           
        </div>
    </div>
<?php $form->end() ?> 

