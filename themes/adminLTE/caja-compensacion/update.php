<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CajaCompensacion */

$this->title = 'ACTUALIZAR: ' . $model->caja;
$this->params['breadcrumbs'][] = ['label' => 'Caja Compensacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_caja, 'url' => ['view', 'id' => $model->id_caja]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="caja-compensacion-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>--->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => $sw,
    ]) ?>

</div>
