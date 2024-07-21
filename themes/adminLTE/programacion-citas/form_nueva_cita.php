<?php
//modelos
use app\models\Clientes;
use app\models\TipoVisitaComercial;
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
            'template' => '{label}<div class="col-sm-9 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-3 control-label'],
            'options' => []
        ],
        ]);
$cliente = ArrayHelper::map(Clientes::find()->where(['=','estado_cliente', 0])->andWhere(['>','cupo_asignado', 0])->andWhere(['=','id_agente', $agenteToken])->orderBy('nombre_completo ASC')->all(), 'id_cliente', 'nombre_completo');
$tipovisita = ArrayHelper::map(TipoVisitaComercial::find()->orderBy('nombre_visita ASC')->all(), 'id_tipo_visita', 'nombre_visita');
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body">        
        <div class="table table-responsive">
            <div class="panel panel-success ">
                <div class="panel-heading" style="text-align: left ">
                  NUEVA CITA CLIENTE    
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?= $form->field($model, 'cliente')->dropDownList($cliente, ['prompt' => 'Seleccione...']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'tipo_visita')->dropdownList($tipovisita, ['prompt' => 'Seleccione...']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'hora_visita')->input ('time'); ?>
                    </div>
                    <div class="row">
                    <?= $form->field($model, 'fecha_cita')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                            'value' => date('d-M-Y', strtotime('+2 days')),
                            'options' => ['placeholder' => 'Seleccione una fecha ...'],
                            'pluginOptions' => [
                                'format' => 'yyyy-m-d',
                                'todayHighlight' => true]])
                        ?>
                    </div>    
                    <div class="row">
                        <?= $form->field($model, 'nota')->textArea(['maxlength' => true, 'size' => 40]) ?>
                    </div
                </div>  
                    <div class="panel-footer text-right">
                       <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar", ["class" => "btn btn-primary", 'name' => 'nueva_cita_cliente']) ?>                    
                   </div>
                
            </div>
           
        </div>
    </div>
<?php $form->end() ?> 

