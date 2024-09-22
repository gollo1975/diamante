<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MotivoTerminacion */

$this->title = 'Update Motivo Terminacion: ' . $model->id_motivo_terminacion;
$this->params['breadcrumbs'][] = ['label' => 'Motivo Terminacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_motivo_terminacion, 'url' => ['view', 'id' => $model->id_motivo_terminacion]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="motivo-terminacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
