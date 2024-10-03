<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionSalario */

$this->title = 'ACTUALIZAR';
$this->params['breadcrumbs'][] = ['label' => 'Configuracion Salarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_salario, 'url' => ['view', 'id' => $model->id_salario]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="configuracion-salario-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
