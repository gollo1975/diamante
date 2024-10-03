<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DiagnosticoIncapacidad */

$this->title = 'NUEVO';
$this->params['breadcrumbs'][] = ['label' => 'Diagnostico Incapacidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="diagnostico-incapacidad-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
       
    ]) ?>

</div>
