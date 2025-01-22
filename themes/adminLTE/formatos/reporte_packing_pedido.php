<?php

use inquid\pdf\FPDF;
use app\models\PackingPedido;
use app\models\PackingPedidoDetalle;
use app\models\MatriculaEmpresa;
use app\models\Municipios;
use app\models\Departamentos;

class PDF extends FPDF {

    function Header() {
        $id_packing = $GLOBALS['id_packing'];
        $packing = PackingPedido::findOne($id_packing);
        $config = MatriculaEmpresa::findOne(1);
        $municipio = Municipios::findOne($config->codigo_municipio);
        $departamento = Departamentos::findOne($config->codigo_departamento);
        //Logo
        $this->SetXY(43, 10);
        $this->Image('dist/images/logos/logoempresa.png', 10, 10, 30, 30);
        //Encabezado
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(53, 9);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("Empresa:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->razon_social_completa), 0, 0, 'L', 1);
        $this->SetXY(30, 5);
        //FIN
        $this->SetXY(53, 13);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("Nit:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->nit_empresa . " - " . $config->dv), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 17);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("Dirección:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->direccion), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 21);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("Teléfono:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->telefono), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 25);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("Municipio:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->codigoMunicipio->municipio . " - " . $config->codigoDepartamento->departamento), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(10, 28);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
        $this->SetXY(10, 28.5);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
        //Prestaciones sociales
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(10, 34);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(162, 7, utf8_decode("NUMERO PACKING"), 0, 0, 'l', 0);
        $this->Cell(30, 7, utf8_decode('N°. ' . str_pad($packing->numero_packing, 5, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
        $this->SetFillColor(200, 200, 200);
        //FIN
        $this->SetXY(10, 43);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Nit:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(80, 5, utf8_decode($packing->nit_cedula_cliente . '-' . $packing->clientePacking->dv), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("Cliente:"), 0, 0, 'c', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(69, 5, utf8_decode($packing->cliente), 0, 0, 'c', 1);
        //FIN
        $this->SetXY(10, 47);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Departamento:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(80, 5, utf8_decode($packing->clientePacking->codigoDepartamento->departamento), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("Municipio:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(69, 5, utf8_decode($packing->clientePacking->codigoMunicipio->municipio), 0, 0, 'L', 1);
        //FIN
        $this->SetXY(10, 51);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Fecha packing:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(80, 5, utf8_decode($packing->fecha_creacion), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("Unidades:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(69, 5, utf8_decode($packing->total_unidades_packing), 0, 0, 'L', 1);
        // FIN
        $this->SetXY(10, 55);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Total cajas:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(80, 5, utf8_decode($packing->total_cajas), 0, 0, 'l', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(17, 5, utf8_decode("Transportadora:"), 0, 0, 'J', 1);
        $this->SetFont('Arial', '', 8);
        if($packing->id_transportadora <> ''){
            $this->Cell(69, 5, utf8_decode($packing->transportadora->razon_social), 0, 0, 'L', 1);
        }else{
           $this->Cell(69, 5, utf8_decode('NO FOUND'), 0, 0, 'L', 1); 
        }    
        //FIN
        //Lineas del encabezado
        $this->Line(10, 64, 10, 248);
        $this->Line(27, 64, 27, 248);
        $this->Line(107, 64, 107, 248);
        $this->Line(127, 64, 127, 248);
        $this->Line(164, 64, 164, 248);
        $this->Line(202, 64, 202, 248);
        //Cuadro de la nota
        $this->Line(10, 248, 202, 248); //linea horizontal superior
        $this->Line(10, 248, 202, 248); //linea horizontal inferior
        //Linea de las observacines
     
        //lineas para los cuadros de nit/cc,fecha,firma        
        //Detalle factura
        $this->EncabezadoDetalles();
    }

    function EncabezadoDetalles() {
        $this->Ln(7);
        $header = array('CODIGO', 'PRESENTACION PRODUCTO', 'CANTIDAD', 'NRO CAJA', 'NRO GUIA');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(17, 80, 20, 37, 38);
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

    function Body($pdf, $model) {
        $config = MatriculaEmpresa::findOne(1);
        $detalles = PackingPedidoDetalle::find()->where(['=', 'id_packing', $model->id_packing])->orderBy('numero_caja ASC')->all();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $cant = 0;  $contador = 0;
        foreach ($detalles as $detalle) {
            $contador +=1;
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(17, 4, $detalle->codigo_producto, 0, 0, 'L');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(80, 4, utf8_decode($detalle->nombre_producto), 0, 0, 'L');
            $pdf->Cell(20, 4, $detalle->cantidad_despachada, 0, 0, 'R');
            $pdf->Cell(37, 4, $detalle->numero_caja, 0, 0, 'R');
            $pdf->Cell(38, 4, $detalle->numero_guia, 0, 0, 'L');
            $pdf->Ln();
            //$pdf->SetAutoPageBreak(true, 20);
            $cant += $detalle->cantidad_despachada;
            if ($contador % 40 == 0) {
                $pdf->AddPage();
                $this->Line(10,240,10,200);
            }
            
        }
        if (!$contador % 40 == 0) {
             $this->Line(10,240,10,200);
        }
       
     
        $pdf->SetXY(10, 240);
        $pdf->SetXY(107, 240);
        $pdf->MultiCell(20, 8, 'CANTIDAD: ' . $cant, 1, 'L', 0);

        $pdf->SetXY(10, 265); //firma trabajador
        $this->SetFont('', 'B', 9);
        $pdf->Cell(35, 5, 'FIRMA TRANSPORTADORA: ____________________________________________________', 0, 0, 'L', 0);
        $pdf->SetXY(10, 270);
        $pdf->Cell(35, 5, 'NIT/CC.:', 0, 0, 'L', 0);
    }

    function Footer() {

        $this->SetFont('Arial', '', 8);
        $this->Text(10, 290, utf8_decode('Nuestra compañía, en favor del medio ambiente.'));
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}

global $id_packing;
$id_packing = $model->id_packing;
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf, $model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("PackingPedido_$model->numero_packing.pdf", 'D');

exit;

function zero_fill($valor, $long = 0) {
    return str_pad($valor, $long, '0', STR_PAD_LEFT);
}
