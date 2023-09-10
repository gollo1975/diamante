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
//model
use app\models\PresupuestoEmpresarial;
$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Presupuesto mensual', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
            'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
            'options' => []
        ],
        ]);
?>

<?php
$presupueso = ArrayHelper::map(PresupuestoEmpresarial::find()->orderBy ('id_presupuesto ASC')->all(), 'id_presupuesto', 'descripcion');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4>PRESUPUESTO MENSUAL</h4>
        
    </div>
    
    <div class="panel-body">
        <div class="row">
            <?=  $form->field($model, 'fecha_inicio')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                           'value' => date('Y-m-d', strtotime('+2 days')),
                           'options' => ['placeholder' => 'Seleccione una fecha ...'],
                           'pluginOptions' => [
                               'format' => 'yyyy-m-d',
                               'todayHighlight' => true]])
            ?>
             <?=  $form->field($model, 'fecha_corte')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                           'value' => date('Y-m-d', strtotime('+2 days')),
                           'options' => ['placeholder' => 'Seleccione una fecha ...'],
                           'pluginOptions' => [
                               'format' => 'yyyy-m-d',
                               'todayHighlight' => true]])
            ?>
        </div>
         <div class="row">
             <?= $form->field($model, 'id_presupuesto')->widget(Select2::classname(), [
                'data' => $presupueso,
                'options' => ['prompt' => 'Seleccione...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
            <?= $form->field($model, 'observacion', ['template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>'])->textarea(['rows' => 2]) ?> 
        </div>        
        <div class="panel-footer text-right">			
            <a href="<?= Url::toRoute("presupuesto-empresarial/presupuesto_mensual") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
        </div>
    </div>
</div>
<?php $form->end() ?>     
