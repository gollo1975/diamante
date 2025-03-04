<?php

use inquid\pdf\FPDF;
use app\models\SolicitudMateriales;

class PDF extends FPDF {

    function Header() {
        $codigo = $GLOBALS['codigo'];
        $solicitud = SolicitudMateriales::findOne($codigo);
        //Logo
       $this->SetXY(43, 10);
        $this->Image('dist/images/logos/logoempresa.png', 10, 10, 30, 19);
        //Encabezado
        

        //FIN
        $this->SetXY(10, 32);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
         $this->SetXY(10, 32.5);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
        //Prestaciones sociales
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(10, 39);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(162, 7, utf8_decode("SOLICITUD DE MATERIALES"), 0, 0, 'l', 0);
        $this->Cell(30, 7, utf8_decode('N°. '.str_pad($solicitud->numero_solicitud, 5, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
       // $this->SetFillColor(200, 200, 200);
        $this->SetXY(10, 48);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("TIPO MATERIAL:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(32, 5, utf8_decode($solicitud->solicitud->descripcion), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(23, 5, utf8_decode("PRODUCTO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(60, 5, utf8_decode($solicitud->ordenProduccion->producto->nombre_producto), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("NUMERO LOTE:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(27, 5, $solicitud->numero_lote, 0, 0, 'R', 1);
        //FIN
        $this->SetXY(10, 52);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("O PRODUCCION:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(32, 5, utf8_decode($solicitud->numero_orden_produccion), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(23, 5, utf8_decode("USER NAME:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(60, 5, utf8_decode($solicitud->user_name), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("UNIDADES:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(27, 5, $solicitud->unidades, 0, 0, 'R', 1);
        //FIN
        $this->SetXY(10, 56);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("F. CIERRE:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(32, 5, utf8_decode($solicitud->fecha_cierre), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(23, 5, utf8_decode("F. PROCESO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(60, 5, utf8_decode($solicitud->fecha_hora_registro), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("CERRADO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(27, 5, $solicitud->cerrarSolicitud, 0, 0, 'L', 1);
        //FIN
        $this->SetXY(10, 60);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("TAMAÑO LOTE:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(167, 5, utf8_decode($solicitud->ordenProduccion->tamano_lote), 0, 0, 'L',1);
        
        $this->EncabezadoDetalles();
                 
         
        //linea del llogo
        $this->Line(10,8,202,8);//linea superior horizontal
        $this->Line(10,30,10,8);//primera linea en y
        $this->Line(45,30,45,8);//segunda linea en y
        $this->Line(130,30,130,8);//tercera linea en y
        $this->Line(202,30,202,8);//cuarta linea en y
        $this->Line(10,30,202,30);//linea inferior horizontal
       
        //Lineas del encabezado
        $this->Line(10,74,10,125);
        $this->Line(70,74,70,125);
        $this->Line(130,74,130,125);
        $this->Line(166,74,166,125);
        $this->Line(202,74,202,125);
        $this->Line(10,125,202,125);//linea horizontal inferior  
    }
    function EncabezadoDetalles() {
        $this->Ln(10);
        $header = array('NOMBRE DEL MATERIAl', 'PRESENTACION',('U. REQUERIDAS'),('U. SOLICITADAS'));
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(60, 60 ,36, 36);
        for ($i = 0; $i < count($header); $i++){
            if ($i == 0 || $i == 1){
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            } else {
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            }
        }    
        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(5);
    }        
    function Body($pdf,$model) {        
      
        $detalles = app\models\SolicitudMaterialesDetalle::find()->where(['=','codigo', $model->codigo])->all();		
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
	foreach ($detalles as $detalle) {                                                           
            $pdf->Cell(60, 4, utf8_decode($detalle->materiales), 0, 0, 'L');
             $pdf->Cell(60, 4, utf8_decode($detalle->ordenPresentacion->descripcion), 0, 0, 'L');
            $pdf->Cell(36, 4, utf8_decode(''.number_format($detalle->unidades_lote,0)), 0, 0, 'R');
            $pdf->Cell(36, 4, utf8_decode(''.number_format($detalle->unidades_requeridas,0)), 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);                              
        }
	$pdf->SetXY(10, 135);
        $this->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(146, 4, utf8_decode('OBSERVACION: '.$model->observacion),0,'J');
	//firma trabajador
        $pdf->SetXY(10, 160);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(35, 5, '________________________________', 0, 0, 'L',0);
         $pdf->SetXY(10, 165);
        $pdf->Cell(35, 5, 'OPERARIO DE PRODUCCION', 0, 0, 'L',0);
        $pdf->SetXY(10, 170);
        $pdf->Cell(35, 5, utf8_decode('Recibe'), 0, 0, 'L',0);
        // SEGUNDA FIRMA
        $pdf->SetXY(120, 160);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(120, 5, '________________________________', 0, 0, 'L',0);
        $pdf->SetXY(120, 165);
        $pdf->Cell(120, 5, 'OPERARIO DE PRODUCCION', 0, 0, 'L',0);
        $pdf->SetXY(120, 170);
        $pdf->Cell(120, 5, utf8_decode('Solicita'), 0, 0, 'L',0);
        //liena
        //linea
        $pdf->SetXY(75, 10);
        $pdf->Cell(20, 5, 'GESTION DE PRODUCCION', 0, 0, 'C',0);
        $this->SetXY(44, 13);
        $pdf->Cell(125, 5, '_________________________________________________________________________________________', 0, 0, 'L',0);
        //segunda linea
        $pdf->SetXY(130, 10);
        $pdf->Cell(120, 5, 'Codigo: GP-F-010', 0, 0, 'L',0);
        $this->SetXY(129, 20);
        $pdf->Cell(124, 5, '_________________________________________', 0, 0, 'L',0);
        $pdf->SetXY(130, 17);
        $pdf->Cell(120, 5, 'Version: 05', 0, 0, 'L',0);
        $pdf->SetXY(130, 24);
        $pdf->Cell(120, 5, 'Fecha: 04/07/23', 0, 0, 'L',0);
        //SEGUNDO NOMBRE
        $pdf->SetXY(75, 20);
        $pdf->Cell(20, 5, 'REQUERIMIENTO DE MATERIALES', 0, 0, 'C',0);

    }

    function Footer() {

        $this->SetFont('Arial', '', 7);
        $this->Text(10, 290, utf8_decode('Nuestra compañía, en favor del medio ambiente.'));
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}
global $codigo;
$codigo = $model->codigo;
$pdf = new PDF();
$pdf->SetFont('helvetica','',10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("SolicitudMateriales$model->codigo.pdf", 'D');

exit;
