<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProveedorEstudios */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Proveedor Estudios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proveedor-estudios-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
