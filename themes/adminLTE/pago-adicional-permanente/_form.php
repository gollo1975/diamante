<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PagoAdicionalPermanente */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pago-adicional-permanente-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_empleado')->textInput() ?>

    <?= $form->field($model, 'codigo_salario')->textInput() ?>

    <?= $form->field($model, 'id_contrato')->textInput() ?>

    <?= $form->field($model, 'id_grupo_pago')->textInput() ?>

    <?= $form->field($model, 'id_pago_fecha')->textInput() ?>

    <?= $form->field($model, 'fecha_corte')->textInput() ?>

    <?= $form->field($model, 'tipo_adicion')->textInput() ?>

    <?= $form->field($model, 'valor_adicion')->textInput() ?>

    <?= $form->field($model, 'permanente')->textInput() ?>

    <?= $form->field($model, 'aplicar_dia_laborado')->textInput() ?>

    <?= $form->field($model, 'aplicar_prima')->textInput() ?>

    <?= $form->field($model, 'aplicar_cesantias')->textInput() ?>

    <?= $form->field($model, 'estado_registro')->textInput() ?>

    <?= $form->field($model, 'estado_periodo')->textInput() ?>

    <?= $form->field($model, 'detalle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_creacion')->textInput() ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
