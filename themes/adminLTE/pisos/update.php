<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pisos */

$this->title = 'Actualizar: ' . $model->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Pisos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_piso, 'url' => ['update', 'id' => $model->id_piso]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="pisos-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
