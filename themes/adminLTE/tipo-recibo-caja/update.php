<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoReciboCaja */

$this->title = 'Actualizar: ' . $model->concepto;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Recibo Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tipo, 'url' => ['update', 'id' => $model->id_tipo]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="tipo-recibo-caja-update">

  <!--  <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
