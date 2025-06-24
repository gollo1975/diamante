<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoFases */

$this->title = 'Actualizar: ' . $model->nombre_fase;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Fases', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_fase, 'url' => ['update', 'id' => $model->id_fase]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="tipo-fases-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
