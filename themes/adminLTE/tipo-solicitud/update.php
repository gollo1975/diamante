<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoSolicitud */

$this->title = 'Actualizar: ' . $model->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Solicituds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_solicitud, 'url' => ['update', 'id' => $model->id_solicitud]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="tipo-solicitud-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
