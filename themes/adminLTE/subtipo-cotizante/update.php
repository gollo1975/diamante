<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SubtipoCotizante */

$this->title = 'ACTUALIZAR: ' . $model->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Subtipos Cotizantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_subtipo_cotizante, 'url' => ['update', 'id' => $model->id_subtipo_cotizante]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="subtipo-cotizante-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
