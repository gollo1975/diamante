<?php

//clase
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\Session;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\AccessControl;

/* @var $this yii\web\View */
/* @var $model app\models\Ordenproduccion */

$this->title = 'SIMULADOR DE MATERIAS PRIMAS';
$this->params['breadcrumbs'][] = ['label' => 'Simulador de materia prima', 'url' => ['view','id' =>$id, 'token' =>$token]];
$this->params['breadcrumbs'][] = $id;
if(count($conFaseinicial) > 0){
    
}else{
     Yii::$app->getSession()->setFlash('warning', 'No se ha configurado la MATERIA PRIMA para este producto. Favor validar la informacion.');
}
?>
<div class="orden-produccion-simulador">
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['view', 'id' => $id,'token'=> $token], ['class' => 'btn btn-primary btn-sm']) ?>  
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h4>LISTADO DE MATERIA PRIMA</h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr style="font-size: 85%;">
                        <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Codigo</b></th>                        
                        <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Materia prima</b></th>
                         <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Stock</b></th>
                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Tipo de fase</th>
                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Porcentaje_aplicacion</th> 
                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Cantidad gramos</th>  
                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Cumple stock</th>
                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Cantidad faltante</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $cantidad = 0; $auxiliar = ''; $unidades = 0;
                    foreach ($conFaseinicial as $materia):
                        $item = \app\models\MateriaPrimas::findOne($materia->id_materia_prima);
                        ?>
                        <tr style="font-size: 85%;">
                            <td><?= $materia->codigo_materia?> </td>
                            <td ><?= $materia->nombre_materia_prima?> </td>
                            <td style="text-align: right"><?= $materia->materiaPrima->stock_gramos?> </td>
                            <?php if($materia->id_fase == 1){?>
                                <td style='background-color:<?=$materia->fase->color?>'><?= $materia->fase->nombre_fase?> </td>
                            <?php }else{?>
                                <td style='background-color:<?=$materia->fase->color?>'><?= $materia->fase->nombre_fase?> </td>
                            <?php }?>    
                                <td style="text-align: right"><?= $materia->porcentaje_aplicacion?> </td>
                            <?php
                            $cantidad = round(($orden->tamano_lote * $materia->porcentaje_aplicacion)/100);
                            ?>
                            <td style="text-align: right"><?= $cantidad?> </td>
                            <?php
                            if($cantidad <= $item->stock_gramos){
                                  $auxiliar = 'OK';
                                   $unidades = 0;
                            }else{
                                  $auxiliar = 'FALTA';
                                  $unidades = ($cantidad - $item->stock_gramos);
                            }?>
                            <td><?= $auxiliar?> </td>
                            <td style="text-align: right"><?= $unidades?> -- Gramos</td>
                              
                        </tr>
                    <?php endforeach;  ?>
                </tbody>
            </table>
        </div>
    </div>    
    
</div>