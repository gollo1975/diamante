<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EspecificacionProducto */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Especificacion Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="especificacion-producto-create">

  <!--  <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
