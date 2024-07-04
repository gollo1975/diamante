<?php

use inquid\pdf\FPDF;
use app\models\EntradaProductoTerminado;
use app\models\EntradaProductoTerminadoDetalle;
use app\models\MatriculaEmpresa;
use app\models\Municipios;
use app\models\Departamentos;

class PDF extends FPDF {

    function Header() {
        $id_entrada = $GLOBALS['id_entrada'];
        $orden = EntradaProductoTerminado::findOne($id_entrada);
        $config = Matriculaempresa::findOne(1);
        $municipio = Municipios::findOne($config->codigo_municipio);
        $departamento = Departamentos::findOne($config->codigo_departamento);
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
        $this->Cell(162, 7, utf8_decode("ENTRADA PRODUCTO TERMINADO"), 0, 0, 'l', 0);
        $this->Cell(30, 7, utf8_decode('N°. '.str_pad($orden->id_entrada, 6, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
       // $this->SetFillColor(300, 300, 300);
        //fin
        $this->SetXY(10, 44); //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(24, 5, utf8_decode("PROVEEDOR:"), 0, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->Cell(55, 5, utf8_decode($orden->proveedor->nombre_completo), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(24, 5, utf8_decode("TIPO ORDEN:"), 0, 0, 'J');
        if($orden->id_orden_compra == null){
             $this->SetFont('Arial', '', 8);
        $this->Cell(38, 5, utf8_decode('NO FOUND'), 0, 0, 'J');
        }else{
             $this->SetFont('Arial', '', 8);
        $this->Cell(53, 5, utf8_decode($orden->ordenCompra->tipoOrden->descripcion_orden), 0, 0, 'J');
        }
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("SUBTOTAL:"), 0, 0, 'J');
        $this->SetFont('Arial', '', 8);
        $this->Cell(15, 5, (''. number_format($orden->subtotal,0)), 0, 0, 'R');
        //fin
        $this->SetXY(10, 48); //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(24, 5, utf8_decode("F. ENTRADA:"), 0, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->Cell(55, 5, utf8_decode($orden->fecha_proceso), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(24, 5, utf8_decode("TIPO ENTRADA:"), 0, 0, 'J');
        $this->SetFont('Arial', '', 8);
        $this->Cell(53, 5, utf8_decode($orden->tipoEntrada), 0, 0, 'J');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("IMPUESTO:"), 0, 0, 'J');
        $this->SetFont('Arial', '', 8);
        $this->Cell(15, 5, (''. number_format($orden->impuesto,0)), 0, 0, 'R');
        //FIN
        $this->SetXY(10, 52); //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(24, 5, utf8_decode("AUTORIZADO:"), 0, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->Cell(55, 5, utf8_decode($orden->autorizadoCompra), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(24, 5, utf8_decode("No SOPORTE:"), 0, 0, 'J');
        $this->SetFont('Arial', '', 8);
        $this->Cell(53, 5, utf8_decode($orden->numero_soporte), 0, 0, 'J');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("TOTAL:"), 0, 0, 'J');
        $this->SetFont('Arial', '', 8);
        $this->Cell(15, 5, (''. number_format($orden->total_salida,0)), 0, 0, 'R');
        //FIN
        //Lineas del encabezado
        $this->Line(10, 60, 10, 160); //x1,y1,x2,y2        
        $this->Line(27, 60, 27, 160); //x1,y1,x2,y2
        $this->Line(86, 60, 86, 160); //x1,y1,x2,y2
        $this->Line(101, 60, 101, 160); //x1,y1,x2,y2
        $this->Line(126, 60, 126, 160); //x1,y1,x2,y2
        $this->Line(151, 60, 151, 160); //x1,y1,x2,y2
        $this->Line(175, 60, 175, 160); //x1,y1,x2,y2
        $this->Line(200, 60, 200, 160); //x1,y1,x2,y2
        $this->Line(10, 160, 200, 160); //linea horizontal inferior x1,y1,x2,y2
        //Detalle factura
        $this->EncabezadoDetalles();
    }

    function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array(utf8_decode('CODIGO'), utf8_decode('NOMBRE PRODUCTO'),'CANT.', 'VR. UNIT.', 'SUBTOTAL', 'IVA', 'TOTAL');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(17,59,15, 25, 25, 24,25);
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
        $detalles = EntradaProductoTerminadoDetalle::find()->where(['=', 'id_entrada', $model->id_entrada])->all();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        $i = 0;
        foreach ($detalles as $detalle) {
            $i = $i + 1;
            $pdf->Cell(17, 5, $detalle->codigo_producto, 0, 0, 'L');
            $pdf->Cell(59, 5, $detalle->inventario->nombre_producto, 0, 0, 'L');
            $pdf->Cell(15, 5, $detalle->cantidad, 0, 0, 'R');
            $pdf->Cell(25, 5, ''.number_format($detalle->valor_unitario,0), 0, 0, 'R');
            $pdf->Cell(25, 5, '$ '.number_format($detalle->subtotal, 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(24, 5, '$ '.number_format($detalle->total_iva, 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(25, 5, '$ '.number_format($detalle->total_entrada, 0, '.', ','), 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);
        }
        $pdf->SetXY(10, 165);
        $this->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(29, 6, 'Observacion:', 0, 'J');
        $pdf->SetXY(38, 166);
        $this->SetFont('Arial', '', 10);
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

global $id_entrada;
$id_entrada = $model->id_entrada;
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf, $model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("Entrada_Producto_No_$model->id_entrada.pdf", 'D');

exit;

function zero_fill($valor, $long = 0) {
    return str_pad($valor, $long, '0', STR_PAD_LEFT);
}
