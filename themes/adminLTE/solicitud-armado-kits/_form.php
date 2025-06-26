<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\models\Ordenproduccion */
/* @var $form yii\widgets\ActiveForm */
$conSolicitud = ArrayHelper::map(app\models\DocumentoSolicitudes::find()->orderBy ('concepto ASC')->all(), 'id_solicitud', 'concepto');
$conPresentacion = ArrayHelper::map(app\models\PresentacionProducto::find()->where(['=','tipo_venta', 1])->orderBy ('descripcion ASC')->all(), 'id_presentacion', 'descripcion');
?>
 <?php $form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => false,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
            'template' => '{label}<div class="col-sm-6 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-3 control-label'],
            'options' => []
           ],
        ]);
?>
<div class="panel panel-success">
    <div class="panel-heading">
        Detalle del registro
    </div>
    <div class="panel-body">
        <div class="row">
            <?= $form->field($model, 'id_solicitud')->widget(Select2::classname(), [
                    'data' => $conSolicitud,
                    'options' => ['placeholder' => 'Seleccione...'],
                    'pluginOptions' => [
                        'allowClear' => true ]]);
            ?>
        </div>
        <div class="row">
           <?= $form->field($model, 'fecha_solicitud')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
        </div> 
        <div class="row">
            <?= $form->field($model, 'id_presentacion')->widget(Select2::classname(), [
                    'data' => $conPresentacion,
                    'options' => ['placeholder' => 'Seleccione...'],
                    'pluginOptions' => [
                        'allowClear' => true ]]);
            ?>
        </div>
        <div class="row">
              <?= $form->field($model, 'cantidad_solicitada')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="row">
            <?= $form->field($model, 'observacion', ['template' => '{label}<div class="col-sm-6 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?>
        </div>
    </div>
    <div class="panel-footer text-right">			
         <a href="<?= Url::toRoute("solicitud-armado-kits/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>		
    </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

