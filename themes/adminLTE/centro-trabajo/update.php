<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CentroTrabajo */

$this->title = 'Update Centro Trabajo: ' . $model->id_centro_trabajo;
$this->params['breadcrumbs'][] = ['label' => 'Centro Trabajos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_centro_trabajo, 'url' => ['view', 'id' => $model->id_centro_trabajo]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="centro-trabajo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
