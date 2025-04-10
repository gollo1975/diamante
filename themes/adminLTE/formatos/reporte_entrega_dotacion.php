<?php

use inquid\pdf\FPDF;
use app\models\EntregaDotacion;
use app\models\EntregaDotacionDetalles;
use app\models\MatriculaEmpresa;
use app\models\Municipios;
use app\models\Departamentos;

class PDF extends FPDF {

    function Header() {
        $id_entrega = $GLOBALS['id_entrega'];
        $entrega = EntregaDotacion::findOne($id_entrega);
        $config = MatriculaEmpresa::findOne(1);
        $municipio = Municipios::findOne($config->codigo_municipio);
        $departamento = Departamentos::findOne($config->codigo_departamento);
        //Logo
       $this->SetXY(43, 10);
        $this->Image('dist/images/logos/logoempresa.png', 10, 10, 30, 19);
        //Encabezado
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(53, 9);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("Empresa:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(80, 5, utf8_decode($config->razon_social_completa), 0, 0, 'L', 1);
      
        //FIN
        $this->SetXY(53, 13);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("Nit:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(80, 5, utf8_decode($config->nit_empresa." - ".$config->dv), 0, 0, 'L', 1);
       
        //FIN
        $this->SetXY(53, 17);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("Dirección:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(80, 5, utf8_decode($config->direccion), 0, 0, 'L', 1);
        
        //FIN
        $this->SetXY(53, 21);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("Teléfono:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(80, 5, utf8_decode($config->telefono), 0, 0, 'L', 1);
        
        //FIN
        $this->SetXY(53, 25);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("Municipio:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(80, 5, utf8_decode($config->codigoMunicipio->municipio." - ".$config->codigoDepartamento->departamento), 0, 0, 'L', 1);
       

        //FIN
        $this->SetXY(10, 32);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
         $this->SetXY(10, 32.5);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
        //Prestaciones sociales
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(10, 39);
        $this->SetFont('Arial', 'B', 13);
        $this->Cell(162, 7, utf8_decode("ENTREGA / DEVOLUCION DE DOTACION"), 0, 0, 'l', 0);
        $this->Cell(30, 7, utf8_decode('N°. '.str_pad($entrega->numero_entrega, 5, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
       // $this->SetFillColor(200, 200, 200);
        $this->SetXY(10, 48);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(21, 5, utf8_decode("Documento:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 5, utf8_decode($entrega->empleado->nit_cedula), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Empleado:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(57, 5, utf8_decode($entrega->empleado->nombre_completo), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, utf8_decode("Cantidad:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(23, 5, ' '. number_format($entrega->cantidad, 0), 0, 0, 'L', 1);
        //FIN
       $this->SetXY(10, 52);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(21, 5, utf8_decode("Autorizado:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 5, utf8_decode($entrega->autorizadaEntrega), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Fecha entrega:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(57, 5, utf8_decode($entrega->fecha_entrega), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, utf8_decode("Tipo proceso:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(23, 5, utf8_decode($entrega->tipoProceso), 0, 0, 'L', 1);
        //FIN
        $this->SetXY(10, 56);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(21, 5, utf8_decode("Usuario:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 5, utf8_decode($entrega->user_name), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Fecha Registro:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(57, 5, utf8_decode($entrega->fecha_hora_registro), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, utf8_decode("Tipo dotacion:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(23, 5, $entrega->tipoDotacion->descripcion, 0, 0, 'L', 1);
        //FIN
         //FIN
        $this->SetXY(10, 60);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(21, 5, utf8_decode("Observacion:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(171, 5, $entrega->observacion, 0, 0, 'L', 1);
        //FIN
        //FIN
              
        $this->EncabezadoDetalles();
     
        //Lineas del encabezado
        $this->Line(10,70,10,200);
        $this->Line(38,70,38,200);
        $this->Line(113,70,113,200);
        $this->Line(157,70,157,200);
        $this->Line(202,70,202,200);
        $this->Line(10,200,202,200);//linea horizontal inferior  
     
    }
    function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('CODIGO', ('PRODUCTO'), ('CANTIDAD'), ('TALLAS'));
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(28, 75, 44, 45);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(5);
    }        
                      

    function Body($pdf,$model) {        
        $detalle = EntregaDotacionDetalles::find()->where(['=','id_entrega', $model->id_entrega])->all();		
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
		
        foreach ($detalle as $val) {                                                           
            $pdf->Cell(28, 4, $val->inventario->codigo_producto, 0, 0, 'L');
            $pdf->Cell(75, 4, utf8_decode($val->inventario->nombre_producto), 0, 0, 'L');
            $pdf->Cell(44, 4, $val->cantidad, 0, 0, 'R');
            $pdf->Cell(45, 4, $val->talla, 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);                              
        }
	$this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //productos
        $pdf->SetXY(10, 67);
        $pdf->Cell(192, 5, 'LISTADO DE PRODUCTOS A DEVOLVER', 1, 0, 'C',1);
       
	//firma trabajador
        $pdf->SetXY(10, 240);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(35, 5, 'FIRMA EMPLEADO: ___________________________________________________', 0, 0, 'L',0);
        $pdf->SetXY(10, 245);
        $pdf->Cell(35, 5, 'C.C.:', 0, 0, 'L',0);
        //firma empresa
        $pdf->SetXY(10, 265);//firma trabajador
        $this->SetFont('', 'B', 9);
        $pdf->Cell(35, 5, 'FIRMA AUTORIZADO: ____________________________________________________', 0, 0, 'L',0);
        $pdf->SetXY(10, 270);
        $pdf->Cell(35, 5, 'NIT/CC.:', 0, 0, 'L',0);
    }

    function Footer() {

        $this->SetFont('Arial', '', 7);
        $this->Text(10, 290, utf8_decode('Nuestra compañía, en favor del medio ambiente.'));
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}
global $id_entrega;
$id_entrega = $model->id_entrega;
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("EntregaDevolucion_$model->numero_entrega.pdf", 'D');

exit;
