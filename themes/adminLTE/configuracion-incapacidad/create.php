<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionIncapacidad */

$this->title = 'Configuracion Incapacidad';
$this->params['breadcrumbs'][] = ['label' => 'Configuracion Incapacidad', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuracion-incapacidad-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
