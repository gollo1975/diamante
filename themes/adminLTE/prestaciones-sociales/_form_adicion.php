<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\ConceptoSalarios;
use kartik\select2\Select2;
use kartik\date\DatePicker;
if($tipo_adicion == 1){
   $this->title = 'Adicion pago';
} else {
    $this->title = 'Descuentos';
}   
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
if($tipo_adicion == 1){
   $conceptosalario = ArrayHelper::map(ConceptoSalarios::find()->where(['tipo_adicion'=> 1])->orderBy('nombre_concepto ASC')->all(), 'codigo_salario', 'nombre_concepto');
}else{
   $conceptosalario = ArrayHelper::map(ConceptoSalarios::find()->where(['compone_salario'=> 1])
                                                            ->andWhere(['=','tipo_adicion', 2])
                                                            ->orderBy('nombre_concepto ASC')->all(), 'codigo_salario', 'nombre_concepto');
}
?>
        <div class="panel panel-success">
            <div class="panel-heading">
               <?php
               if($tipo_adicion == 1){?>
                   Datos de los registros.
               <?php }else{?>    
                 Datos de los registros.
               <?php }?>    
            </div>
            <div class="panel-body">
                <div class="row">

                       <?= $form->field($model, 'codigo_salario')->widget(Select2::classname(), [
                    'data' => $conceptosalario,
                    'options' => ['placeholder' => 'Seleccione el concepto'],
                    'pluginOptions' => [
                        'allowClear' => true ]]);
                    ?>
                     <?= $form->field($model, 'valor_adicion')->textInput(['maxlength' => true]) ?>
                </div>        
                <div class="row">
                        
                         <?= $form->field($model, 'observacion')->textarea(['maxlength' => true]) ?>
                </div>
              
                <div class="panel-footer text-right">			
                    <a href="<?= Url::toRoute(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
                </div>
            </div>
        </div>
<?php $form->end() ?>    



