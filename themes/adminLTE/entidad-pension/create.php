<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EntidadPension */

$this->title = 'NUEVA';
$this->params['breadcrumbs'][] = ['label' => 'Entidades de Pension', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-documento-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => 0,
    ]) ?>

</div>
