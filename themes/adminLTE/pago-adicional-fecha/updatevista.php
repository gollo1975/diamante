<?php

use yii\helpers\Html;

/* @var $this yii\web\View */ 
/* @var $model app\models\PagoAdicionalPermanente */

$this->title = 'Actualizar Adición: ' . $model->id_pago_permanente;
$this->params['breadcrumbs'][] = ['label' => 'Adicional x fecha', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_pago_permanente, 'url' => ['vista', 'id_pago_permanente' => $model->id_pago_permanente, 'vista', 'tipoadicion'=>$tipoadicion, 'vista', 'id'=>$id]];
$this->params['breadcrumbs'][] = 'Editar';
?>

<div class="pago-adicional-fecha-updatevista">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    
 <?php  if($tipoadicion == 1) {
          echo $this->render('_formadicion', [
            'model' => $model,
            'tipoadicion' => $tipoadicion,
            'id'=>$id, 
            'fecha_corte' => $fecha_corte,
           ]);
        }else{         
           echo $this->render('_formdescuento', [
            'model' => $model,
            'tipoadicion' => $tipoadicion, 
            'id'=>$id,  
            'fecha_corte' => $fecha_corte
           ]);  
        }
         ?>

</div>
