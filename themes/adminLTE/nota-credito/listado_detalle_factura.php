<?php
//clase
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

$this->title = 'Detalle factura';
$this->params['breadcrumbs'][] = ['label' => 'Nota credito', 'url' => ['listar_detalle_factura', 'id'=> $id]];
$this->params['breadcrumbs'][] = $id_factura;
?>
<p>
    <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['view','id' =>$id], ['class' => 'btn btn-primary btn-sm']) ?>
</p>
    <div class="panel-body">
        <script language="JavaScript">
            function mostrarfiltro() {
                divC = document.getElementById("filtro");
                if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
            }
        </script>
        <?php $formulario = ActiveForm::begin([
            "method" => "get",
            "action" => Url::toRoute(["nota-credito/listar_detalle_factura", 'id' => $id, 'id_factura' => $id_factura]),
            "enableClientValidation" => true,
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                            'template' => '{label}<div class="col-sm-3 form-group">{input}{error}</div>',
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                            'options' => []
                        ],
        ]);
        ?>
        <div class="panel panel-success panel-filters">
            <div class="panel-heading" onclick="mostrarfiltro()">
                Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
            </div>
            <div class="panel-body" id="filtro" style="display:none">
                <div class="row" >
                    <?= $formulario->field($form, "q")->input("search") ?>
                    <?= $formulario->field($form, "nombre")->input("search") ?>
                </div>

                <div class="panel-footer text-right">
                    <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
                    <a align="right" href="<?= Url::toRoute(["nota-credito/listar_detalle_factura", 'id' => $id, 'id_factura' => $id_factura]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
                </div>
            </div>
        </div>
        <?php $formulario->end() ?>
        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-3 control-label'],
                'options' => []
            ],
        ]); ?>
        <div>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#detalle" aria-controls="detalle" role="tab" data-toggle="tab">Detalle factura<span class="badge"><?= $pagination->totalCount ?></span></a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="detalle">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                           <div class="panel-body">
                               <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                             <th scope="col" style='background-color:#B9D5CE;'>Código</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Cantidad</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Vr. unitario</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Subtotal</th>
                                            <th scope="col" style='background-color:#B9D5CE; text-align: center'>Cantida a devolver</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($variable as $val): ?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->codigo_producto ?></td>
                                                <td><?= $val->producto ?></td>
                                                <td><?= $val->cantidad ?></td>
                                                <td><?= $val->valor_unitario ?></td>
                                                <td><?= $val->subtotal ?></td>
                                                <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text" name="cantidad_devolver[]" style="text-align: right" size="7" maxlength="true"> </td> 
                                                <input type="hidden" name="devolucion_factura_detalle[]" value="<?= $val->id_detalle?>"> 
                                            </tr>     
                                        <?php endforeach; ?>
                                    </tbody>     
                                </table>
                            </div>
                            <div class="panel-footer text-right">
                               <?= Html::submitButton("<span class='glyphicon glyphicon-send'></span> Enviar", ["class" => "btn btn-success btn-sm", 'name' => 'devolucion_productos']) ?>
                            </div>
                        </div>
                        <?= LinkPager::widget(['pagination' => $pagination]) ?>
                    </div>
                </div>    
                <!-- TERMINA TABS-->  
            </div>     
        </div>    
        <?php ActiveForm::end(); ?>
    </div>        
