<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ReciboCaja */

$this->title = 'Update Recibo Caja: ' . $model->id_recibo;
$this->params['breadcrumbs'][] = ['label' => 'Recibo Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_recibo, 'url' => ['view', 'id' => $model->id_recibo]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="recibo-caja-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
