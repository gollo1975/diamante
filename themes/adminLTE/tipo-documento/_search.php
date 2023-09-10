<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TipoDocumentoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipo-documento-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    
    <?php ActiveForm::end(); ?>

</div>
