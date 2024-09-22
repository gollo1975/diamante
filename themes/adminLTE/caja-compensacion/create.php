<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CajaCompensacion */

$this->title = 'Create Caja Compensacion';
$this->params['breadcrumbs'][] = ['label' => 'Caja Compensacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="caja-compensacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
