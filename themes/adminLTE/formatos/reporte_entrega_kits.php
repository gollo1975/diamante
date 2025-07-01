<?php

use inquid\pdf\FPDF;
use app\models\EntregaSolicitudKits;
use app\models\EntregaSolicitudKitsDetalle;

class PDF extends FPDF {

    function Header() {
        $id_solicitud = $GLOBALS['id_solicitud'];
        $solicitud = EntregaSolicitudKits::findOne($id_solicitud);
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
        $this->Cell(162, 7, utf8_decode("ENTREGA PRODUCTOS PARA KITS"), 0, 0, 'l', 0);
        $this->Cell(30, 7, utf8_decode('N°. '.str_pad($solicitud->numero_entrega,5, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
       // $this->SetFillColor(200, 200, 200);
        $this->SetXY(10, 48);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(23, 5, utf8_decode("TIPO SOLICITUD:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(41, 5, utf8_decode($solicitud->solicitud->concepto), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(26, 5, utf8_decode("PRESENTACION:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(68, 5, utf8_decode($solicitud->presentacion->descripcion), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(19, 5, utf8_decode("No KITS:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 5, $solicitud->cantidad_despachada, 0, 0, 'R', 1);
        //FIN
        $this->SetXY(10, 52);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(23, 5, utf8_decode("F. SOLICITUD:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(41, 5, utf8_decode($solicitud->fecha_solicitud), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(26, 5, utf8_decode("F. HORA SOLICITUD:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(68, 5, utf8_decode($solicitud->fecha_hora_proceso), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(23, 5, utf8_decode("U. SOLICITADAS:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(11, 5, $solicitud->total_unidades_entregadas    , 0, 0, 'R', 1);
        //FIN
        $this->SetXY(10, 56);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(23, 5, utf8_decode("USER NAME:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(41, 5, utf8_decode($solicitud->user_name), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(26, 5, utf8_decode("F. HORA CIERRE:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(68, 5, utf8_decode($solicitud->fecha_hora_cierre), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(23, 5, utf8_decode("No SOLICITUD:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(11, 5, utf8_decode($solicitud->solicitudArmado->numero_solicitud), 0, 0, 'L', 1);
        //FIN
        
        $this->EncabezadoDetalles();
                 
         
        //linea del llogo
        $this->Line(10,8,202,8);//linea superior horizontal
        $this->Line(10,30,10,8);//primera linea en y
        $this->Line(45,30,45,8);//segunda linea en y
        $this->Line(130,30,130,8);//tercera linea en y
        $this->Line(202,30,202,8);//cuarta linea en y
        $this->Line(10,30,202,30);//linea inferior horizontal
       
        //Lineas del encabezado
        $this->Line(10,63,10,140);
        $this->Line(30,63,30,140);
        $this->Line(120,63,120,140);
        $this->Line(161,63,161,140);
        $this->Line(202,63,202,140);
        $this->Line(10,140,202,140);//linea horizontal inferior  
    }
    function EncabezadoDetalles() {
        $this->Ln(7);
        $header = array('CODIGO', ('PRESENTACION PRODUCTO'),('No LOTE'), ('CANTIDAD'));
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(20, 90, 41, 41);
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
      
        $detalles = EntregaSolicitudKitsDetalle::find()->where(['=','id_entrega_kits', $model->id_entrega_kits])->all();		
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
	foreach ($detalles as $detalle) {                                                           
            $pdf->Cell(20, 4, utf8_decode($detalle->detalle->inventario->codigo_producto), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(90, 4, utf8_decode(substr($detalle->detalle->inventario->nombre_producto,0,90)), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(41, 4, utf8_decode($detalle->numero_lote), 0, 0, 'L');
            $pdf->Cell(41, 4, ''. number_format($detalle->cantidad_despachada,0), 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);                              
        }
	$pdf->SetXY(10, 150);
        $this->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(146, 4, utf8_decode('OBSERVACION: '.$model->observacion),0,'J');
	//firma trabajador
        $pdf->SetXY(10, 186);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(35, 5, '________________________________', 0, 0, 'L',0);
         $pdf->SetXY(10, 190);
        $pdf->Cell(35, 5, 'DEPTO DE PRODUCCION', 0, 0, 'L',0);
        $pdf->SetXY(10, 195);
        $pdf->Cell(35, 5, utf8_decode('Realizado'), 0, 0, 'L',0);
        // SEGUNDA FIRMA
        $pdf->SetXY(120, 186);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(120, 5, '________________________________', 0, 0, 'L',0);
        $pdf->SetXY(120, 190);
        $pdf->Cell(120, 5, 'DIRECTOR TECNICO', 0, 0, 'L',0);
        $pdf->SetXY(120, 195);
        $pdf->Cell(120, 5, utf8_decode('Aprobado'), 0, 0, 'L',0);
        //liena
        //linea
        $pdf->SetXY(75, 10);
        $pdf->Cell(20, 5, 'GESTION LOGISTICA', 0, 0, 'C',0);
        $this->SetXY(44, 13);
        $pdf->Cell(125, 5, '_________________________________________________________________________________________', 0, 0, 'L',0);
        //segunda linea
        $pdf->SetXY(130, 10);
        $pdf->Cell(120, 5, 'Codigo: LOG-F-003', 0, 0, 'L',0);
        $this->SetXY(129, 20);
        $pdf->Cell(124, 5, '_________________________________________', 0, 0, 'L',0);
        $pdf->SetXY(130, 17);
        $pdf->Cell(1120, 5, 'Version: 01', 0, 0, 'L',0);
        $pdf->SetXY(130, 24);
        $pdf->Cell(120, 5, 'Fecha: 28/06/25', 0, 0, 'L',0);
        //SEGUNDO NOMBRE
        $pdf->SetXY(75, 20);
        $pdf->Cell(20, 5, 'ARMADO DE KITS', 0, 0, 'C',0);

    }

    function Footer() {

        $this->SetFont('Arial', '', 7);
        $this->Text(10, 290, utf8_decode('Nuestra compañía, en favor del medio ambiente.'));
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}
global $id_solicitud;
$id_solicitud = $model->id_entrega_kits;
$pdf = new PDF();
$pdf->SetFont('helvetica','',10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("ReporteEntregaKits_$model->numero_entrega.pdf", 'D');

exit;
