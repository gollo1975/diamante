<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TiempoServicio */

$this->title = 'Update Tiempo Servicio: ' . $model->id_tiempo;
$this->params['breadcrumbs'][] = ['label' => 'Tiempo Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tiempo, 'url' => ['view', 'id' => $model->id_tiempo]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tiempo-servicio-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
