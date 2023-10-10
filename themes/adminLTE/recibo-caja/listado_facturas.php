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

$this->title = 'Facturas';
$this->params['breadcrumbs'][] = ['label' => 'Recibo de caja', 'url' => ['view_cliente','id' =>$model->id_recibo, 'token' => $token]];
$this->params['breadcrumbs'][] = $model->id_recibo;
?>
     <div class="btn-group btn-sm" role="group">
          <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['view_cliente','id' => $model->id_recibo, 'token' => $token, 'tokenAcceso' => $tokenAcceso], ['class' => 'btn btn-primary btn-sm']) ?>
           
     </div>
<div class="panel panel-success">
    <div class="panel-body">
        <script language="JavaScript">
            function mostrarfiltro() {
                divC = document.getElementById("filtro");
                if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
            }
        </script>
        <?php $formulario = ActiveForm::begin([
            "method" => "get",
            "action" => Url::toRoute(["recibo-caja/buscar_facturas", 'id' => $id, 'token' => $token,'id_cliente' => $id_cliente, 'tokenAcceso' => $tokenAcceso]),
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
                </div>

                <div class="panel-footer text-right">
                    <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
                    <a align="right" href="<?= Url::toRoute(["recibo-caja/buscar_facturas", 'id' => $id, 'token' => $token,'id_cliente' => $id_cliente, 'tokenAcceso' => $tokenAcceso]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <li role="presentation" class="active"><a href="#listadofacturas" aria-controls="listadofacturas" role="tab" data-toggle="tab">Listado facturas<span class="badge"><?= $pagination->totalCount ?></span></a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="listadofacturas">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                           <div class="panel-body">
                                 <table class="table table-responsive">
                                    <thead>
                                        <tr style="font-size: 95%;">
                                             <th scope="col" style='background-color:#B9D5CE;'>Numero factura</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Fecha vencimiento</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Saldo</th>
                                            <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($factura as $val):
                                            ?>
                                            <tr style="font-size: 95%;">
                                                <td><?= $val->numero_factura ?></td>
                                                <td><?= $val->fecha_vencimiento ?></td>
                                                <td style="text-align: center"><?= '$'.number_format($val->saldo_factura) ?></td>
                                                <td style= 'width: 25px; height: 25px;'><input type="checkbox" name="pago_factura[]" value="<?= $val->id_factura ?>"></td>
                                            </tr>     
                                        <?php endforeach; ?>
                                    </tbody>     
                                </table>
                            </div>
                            <div class="panel-footer text-right">
                               <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar", ["class" => "btn btn-success btn-sm", 'name' => 'enviar_factura']) ?>
                            </div>
                        </div>
                        <?= LinkPager::widget(['pagination' => $pagination]) ?>
                    </div>
                </div>    
                <!-- TERMINA TABS-->  
            </div>     
        </div>     
    <?php $formulario->end() ?>    
</div> 
<script type="text/javascript">
	function marcar(source) 
	{
		checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}
</script>
