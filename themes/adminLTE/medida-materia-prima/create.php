<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MedidaMateriaPrima */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Medida Materia Primas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medida-materia-prima-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
