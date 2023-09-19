<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ResolucionDian */

$this->title = 'Nueva';
$this->params['breadcrumbs'][] = ['label' => 'Resolucion Dians', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resolucion-dian-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => $sw,
    ]) ?>

</div>
