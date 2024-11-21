<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\Session;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\AccessControl;

?>


<?php $form = ActiveForm::begin([

    'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
    'fieldConfig' => [
        'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
        'labelOptions' => ['class' => 'col-sm-3 control-label'],
        'options' => []
    ],
]); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body">
        
        <div class="table table-responsive">
            <div class="panel panel-success ">
                <div class="panel-heading">
                    Lista de packing <span class="badge"><?= count($model)?></span>
                </div>
                <div class="panel-body">
                    <table class="table table-responsive">
                        <thead>
                            <tr style='font-size:90%;'>
                                <td scope="col" style='background-color:#B9D5CE; '>Id</td>
                                <td scope="col" style='background-color:#B9D5CE; '>Numero de packing</td>
                                <td scope="col" style='background-color:#B9D5CE;'>Cliente</td>
                                <td scope="col" style='background-color:#B9D5CE;'>Total cajas</td>
                                <td scope="col" style='background-color:#B9D5CE;'>Total unidades</td>
                                <td scope="col" style='background-color:#B9D5CE;'></td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $suma = 0;
                        foreach ($model as $val):
                            $suma += $val->total_unidades_packing;
                            ?>
                            <tr style="font-size: 95%;">
                                <td><?= $val->id_packing ?></td>  
                                <td><?= $val->numero_packing ?></td>  
                                <td><?= $val->cliente ?></td>
                                <td><?= $val->total_cajas ?></td>
                                <td><?= $val->total_unidades_packing ?></td>
                                <td style= 'width: 25px; height: 25px;'>
                                    <a href="javascript:void(0);" onclick="window.open('<?= Url::toRoute(["packing-pedido/view", 'id' => $val->id_packing]) ?>', '_blank')"><span class="glyphicon glyphicon-eye-open" 'apenk_blank'></span></a>
                                    
                                </td> 
                            </tr>
                            </tbody>
                            <?php
                        endforeach; ?>     
                             <tr>
                        <td colspan="3"></td>
                        <td align="center"><b>Total unidades:</b></td>
                        <td align="right" ><b><?= ''.number_format($suma,0); ?></b></td>
                        <td colspan="1"></td
                      
                    </tr>    
                    </table>
                   
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
