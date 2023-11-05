<?php

use inquid\pdf\FPDF;
use app\models\DevolucionProductos;
use app\models\DevolucionProductoDetalle;
use app\models\MatriculaEmpresa;
use app\models\Municipios;
use app\models\Departamentos;

class PDF extends FPDF {

    function Header() {
        $id_devolucion = $GLOBALS['id_devolucion'];
        $devolucion = DevolucionProductos::findOne($id_devolucion);
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
        $this->Cell(30, 5, utf8_decode("Empresa:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->razon_social_completa), 0, 0, 'L', 1);
        $this->SetXY(30, 5);
        //FIN
        $this->SetXY(53, 13);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("Nit:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 8);
        $this->Cell(40, 5, utf8_decode($config->nit_empresa." - ".$config->dv), 0, 0, 'L', 1);
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
        $this->Cell(40, 5, utf8_decode($config->codigoMunicipio->municipio." - ".$config->codigoDepartamento->departamento), 0, 0, 'L', 1);
        $this->SetXY(40, 5);

        //FIN
        $this->SetXY(10, 32);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
         $this->SetXY(10, 32.5);
        $this->Cell(190, 7, utf8_decode("_________________________________________________________________________________________________________________________________________"), 0, 0, 'C', 0);
        //Prestaciones sociales
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(10, 39);
        $this->SetFont('Arial', 'B', 13);
        $this->Cell(162, 7, utf8_decode("DEVOLUCION DE PRODUCTOS"), 0, 0, 'l', 0);
        $this->Cell(30, 7, utf8_decode('N°. '.str_pad($devolucion->numero_devolucion, 5, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
       // $this->SetFillColor(200, 200, 200);
        $this->SetXY(10, 48);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(21, 5, utf8_decode("Documento:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 5, utf8_decode($devolucion->cliente->nit_cedula), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Cliente:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(57, 5, utf8_decode($devolucion->cliente->nombre_completo), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(39, 5, utf8_decode("Cant. Inventario:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(19, 5, ' '. number_format($devolucion->cantidad_inventario, 0), 0, 0, 'R', 1);
        //FIN
       $this->SetXY(10, 52);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(21, 5, utf8_decode("Autorizado:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 5, utf8_decode($devolucion->autorizadoProceso), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Fecha Devolución:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(57, 5, utf8_decode($devolucion->fecha_devolucion), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(39, 5, utf8_decode("Cant. Averias:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(19, 5, ' '. number_format($devolucion->cantidad_averias, 0), 0, 0, 'R', 1);
        //FIN
        $this->SetXY(10, 56);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(21, 5, utf8_decode("Usuario:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 5, utf8_decode($devolucion->user_name), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Fecha Registro:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(57, 5, utf8_decode($devolucion->fecha_registro), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(39, 5, utf8_decode("Nota credito:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(19, 5, $devolucion->nota->numero_nota_credito, 0, 0, 'R', 1);
        //FIN
         //FIN
        $this->SetXY(10, 60);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(21, 5, utf8_decode("Observacion:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(171, 5, $devolucion->observacion, 0, 0, 'L', 1);
        //FIN
        //FIN
              
        $this->EncabezadoDetalles();
     
        //Lineas del encabezado
        $this->Line(10,70,10,200);
        $this->Line(33,70,33,200);
        $this->Line(96,70,96,200);
        $this->Line(121,70,121,200);
        $this->Line(146,70,146,200);
        $this->Line(202,70,202,200);
        $this->Line(10,200,202,200);//linea horizontal inferior  
     
    }
    function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('CODIGO', ('PRODUCTO'), ('CANT. INV.'), ('CANT. AVERIAS'), ('TIPO DEVOLUCION'));
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(23, 63, 25, 25, 56);
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
        $detalle = DevolucionProductoDetalle::find()->where(['=','id_devolucion', $model->id_devolucion])->all();		
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
		
        foreach ($detalle as $val) {                                                           
            $pdf->Cell(23, 4, $val->codigo_producto, 0, 0, 'L');
            $pdf->Cell(63, 4, utf8_decode($val->nombre_producto), 0, 0, 'L');
            $pdf->Cell(25, 4, $val->cantidad_devolver, 0, 0, 'R');
            $pdf->Cell(25, 4, $val->cantidad_averias, 0, 0, 'R');
            $pdf->Cell(66, 4, utf8_decode($val->tipoDevolucion->concepto), 0, 0, 'L');
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
global $id_devolucion;
$id_devolucion = $model->id_devolucion;
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("Devolucion_producto_$model->numero_devolucion.pdf", 'D');

exit;
