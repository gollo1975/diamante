<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoCotizante */

$this->title = 'ACTUALIZAR: ' . $model->tipo;
$this->params['breadcrumbs'][] = ['label' => 'Tipos Cotizantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tipo_cotizante, 'url' => ['update', 'id' => $model->id_tipo_cotizante]];
$this->params['breadcrumbs'][] = 'Editar Tipo Cotizante';
?>
<div class="motivo-terminacion-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
