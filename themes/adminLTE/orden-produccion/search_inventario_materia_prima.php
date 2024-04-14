<?php
//modelos

//clases
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\MateriaPrimas;
$model = MateriaPrimas::findOne($detalle);
?>
<div class="modal-header">
     <button type="button" class="close" data-dismiss="modal">&times;</button>
     <h4 class="modal-title"></h4>
 </div>
 <div class="modal-body">        
     <div class="table table-responsive">
         <div class="panel panel-success ">
             <div class="panel-heading" style="text-align: left ">
                Resultado de la busqueda (Materia primas)
             </div>
             <table class="table table-bordered table-striped table-hover">
                 <thead>
                     <tr style="font-size: 95%;">
                         <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Código</b></th>
                         <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Materia prima</b></th>
                         <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Stock en unidades</b></th>
                         <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Stock en gramos</b></th>
                     </tr>
                 </thead>
                 <tbody>
                    <tr style="font-size: 95%;">
                        <td><?= $model->codigo_materia_prima?></td>
                        <td><?= $model->materia_prima?></td>
                        <td style="text-align: right"><?= ''. number_format($model->stock,0)?></td>
                        <td style = "text-align: right"><?= ''. number_format($model->stock_gramos,0)?></td>

                    </tr>
                 </tbody>

             </table>
         </div>

     </div>
 </div>


