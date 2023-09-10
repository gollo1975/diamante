<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PresupuestoEmpresarial */

$this->title = 'Actualizar: ' . $model->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Presupuesto Empresarials', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_presupuesto, 'url' => ['update', 'id' => $model->id_presupuesto]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="presupuesto-empresarial-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' =>$sw,
    ]) ?>

</div>
