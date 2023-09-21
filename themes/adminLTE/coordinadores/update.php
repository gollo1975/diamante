<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Coordinadores */

$this->title = 'Actualizar: ' . $model->nombre_completo;
$this->params['breadcrumbs'][] = ['label' => 'Coordinadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_coordinador, 'url' => ['update', 'id' => $model->id_coordinador]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="coordinadores-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
