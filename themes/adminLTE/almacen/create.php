<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Almacen */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Almacen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="almacen-create">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
