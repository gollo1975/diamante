<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoRack */

$this->title = 'Update Tipo Rack: ' . $model->id_rack;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Racks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_rack, 'url' => ['view', 'id' => $model->id_rack]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tipo-rack-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
