<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Remisiones */

$this->title = 'NUEVA REMISION';
$this->params['breadcrumbs'][] = ['label' => 'Remisiones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remisiones-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
