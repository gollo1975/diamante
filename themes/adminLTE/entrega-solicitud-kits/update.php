<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntregaSolicitudKits */

$this->title = 'Actualizar: ' . $model->solicitud->concepto;
$this->params['breadcrumbs'][] = ['label' => 'Entrega Solicitud Kits', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_entrega_kits, 'url' => ['update', 'id' => $model->id_entrega_kits]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="entrega-solicitud-kits-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form_editar', [
        'model' => $model,
    ]) ?>

</div>
