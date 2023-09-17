<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PresupuestoEmpresarial */

$this->title = 'Actualizar: ' . $model->presupuesto->area->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Presupuesto Empresarials', 'url' => ['presupuesto_mensual']];
$this->params['breadcrumbs'][] = ['label' => $model->id_mensual, 'url' => ['update_mensual', 'id' => $model->id_mensual]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="presupuesto-empresarial-update_mensual">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form_crear_fecha', [
        'model' => $model,

    ]) ?>

</div>
