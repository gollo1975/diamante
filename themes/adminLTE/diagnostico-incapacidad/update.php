<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DiagnosticoIncapacidad */

$this->title = 'Editar Diagnostico: ' . $model->codigo_diagnostico;
$this->params['breadcrumbs'][] = ['label' => 'Diagnostico Incapacidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->codigo_diagnostico, 'url' => ['view', 'id' => $model->id_codigo]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="diagnostico-incapacidad-update">

   <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_formeditar', [
        'model' => $model,
    ]) ?>

</div>
