<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntradaProductoTerminado */

$this->title = 'Actualizar';
$this->params['breadcrumbs'][] = ['label' => 'Entrada Producto Terminados', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_entrada, 'url' => ['update', 'id' => $model->id_entrada]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="entrada-producto-terminado-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php if($sw == 0){?>
        <?= $this->render('_form_editar', [
            'model' => $model,
            'ordenes' => $ordenes,
        ]) ?>
    <?php }else{ ?>
        <?= $this->render('_form_sinorden', [
            'model' => $model,
        ]) ?>
    <?php }?>

</div>
