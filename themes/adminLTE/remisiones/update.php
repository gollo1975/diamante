<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Remisiones */

$this->title = 'Update Remisiones: ' . $model->id_remision;
$this->params['breadcrumbs'][] = ['label' => 'Remisiones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_remision, 'url' => ['view', 'id' => $model->id_remision]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="remisiones-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
