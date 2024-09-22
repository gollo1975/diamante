<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoCotizante */

$this->title = 'Update Tipo Cotizante: ' . $model->id_tipo_cotizante;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Cotizantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tipo_cotizante, 'url' => ['view', 'id' => $model->id_tipo_cotizante]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tipo-cotizante-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
