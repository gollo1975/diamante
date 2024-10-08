<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\PeriodoPago;
use app\models\GrupoPago;
use app\models\TipoNomina;
use yii\widgets\LinkPager;
use kartik\date\DatePicker;
use kartik\select2\Select2;

$this->title = 'Periodo de pago';
$this->params['breadcrumbs'][] = ['label' => 'Programación Nómina', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<!--<h1>Nuevo Cliente</h1>-->

<?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
        'fieldConfig' => [
            'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-3 control-label'],
            'options' => []
        ],
    ]); ?>

<?php
$grupopago = ArrayHelper::map(GrupoPago::find()->orderBy('grupo_pago ASC')->all(), 'id_grupo_pago', 'grupo_pago');
$periodopago = ArrayHelper::map(PeriodoPago::find()->orderBy('id_periodo_pago ASC')->all(), 'id_periodo_pago', 'nombre_periodo');
$tiponomina = ArrayHelper::map(TipoNomina::find()->all(), 'id_tipo_nomina', 'tipo_pago');
?>
<div class="panel panel-success">
    <div class="panel-heading">
        Información Periodo Pago
    </div>
    <div class="panel-body">
        <div class="row">
            <?= $form->field($model, 'id_periodo_pago')->dropDownList($periodopago, ['prompt' => 'Seleccione...']) ?>
        </div>														      
        <div class="row">
            <?= $form->field($model, 'id_tipo_nomina')->dropDownList($tiponomina, ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'id_grupo_pago')->dropDownList($grupopago, ['prompt' => 'Seleccione...']) ?>
        </div>        
        <div class="row">
            <?= $form->field($model, 'dias_periodo')->input("text") ?>            
        </div>	
        <div class="row">            
            <?=  $form->field($model, 'fecha_desde')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('Y-m-d', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
        </div>
        <div class="row">            
            <?=  $form->field($model, 'fecha_hasta')->widget(DatePicker::className(), ['name' => 'check_issue_date',
                'value' => date('Y-m-d', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true]])
            ?>
        </div>
    </div>    
    <div class="panel-footer text-right">
        <a href="<?= Url::toRoute("programacion-nomina/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>        
    </div>
</div>
<?php $form->end() ?>
