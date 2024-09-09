<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MateriaPrimas */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Materia Primas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="materia-primas-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
         'sw' => $sw,
    ]) ?>

</div>
