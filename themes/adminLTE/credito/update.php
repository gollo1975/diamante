<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Credito */

$this->title = 'Actualizar: ' . $model->id_credito;
$this->params['breadcrumbs'][] = ['label' => 'Creditos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_credito, 'url' => ['update', 'id' => $model->id_credito]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="credito-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
