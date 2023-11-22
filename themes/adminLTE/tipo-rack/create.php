<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoRack */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Tipo Racks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-rack-create">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => $sw,
    ]) ?>

</div>
