<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SubtipoCotizante */

$this->title = 'Update Subtipo Cotizante: ' . $model->id_subtipo_cotizante;
$this->params['breadcrumbs'][] = ['label' => 'Subtipo Cotizantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_subtipo_cotizante, 'url' => ['view', 'id' => $model->id_subtipo_cotizante]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="subtipo-cotizante-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
