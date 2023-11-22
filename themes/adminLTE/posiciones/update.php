<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Posiciones */

$this->title = 'Actualizar: ' . $model->posicion;
$this->params['breadcrumbs'][] = ['label' => 'Posiciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_posicion, 'url' => ['update', 'id' => $model->id_posicion]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="posiciones-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
