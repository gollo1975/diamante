<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EntidadSalud */

$this->title = 'NUEVA';
$this->params['breadcrumbs'][] = ['label' => 'Entidades de Salud', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-documento-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => $sw,
    ]) ?>

</div>
