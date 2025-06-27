<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntregaSolicitudKits */

$this->title = 'Update Entrega Solicitud Kits: ' . $model->id_entrega_kits;
$this->params['breadcrumbs'][] = ['label' => 'Entrega Solicitud Kits', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_entrega_kits, 'url' => ['view', 'id' => $model->id_entrega_kits]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="entrega-solicitud-kits-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
