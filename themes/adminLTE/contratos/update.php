<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Contratos */

$this->title = 'ACTUALIZAR: ' . $model->id_contrato;
$this->params['breadcrumbs'][] = ['label' => 'Contratos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_contrato, 'url' => ['update', 'id' => $model->id_contrato]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="contratos-update">

  <!--  <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => $sw,
    ]) ?>

</div>
