<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\ConfiguracionCredito;
use app\models\ConceptoSalarios;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionCredito */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$form = ActiveForm::begin([
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
<?php
   $salarios = ArrayHelper::map(ConceptoSalarios::find()->where(['=','debito_credito',2])
                                  ->andWhere(['=','adicion',1])                                
                                  ->orderBy('nombre_concepto ASC')
                                  ->all(), 'codigo_salario', 'nombre_concepto');
?>


        <div class="panel panel-success">
            <div class="panel-heading">
                Configuración de crédito
            </div>
            <div class="panel-body">        														   		
                <div class="row">
                    <?= $form->field($model, 'nombre_credito')->textInput(['maxlength' => true]) ?>    
                </div>
                <div class="row">
                   <?= $form->field($model, 'codigo_salario')->widget(Select2::classname(), [
                    'data' => $salarios,
                    'options' => ['placeholder' => 'Seleccione el concepto'],
                    'pluginOptions' => ['allowClear' => true ],
                     ]); ?>
                </div>       
               <div class="panel panel-success">
                        <div class="panel-footer text-right">			
                            <a href="<?= Url::toRoute("configuracion-credito/index") ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
                            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
                        </div>
                </div>
        </div>
<?php $form->end() ?>     