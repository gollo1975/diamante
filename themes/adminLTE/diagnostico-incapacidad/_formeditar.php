<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Recibocaja */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'ACTUALIZAR';
?>

<?php $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'formeditar'],
                'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-3 control-label'],
                    'options' => []
                ],
        ]);
?>

<div class="panel panel-success">
    <div class="panel-heading">
        Información del Diagnostico medico
    </div>
    <div class="panel-body">        
         <div class="row">
               <?= $form->field($model, 'codigo_diagnostico')->input('text', ['codigo_diagnostico', 'readonly' => TRUE, ['style' => 'width:15%']]) ?>    
        </div> 
        <div class="row">
            <?= $form->field($model, 'diagnostico')->textInput(['maxlength' => true]) ?>  					
        </div>
        <div class="panel-footer text-right">                        
            <a href="<?= Url::toRoute(["diagnostico-incapacidad/index"  ]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>