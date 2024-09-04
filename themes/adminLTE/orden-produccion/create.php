<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenProduccion */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Orden Produccions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orden-produccion-create">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => $sw,
    ]) ?>

</div>
