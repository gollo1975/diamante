<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FacturaVentaPunto */

$this->title = 'Nueva Factura';
$this->params['breadcrumbs'][] = ['label' => 'Factura Venta Puntos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="factura-venta-punto-create">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'accesoToken' => $accesoToken,
    ]) ?>

</div>
