<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PresentacionProducto */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Presentacion de Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="presentacion-producto-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
