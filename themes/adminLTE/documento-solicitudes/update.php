<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentoSolicitudes */

$this->title = 'Update Documento Solicitudes: ' . $model->id_solicitud;
$this->params['breadcrumbs'][] = ['label' => 'Documento Solicitudes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_solicitud, 'url' => ['view', 'id' => $model->id_solicitud]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="documento-solicitudes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
