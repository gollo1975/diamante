<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Empleados */

$this->title = 'Actualizar a : ' . $model->nombre_completo;
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_empleado, 'url' => ['update', 'id' => $model->id_empleado]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="empleados-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => $sw,
    ]) ?>

</div>
