<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pisos */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Pisos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pisos-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
