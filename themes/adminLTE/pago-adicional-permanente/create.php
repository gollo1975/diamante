<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PagoAdicionalPermanente */

$this->title = 'Create Pago Adicional Permanente';
$this->params['breadcrumbs'][] = ['label' => 'Pago Adicional Permanentes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pago-adicional-permanente-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
