<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MateriaPrimas */

$this->title = 'Actualizar: ' . $model->materia_prima;
$this->params['breadcrumbs'][] = ['label' => 'Materia Primas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_materia_prima, 'url' => ['update', 'id' => $model->id_materia_prima]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="materia-primas-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
