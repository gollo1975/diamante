<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Clientes */

$this->title = 'Nueva';
$this->params['breadcrumbs'][] = ['label' => 'Factura de venta', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="factura-venta-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form_factura', [
        'model' => $model,

    ]) ?>

</div>