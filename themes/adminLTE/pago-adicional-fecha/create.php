<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PagoAdicionalFecha */

$this->title = 'Nueva fecha';
$this->params['breadcrumbs'][] = ['label' => 'Pago Adicional Fechas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pago-adicional-fecha-create">

    <!---<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => 0,
    ]) ?>

</div>
