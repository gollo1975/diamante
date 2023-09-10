<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cargos */

$this->title = 'Actualizar: ' . $model->nombre_cargo;
$this->params['breadcrumbs'][] = ['label' => 'Cargos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_cargo, 'url' => ['update', 'id' => $model->id_cargo]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="cargos-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
