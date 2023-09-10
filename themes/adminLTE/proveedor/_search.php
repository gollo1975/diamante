<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProveedorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="proveedor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_provedor') ?>

    <?= $form->field($model, 'id_tipo_documento') ?>

    <?= $form->field($model, 'nit/cedula') ?>

    <?= $form->field($model, 'primer_nombre') ?>

    <?= $form->field($model, 'segundo_nombre') ?>

    <?php // echo $form->field($model, 'primer_apellido') ?>

    <?php // echo $form->field($model, 'segundo_apellido') ?>

    <?php // echo $form->field($model, 'razon_social') ?>

    <?php // echo $form->field($model, 'nombre_completo') ?>

    <?php // echo $form->field($model, 'direccion') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'telefono') ?>

    <?php // echo $form->field($model, 'celular') ?>

    <?php // echo $form->field($model, 'codigo_departamento') ?>

    <?php // echo $form->field($model, 'codigo_municipio') ?>

    <?php // echo $form->field($model, 'nombre_contacto') ?>

    <?php // echo $form->field($model, 'celular_contacto') ?>

    <?php // echo $form->field($model, 'tipo_regimen') ?>

    <?php // echo $form->field($model, 'forma_pago') ?>

    <?php // echo $form->field($model, 'plazo') ?>

    <?php // echo $form->field($model, 'autoretenedor') ?>

    <?php // echo $form->field($model, 'id_naturaleza') ?>

    <?php // echo $form->field($model, 'tipo_sociedad') ?>

    <?php // echo $form->field($model, 'codigo_banco') ?>

    <?php // echo $form->field($model, 'tipo_cuenta') ?>

    <?php // echo $form->field($model, 'producto') ?>

    <?php // echo $form->field($model, 'tipo_transacion') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'fecha_creacion') ?>

    <?php // echo $form->field($model, 'id_empresa') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
