<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Contratos */

$this->title = 'NUEVO';
$this->params['breadcrumbs'][] = ['label' => 'Contratos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contratos-create">

   <!-- <h1><?= Html::encode($this->title) ?></h1>--->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => $sw,
    ]) ?>

</div>
