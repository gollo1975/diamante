<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PagoAdicionalPermanente */

$this->title = 'Update Pago Adicional Permanente: ' . $model->id_pago_permanente;
$this->params['breadcrumbs'][] = ['label' => 'Pago Adicional Permanentes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_pago_permanente, 'url' => ['view', 'id' => $model->id_pago_permanente]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pago-adicional-permanente-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
