<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrestacionesSociales */

$this->title = 'Update Prestaciones Sociales: ' . $model->id_prestacion;
$this->params['breadcrumbs'][] = ['label' => 'Prestaciones Sociales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_prestacion, 'url' => ['view', 'id' => $model->id_prestacion]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="prestaciones-sociales-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
