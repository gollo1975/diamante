<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EstudiosEmpleados */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Estudios Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estudios-empleados-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
