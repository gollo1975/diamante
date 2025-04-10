<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\TipoPagoCredito;
use app\models\ConfiguracionCredito;
use app\models\Empleados;
use kartik\select2\Select2;
use kartik\date\DatePicker;


?>
<?php
$form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => false,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
            'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
            'options' => []
        ],
        ]);
$conEmpleado = ArrayHelper::map(app\models\Empleados::find()->orderBy ('nombre_completo ASC')->all(), 'id_empleado', 'nombre_completo');
$TipoDotacion = ArrayHelper::map(app\models\TipoDotacion::find()->orderBy('id_tipo_dotacion ASC')->all(), 'id_tipo_dotacion', 'descripcion');
?>
        <div class="panel panel-success">
            <div class="panel-heading">
                Información de la entrega
            </div>
            <div class="panel-body">
                <div class="row">

                     <?= $form->field($model, 'id_empleado')->widget(Select2::classname(), [
                    'data' => $conEmpleado,
                    'options' => ['placeholder' => 'Seleccione...'],
                    'pluginOptions' => [
                        'allowClear' => true ]]);
                    ?>
                    <?= $form->field($model, 'id_tipo_dotacion')->widget(Select2::classname(), [
                    'data' => $TipoDotacion,
                    'options' => ['placeholder' => 'Seleccione...'],
                    'pluginOptions' => [
                        'allowClear' => true ]]);
                    ?>
                </div>   
                <div class="row">      
                        <?=  $form->field($model, 'fecha_entrega')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                           'value' => date('Y-m-d', strtotime('+2 days')),
                           'options' => ['placeholder' => 'Seleccione una fecha ...'],
                           'pluginOptions' => [
                               'format' => 'yyyy-m-d',
                               'todayHighlight' => true]])
                    ?>
                     <?= $form->field($model, 'tipo_proceso')->dropDownList(['0' => 'SALIDA', '1' => 'DEVOLUCION'],['prompt' => 'Seleccione'] ); ?>
                </div>
                <div class="row">      
                   <?= $form->field($model, 'observacion', ['template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?>
               
                </div>
                 
                <div class="panel-footer text-right">			
                    <a href="<?= Url::toRoute("entrega-dotacion/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
                </div>
            </div>
        </div>
<?php $form->end() ?> 