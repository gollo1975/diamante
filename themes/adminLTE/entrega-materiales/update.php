<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntregaMateriales */

$this->title = 'Update Entrega Materiales: ' . $model->id_entrega;
$this->params['breadcrumbs'][] = ['label' => 'Entrega Materiales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_entrega, 'url' => ['view', 'id' => $model->id_entrega]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="entrega-materiales-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
