    <?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;

$this->title = 'Importar programaciones';
$this->params['breadcrumbs'][] = $this->title;

?>


<?php $form = ActiveForm::begin([
    'options' => ['class' => 'form-horizontal condensed', 'role' => 'form', 'enctype' => 'multipart/form-data'],
    "method" => "post",
     "enableClientValidation" => true,
    'fieldConfig' => [
        'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
        'labelOptions' => ['class' => 'col-sm-3 control-label'],
        'options' => []
    ],
]); ?>
    <div class="panel panel-success">
        <div class="panel-heading">
            Información del archivo...
        </div>
        <div class="panel-body">
            <div class="row">
                <?= $form->field($model, 'fileProgramacion')->fileInput(['accept' => 'xlsx, xls']) ?>
            </div>
            <div class="panel-footer text-right">                
                <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['programacion-citas/view', 'id' => $id,'agenteToken' => $agenteToken, 'tokenAcceso' => $tokenAcceso], ['class' => 'btn btn-primary btn-sm']); ?>
                <?= Html::submitButton("<span class='glyphicon glyphicon-upload'></span> Subir Archivo", ["class" => "btn btn-success btn-sm",]) ?>
            </div>
        </div>
        <div class="panel panel-success">
            <div class="panel-heading">
                Información del archivo y su configuracion para exportacion.
            </div>
        <div class="panel-body">
            <li>
               Nombre del archivo debe de ser (programacioncitas.xlsx). 
            </li>
            <li>
               Solo se importan archivos de excel con extensión (xlsx y xls).
            </li>
            <li>
               Los campos que se necesitan son (codigo_progrmacion, id_cliente, id_tipo_visita, hora_visita, nota, fecha_cita_comercial). 
            </li>
            <li>
               La HORA_VISITA: Debe de ser en formato entero o texto, ejemplo (12:30:00), debe de ser 123000. Debe de contener 6 digitos
            </li>
            <li>
               La FECHA_CITA_COMERCIAL: Debe de ser en formato entero o texto, ejemplo (2024-07-01), debe de ser 20240701. Debe de contener 8 digitos.
            </li>
            <li>
               Debe de solicitar el formato al (3233083629) o crear uno en excel con los campos aca informados y su respectivo formato.
            </li>
        </div>    
    </div>
<?php ActiveForm::end() ?>