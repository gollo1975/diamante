<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PresupuestoEmpresarial */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Presupuesto Empresarials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="presupuesto-empresarial-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' =>$sw,
    ]) ?>

</div>
