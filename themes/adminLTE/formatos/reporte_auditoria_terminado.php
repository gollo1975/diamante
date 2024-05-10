<?php

use inquid\pdf\FPDF;
use app\models\OrdenEnsambleAuditoria;
use app\models\OrdenEnsambleAuditoriaDetalle;
use app\models\OrdenEnsambleProducto;

class PDF extends FPDF {

    function Header() {
        $id_auditoria = $GLOBALS['id_auditoria'];
        $auditoria = OrdenEnsambleAuditoria::findOne($id_auditoria);
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
        $this->Cell(162, 7, utf8_decode("AUDITORIA PRODUCTO TERMINADO"), 0, 0, 'l', 0);
        $this->Cell(30, 7, utf8_decode('N°. '.str_pad($auditoria->numero_auditoria, 5, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
       // $this->SetFillColor(200, 200, 200);
        $this->SetXY(10, 48);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("ETAPA:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(27, 5, utf8_decode($auditoria->etapa), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(23, 5, utf8_decode("GRUPO:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(60, 5, utf8_decode($auditoria->grupo->nombre_grupo), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("NUMERO LOTE:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(32, 5, $auditoria->numero_lote, 0, 0, 'R', 1);
        //FIN
        $this->SetXY(10, 52);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("F. COSMETICA:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(24, 5, utf8_decode($auditoria->forma->concepto), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(26, 5, utf8_decode("USER NAME:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(60, 5, utf8_decode($auditoria->user_name), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("ORDEN PRODUCCION:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(32, 5, $auditoria->numero_orden, 0, 0, 'R', 1);
        //FIN
        $this->SetXY(10, 56);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("F. ANALISIS:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(24, 5, utf8_decode($auditoria->fecha_analisis), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(26, 5, utf8_decode("F. PROCESO:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(60, 5, utf8_decode($auditoria->fecha_proceso), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(33, 5, utf8_decode("CONDICIONES ANALISIS:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(29, 5, $auditoria->condicionAnalisis, 0, 0, 'L', 1);
        //FIN
        $this->SetXY(10, 60);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("TAMAÑO LOTE:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(172, 5, utf8_decode($auditoria->ensamble->ordenProduccion->tamano_lote), 0, 0, 'L',1);
        
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
        $this->Line(74,74,74,125);
        $this->Line(164,74,164,125);
        $this->Line(202,74,202,125);
        $this->Line(10,125,202,125);//linea horizontal inferior  
    }
    function EncabezadoDetalles() {
        $this->Ln(10);
        $header = array('NOMBRE DE ANALISIS', ('ESPECIFICACIONES'),('RESULTADO'));
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(64, 90, 38);
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
      
        $detalles = OrdenEnsambleAuditoriaDetalle::find()->where(['=','id_auditoria', $model->id_auditoria])->all();		
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
	foreach ($detalles as $detalle) {                                                           
            $pdf->Cell(64, 4, utf8_decode($detalle->analisis->concepto), 0, 0, 'L');
            $pdf->Cell(90, 4, utf8_decode($detalle->especificacion->concepto), 0, 0, 'L');
            $pdf->Cell(38, 4, utf8_decode($detalle->resultado), 0, 0, 'L');
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
        $pdf->Cell(35, 5, 'ANALISTA JR CALIDAD', 0, 0, 'L',0);
        $pdf->SetXY(10, 170);
        $pdf->Cell(35, 5, utf8_decode('Realizado'), 0, 0, 'L',0);
        // SEGUNDA FIRMA
        $pdf->SetXY(120, 160);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(120, 5, '________________________________', 0, 0, 'L',0);
        $pdf->SetXY(120, 165);
        $pdf->Cell(120, 5, 'DIRECTOR TECNICO', 0, 0, 'L',0);
        $pdf->SetXY(120, 170);
        $pdf->Cell(120, 5, utf8_decode('Aprobado'), 0, 0, 'L',0);
        //liena
        //linea
        $pdf->SetXY(75, 10);
        $pdf->Cell(20, 5, 'GESTION TECNICA', 0, 0, 'C',0);
        $this->SetXY(44, 13);
        $pdf->Cell(125, 5, '_________________________________________________________________________________________', 0, 0, 'L',0);
        //segunda linea
        $pdf->SetXY(130, 10);
        $pdf->Cell(120, 5, 'Codigo: GTCC-F-003', 0, 0, 'L',0);
        $this->SetXY(129, 20);
        $pdf->Cell(124, 5, '_________________________________________', 0, 0, 'L',0);
        $pdf->SetXY(130, 17);
        $pdf->Cell(120, 5, 'Version: 03', 0, 0, 'L',0);
        $pdf->SetXY(130, 24);
        $pdf->Cell(120, 5, 'Fecha: 22/03/23', 0, 0, 'L',0);
        //SEGUNDO NOMBRE
        $pdf->SetXY(75, 20);
        $pdf->Cell(20, 5, 'CONTROLES EN PRODUCTO EN TERMINADO', 0, 0, 'C',0);

    }

    function Footer() {

        $this->SetFont('Arial', '', 7);
        $this->Text(10, 290, utf8_decode('Nuestra compañía, en favor del medio ambiente.'));
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}
global $id_auditoria;
$id_auditoria = $model->id_auditoria;
$pdf = new PDF();
$pdf->SetFont('helvetica','',10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("AuditoriaTerminado$model->numero_auditoria.pdf", 'D');

exit;
