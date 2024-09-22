<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntidadPension */

$this->title = 'Update Entidad Pension: ' . $model->id_entidad_pension;
$this->params['breadcrumbs'][] = ['label' => 'Entidad Pensions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_entidad_pension, 'url' => ['view', 'id' => $model->id_entidad_pension]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="entidad-pension-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
