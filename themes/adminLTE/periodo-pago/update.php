<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PeriodoPago */

$this->title = 'ACTUALIZAR: ' . $model->nombre_periodo;
$this->params['breadcrumbs'][] = ['label' => 'Periodo Pagos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_periodo_pago, 'url' => ['update', 'id' => $model->id_periodo_pago]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="periodo-pago-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
