<?php
use yii\bootstrap;
use yii\bootstrap\Html;
use app\models\MatriculaEmpresa;
use app\models\Municipios;
use app\models\Departamentos;
/* @var $this yii\web\View */
$empresa = Matriculaempresa::findOne(1);
$municipio = Municipios::find()->all();
$departamento = Departamentos::find()->all();
$proveedor = \app\models\Proveedor::find()->all();
$cliente = \app\models\Clientes::find()->where(['=','estado_cliente', 0])->all();
$this->params['breadcrumbs'][] = ['label' => 'Diamante ERP', 'url' => ['index']];
$this->title = $empresa->nombre_sistema;
?>

<div class="panel panel-primary">
        <div class="login-logo">
            <a href="#"><b><?= $empresa->nombre_sistema ?></a>
       </div>
    <div class="panel-body">
   <section class="content">
    <div class="container-fluid" style="text-align: center">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-light-blue">
                    <div class="inner">
                        <h4 style="text-align: center; color: #FFFFFF;"><span class='glyphicon glyphicon-home'> <font face="arial">MUNICIPIOS</font></span></h4>  
                        <h3 style="text-align: center;"><?= count($municipio)?></h3>
                    </div>
                    <div class="icon">
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                  <div class="inner">
                    <h4 style="text-align: center; color: #2B5DB0;"><span class='glyphicon glyphicon-globe'> <font face="arial">DEPARTAMENTOS</font></span></h4>  
                    <h3 style="text-align: center;"><?= count($departamento)?></h3>
                  </div>
                  <div class="icon">
                  </div>
                </div>
            </div>    
            <div class="col-lg-3 col-6">
                <div class="small-box bg-olive-active">
                  <div class="inner">
                    <h4 style="text-align: center; color: white"><span class='glyphicon glyphicon-user'> <font face="arial">CLIENTES</font></span></h4>
                    <h3 style="text-align: center;"><?= count($cliente)?></h3>
                  </div>
                  <div class="overlay">
                    <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                  </div>
                </div>
            </div>  
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                  <div class="inner">
                      <h4 style="text-align: center; color: #253886;"><span class='glyphicon glyphicon-user'> <font face="arial">PROVEEDORES</font></span></h4>
                    <h3 style="text-align: center;"><?= count($proveedor)?></h3>
                  </div>
                  <div class="overlay">
                    <i ></i>
                  </div>
                </div>
            </div>  
           
          </div>
      </div>
    </section> 
        

<!-- Remove the container if you want to extend the Footer to full width. -->
   <div class="container my-5">

  <!-- Footer -->
  <footer
          class="text-center text-lg-start text-white"
          style="background-color: white; width: 100%; text-align: center"
          >
    <!-- Section: Social media -->
    <section
             class="d-flex justify-content-between p-4"
             style="background-color: #136C5D; text-align: center"
             >
      <!-- Left -->
      <div class="me-5">
          <span class="badge"> <h4><font face="arial"><?= $empresa->razon_social_completa?></font></h4></span>
      </div>
      <!-- Left -->

      <!-- Right -->
      
      <!-- Right -->
    </section>
    <!-- Section: Social media -->

    <!-- Section: Links  -->
    <section class="">
      <div class="container text-center text-md-start mt-5">
        <!-- Grid row -->
        <div class="row mt-3">
          <!-- Grid column -->
          <div class="col-md-3 col-lg-6 col-xl-3 mx-auto mb-4">
            <!-- Content -->
            <h4 class="text-uppercase fw-bold"><b><span class="badge"><font face="arial">NUESTRA COMPAÑIA</font></span></b></h4>
            <hr
                class="mb-4 mt-0 d-inline-block mx-auto"
                style="width: 100px; background-color: black; height: 3px"
                />
            <p style ="text-align: justify">
                <b><font face="arial"><?= $empresa->razon_social_completa?></font></b>, <font face="arial"><?= $empresa->presentacion?> </font>
            </p>
          </div>
          <!-- Grid column -->

          <!-- Grid column -->
         <!-- <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
            <!-- Links -->
           <!-- <h6 class="text-uppercase fw-bold"><span class="badge"><font face="arial">PRODUCTOS</font></span></h6>
            <hr
                class="mb-4 mt-0 d-inline-block mx-auto"
                style="width: 100px; background-color: black; height: 3px"
                />
            <p>
              <a href="#!" class="text-black">Systime </a>
            </p>
            <p>
              <a href="#!" class="text-black">Diamante ERP</a>
            </p>
            <p>
              <a href="#!" class="text-black">Producción</a>
            </p>
            <p>
              <a href="#!" class="text-black">Inventarios</a>
            </p>
          </div>
          <!-- Grid column -->

          <!-- Grid column -->
          <!-- Grid column -->

          <!-- Grid column -->
          <div class="col-md-4 col-lg-6 col-xl-3 mx-auto mb-md-0 mb-4">
            <!-- Links -->
            <h4 class="text-uppercase fw-bold"><span class="badge"><font face="arial">CONTACTO</font></span></h4>
            <hr
                class="mb-4 mt-0 d-inline-block mx-auto"
                style="width: 100px; background-color: black; height: 3px"
                />
            <p><i class='glyphicon glyphicon-user'></i>  Medellin - Colombia</p>
            <p><i class='glyphicon glyphicon-envelope'></i>  soporte@diamantesj.com</p>
            <p><i class='glyphicon glyphicon-phone'></i>  323 308 36 29 - 323 552 28 23</p>
          </div>
          <!-- Grid column -->
        </div>
        <!-- Grid row -->
      </div>
    </section>
    <!-- Section: Links  -->

    <!-- Copyright -->
    <div
         class="text-center p-4"
         style="background-color: #136C5D"
         >
        <h4 class="text-uppercase fw-bold"><span class="badge"> <font face="arial">© 2023 Copyright | Todos los dereechos reservados | DIAMANTE SJ SAS . Version 1.0</font> </span></h4>
     
     
    </div>
    <!-- Copyright -->
  </footer>
  <!-- Footer -->

</div>
    </div>
</div>   
