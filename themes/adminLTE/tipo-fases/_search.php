<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TipoFasesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipo-fases-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php ActiveForm::end(); ?>

</div>
