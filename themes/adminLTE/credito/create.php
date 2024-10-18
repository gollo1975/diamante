<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Credito */

$this->title = 'Nuevo credito';
$this->params['breadcrumbs'][] = ['label' => 'Creditos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credito-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
