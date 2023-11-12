<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntradaProductoTerminado */

$this->title = 'Nueva';
$this->params['breadcrumbs'][] = ['label' => 'Entrada Producto Terminados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entrada-producto-terminado-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->   
    <?php if($sw == 0){ ?>
        <?= $this->render('_form', [
            'model' => $model,
            'ordenes' => $ordenes,
        ])?>
    <?php }else{?> 
        <?= $this->render('_form_sinorden', [
            'model' => $model,
        ])?>
    <?php }?>            

</div>
