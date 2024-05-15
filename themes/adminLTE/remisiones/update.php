<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Remisiones */

$this->title = 'Actualizar: ' . $model->id_remision;
$this->params['breadcrumbs'][] = ['label' => 'Remisiones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_remision, 'url' => ['update', 'id' => $model->id_remision, 'accesoToken' => $accesoToken]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="remisiones-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'accesoToken' => $accesoToken,
    ]) ?>

</div>
