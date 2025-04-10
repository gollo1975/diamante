<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntregaDotacion */

$this->title = 'Nueva';
$this->params['breadcrumbs'][] = ['label' => 'Entrega Dotacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entrega-dotacion-create">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'token' => $token,
    ]) ?>

</div>
