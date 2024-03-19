<?php

use inquid\pdf\FPDF;
use app\models\AuditoriaCompras;
use app\models\AuditoriaCompraDetalles;
use app\models\MatriculaEmpresa;
use app\models\Municipios;
use app\models\Departamentos;

class PDF extends FPDF {

    function Header() {
        $id_auditoria = $GLOBALS['id_auditoria'];
        $orden = AuditoriaCompras::findOne($id_auditoria);
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
        $this->Cell(162, 7, utf8_decode("REPORTE AUDITORIA DE COMPRA"), 0, 0, 'l', 0);
        $this->Cell(30, 7, utf8_decode('N°. '.str_pad($orden->id_auditoria, 4, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
       // $this->SetFillColor(300, 300, 300);
        //fin
        $this->SetXY(10, 44); //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(24, 5, utf8_decode("Proveedor:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 5, utf8_decode($orden->proveedor->nombre_completo), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(22, 5, utf8_decode("Tipo compra:"), 0, 0, 'L','1');
        $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($orden->tipoOrden->descripcion_orden), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("Numero orden:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(21, 5, utf8_decode($orden->numero_orden), 0, 0, 'R', 1);
        //fin
        $this->SetXY(10, 48); //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(24, 5, utf8_decode("F. auditoria:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 5, utf8_decode($orden->fecha_auditoria), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(22, 5, utf8_decode("F. compra:"), 0, 0, 'J', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($orden->fecha_proceso_compra), 0, 0, 'J', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("Factura:"), 0, 0, 'J',1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(21, 5, ($orden->numero_factura), 0, 0, 'R', 1);
        //FIN
        $this->SetXY(10, 52); //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(24, 5, utf8_decode("Usuario:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 5, utf8_decode($orden->user_name), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(22, 5, utf8_decode("Cerrado:"), 0, 0, 'J', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($orden->cerrarAuditoria), 0, 0, 'J', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode(""), 0, 0, 'J',1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(21, 5, utf8_decode(""), 0, 0, 'J', 1);

        //FIN
        //Lineas del encabezado
        $this->Line(10, 65, 10, 192); //x1,y1,x2,y2        
        $this->Line(23, 65, 23, 192); //x1,y1,x2,y2
        $this->Line(63, 65, 63, 192); //x1,y1,x2,y2
        $this->Line(77, 65, 77, 192); //x1,y1,x2,y2
        $this->Line(92, 65, 92, 192); //x1,y1,x2,y2
        $this->Line(106, 65, 106, 192); //x1,y1,x2,y2
        $this->Line(121, 65, 121, 192); //x1,y1,x2,y2
        $this->Line(202, 65, 202, 192); //x1,y1,x2,y2
        $this->Line(10, 192, 202, 192); //linea horizontal inferior x1,y1,x2,y2
        //Detalle factura
        $this->EncabezadoDetalles();
    }

    function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array(utf8_decode('CODIGO'), ('PRODUCTO/SERVICIO'),'CANT.', 'VR. UNIT.', 'N. CANT.', 'N. VALOR', 'OBSERVACION');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(13,40,14, 15, 14, 15, 81);
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
        $detalles = AuditoriaCompraDetalles::find()->where(['=', 'id_auditoria', $model->id_auditoria])->all();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        $i = 0;
        foreach ($detalles as $detalle) {
            $i = $i + 1;
            $pdf->Cell(13, 5, $detalle->id_items, 0, 0, 'L');
            $pdf->Cell(40, 5, $detalle->items->descripcion, 0, 0, 'L');
            $pdf->Cell(14, 5, $detalle->cantidad, 0, 0, 'R');
            $pdf->Cell(15, 5, number_format($detalle->valor_unitario, 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(14, 5, $detalle->nueva_cantidad, 0, 0, 'R');
            $pdf->Cell(15, 5, number_format($detalle->nuevo_valor, 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(81, 5, $detalle->nota, 0, 0, 'L');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);
        }
        
        	//firma trabajador
        $pdf->SetXY(10, 230);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(35, 5, 'FIRMA AUDITOR: ___________________________________________________', 0, 0, 'L',0);
        $pdf->SetXY(10, 235);
        $pdf->Cell(35, 5, 'C.C.:', 0, 0, 'L',0);
        //firma empresa
        $pdf->SetXY(10, 255);//firma trabajador
        $this->SetFont('', 'B', 9);
        $pdf->Cell(35, 5, 'FIRMA RESPONSABLE: _______________________________________________', 0, 0, 'L',0);
        $pdf->SetXY(10, 260);
        $pdf->Cell(35, 5, 'NIT/CC.:', 0, 0, 'L',0);
    }
    
    

    function Footer() {

        $this->SetFont('Arial', '', 8);
        $this->Text(10, 290, utf8_decode('Nuestra compañía, en favor del medio ambiente.'));
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}

global $id_auditoria;
$id_auditoria = $model->id_auditoria;
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf, $model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("AuditoriaCompra_No$model->id_auditoria.pdf", 'D');

exit;

function zero_fill($valor, $long = 0) {
    return str_pad($valor, $long, '0', STR_PAD_LEFT);
}
