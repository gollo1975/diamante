<?php

use inquid\pdf\FPDF;
use app\models\SolicitudCompra;
use app\models\SolicitudCompraDetalles;
use app\models\MatriculaEmpresa;
use app\models\Municipios;
use app\models\Departamentos;

class PDF extends FPDF {

    function Header() {
        $id_solicitud = $GLOBALS['id_solicitud'];
        $solicitud = SolicitudCompra::findOne($id_solicitud);
        $config = MatriculaEmpresa::findOne(1);
        $municipio = Municipios::findOne($config->codigo_municipio);
        $departamento = Departamentos::findOne($config->codigo_departamento);        
        //Logo
        //Logo
        $this->SetXY(43, 10);
        $this->Image('dist/images/logos/logoempresa.png', 10, 10, 30, 19);
        //Encabezado
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(70, 9);
        $this->SetFont('Arial', '', 10);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("EMPRESA:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->razon_social_completa), 0, 0, 'L', 1);
        $this->SetXY(30, 5);
        //FIN
        $this->SetXY(70, 13);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("NIT:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->nit_empresa." - ".$config->dv), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(70, 17);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("DIRECCION:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->direccion), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(70, 21);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("TELEFONO:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->telefono), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(70, 25);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("MUNICIPIO:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->codigoMunicipio->municipio." - ".$config->codigoDepartamento->departamento), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
       $this->SetXY(10, 29);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
         $this->SetXY(10, 30);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
        //Programación Nomina
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(10, 36);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(162, 7, utf8_decode("SOLICITUD DE COMPRA"), 0, 0, 'l', 0);
        $this->Cell(30, 7, utf8_decode('N°. '.str_pad($solicitud->numero_solicitud, 4, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
       // $this->SetFillColor(300, 300, 300);
        //INICIO
        $this->SetXY(10, 44); //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(24, 5, utf8_decode("TIPO SOLICITUD:"), 0, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 5, utf8_decode($solicitud->solicitud->descripcion), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(22, 5, utf8_decode("AREA:"), 0, 0, 'J');
        $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($solicitud->area->descripcion), 0, 0, 'J');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("SUBTOTAL:"), 0, 0, 'J');
        $this->SetFont('Arial', '', 8);
        $this->Cell(15, 5, utf8_decode($solicitud->subtotal), 0, 0, 'R');
        //FIN
        $this->SetXY(10, 48); //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(24, 5, utf8_decode("F. PROCESO:"), 0, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 5, utf8_decode($solicitud->fecha_entrega), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(22, 5, utf8_decode("F. CREACION:"), 0, 0, 'J');
        $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($solicitud->fecha_creacion), 0, 0, 'J');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("IMPUESTO:"), 0, 0, 'J');
        $this->SetFont('Arial', '', 8);
        $this->Cell(15, 5, utf8_decode(''. number_format($solicitud->total_impuesto,0)), 0, 0, 'R');
        //FIN
        $this->SetXY(10, 52); //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(24, 5, utf8_decode("AUTORIZADO:"), 0, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 5, utf8_decode($solicitud->autorizadoCompra), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(22, 5, utf8_decode("USER NAME:"), 0, 0, 'J');
        $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($solicitud->user_name), 0, 0, 'J');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("TOTAL:"), 0, 0, 'J');
        $this->SetFont('Arial', '', 8);
        $this->Cell(15, 5, utf8_decode(''. number_format($solicitud->total,0)), 0, 0, 'R');
        //FIN
       
        //Lineas del encabezado
        $this->Line(10, 64, 10, 140);//x1,y1,x2,y2        
        $this->Line(130, 64, 130, 140);
        $this->Line(200, 64, 200, 164);
        $this->Line(10, 140, 200, 140); //linea horizontal inferior x1,y1,x2,y2
        //Linea de las observacines
        $this->Line(10, 90, 10, 164); //linea vertical
         $this->Line(10, 164, 200, 164); //linea horizontal inferior x1,y1,x2,y2
        //Detalle factura
        $this->EncabezadoDetalles();
    }

    function EncabezadoDetalles() {
        $this->Ln(7);
        $header = array('DESCRIPCION', 'CANTIDAD');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(120, 70);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 5, $header[$i], 1, 0, 'C', 1);
            else
                $this->Cell($w[$i], 5, $header[$i], 1, 0, 'C', 1);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(5);
    }

    function Body($pdf, $model) {
        $detalles = SolicitudCompraDetalles::find()->where(['=', 'id_solicitud_compra', $model->id_solicitud_compra])->all();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        $items = count($detalles);
        foreach ($detalles as $detalle) {
            $pdf->Cell(120, 5, $detalle->items->descripcion, 0, 0, 'J');          
            $pdf->Cell(70, 5, $detalle->cantidad, 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);
        }
        $this->SetFillColor(200, 200, 200);
        $pdf->SetXY(10, 140);
        $this->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(60, 8, 'ITEMS: '.$items, 1, 'J');
        
        $pdf->SetXY(30, 140);
        $this->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(80, 8, 'SUBTOTAL: '.number_format($model->subtotal,0), 1, 'R');
        $pdf->SetXY(50, 140);
        $this->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(100, 8, ' IMPUESTO: '.number_format($model->total_impuesto,0), 1, 'R');
        $pdf->SetXY(70, 140);
        $this->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(130, 8, ' TOTAL: '.number_format($model->total,0), 1, 'R');
       //LIENA DE OBSERVACION
        $pdf->SetXY(10, 149);
        $this->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(29, 6, 'Observacion:', 0, 'J');
        $pdf->SetXY(38, 150);
        $this->SetFont('Arial', '', 8);
        $pdf->MultiCell(112, 4, utf8_decode($model->observacion), 0, 'J');
        
        	//firma trabajador
        $pdf->SetXY(10, 230);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(35, 5, 'FIRMA PREPARADOR: ___________________________________________________', 0, 0, 'L',0);
        $pdf->SetXY(10, 235);
        $pdf->Cell(35, 5, 'C.C.:', 0, 0, 'L',0);
        //firma empresa
        $pdf->SetXY(10, 255);//firma trabajador
        $this->SetFont('', 'B', 9);
        $pdf->Cell(35, 5, 'FIRMA AUTORIZADO: ____________________________________________________', 0, 0, 'L',0);
        $pdf->SetXY(10, 260);
        $pdf->Cell(35, 5, 'NIT/CC.:', 0, 0, 'L',0);
    }

    function Footer() {

        $this->SetFont('Arial', '', 8);
        $this->Text(10, 290, utf8_decode('Nuestra compañía, en favor del medio ambiente.'));
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}

global $id_solicitud;
$id_solicitud = $model->id_solicitud_compra;
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf, $model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("SolicitudCompra$model->numero_solicitud.pdf", 'D');

exit;

function zero_fill($valor, $long = 0) {
    return str_pad($valor, $long, '0', STR_PAD_LEFT);
}


