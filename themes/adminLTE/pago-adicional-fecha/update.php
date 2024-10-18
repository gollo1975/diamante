<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PagoAdicionalFecha */

$this->title = 'Actualizar: ' . $model->fecha_corte;
$this->params['breadcrumbs'][] = ['label' => 'Pago Adicional Fechas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_pago_fecha, 'url' => ['update', 'id' => $model->id_pago_fecha]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="pago-adicional-fecha-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => 1,
    ]) ?>

</div>
