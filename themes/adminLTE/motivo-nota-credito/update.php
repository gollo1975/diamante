<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MotivoNotaCredito */

$this->title = 'Actualizar: ' . $model->concepto;
$this->params['breadcrumbs'][] = ['label' => 'Motivo Nota Creditos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_motivo, 'url' => ['update', 'id' => $model->id_motivo]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="motivo-nota-credito-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
