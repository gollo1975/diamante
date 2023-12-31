<?php

use inquid\pdf\FPDF;
use app\models\OrdenProduccion;
use app\models\OrdenProduccionProductos;
use app\models\OrdenProduccionMateriaPrima;
use app\models\MatriculaEmpresa;
use app\models\Municipios;
use app\models\Departamentos;

class PDF extends FPDF {

    function Header() {
        $id_orden_produccion = $GLOBALS['id_orden_produccion'];
        $orden = OrdenProduccion::findOne($id_orden_produccion);
        $config = MatriculaEmpresa::findOne(1);
        $municipio = Municipios::findOne($config->codigo_municipio);
        $departamento = Departamentos::findOne($config->codigo_departamento);
        //Logo
       $this->SetXY(43, 10);
        $this->Image('dist/images/logos/logoempresa.png', 10, 10, 30, 19);
        //Encabezado
        $this->SetFillColor(220, 220, 220);
        $this->SetXY(53, 9);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("EMPRESA:"), 0, 0, 'l', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($config->razon_social_completa), 0, 0, 'L', 1);
        $this->SetXY(30, 5);
        //FIN
        $this->SetXY(53, 13);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("NIT:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($config->nit_empresa." - ".$config->dv), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 17);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("DIRECCION:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($config->direccion), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 21);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("TELEFONO:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 7);
        $this->Cell(40, 5, utf8_decode($config->telefono), 0, 0, 'L', 1);
        $this->SetXY(40, 5);
        //FIN
        $this->SetXY(53, 25);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, utf8_decode("MUNICIPIO:"), 0, 0, 'l', 1);
         $this->SetFont('Arial', '', 7);
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
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(162, 7, utf8_decode("ORDEN DE PRODUCCION"), 0, 0, 'l', 0);
        $this->Cell(30, 7, utf8_decode('N°. '.str_pad($orden->numero_orden, 5, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
       // $this->SetFillColor(200, 200, 200);
        $this->SetXY(10, 48);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("TIPO ORDEN:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(24, 5, utf8_decode($orden->tipoOrden), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(26, 5, utf8_decode("GRUPO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, utf8_decode($orden->grupo->nombre_grupo), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("LOTE:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(10, 5, utf8_decode($orden->numero_lote), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(22, 5, utf8_decode("UNIDADES:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 5, '$ '. number_format($orden->unidades, 0), 0, 0, 'R', 1);
        //FIN
        $this->SetXY(10, 52);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("AUTORIZADO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(24, 5, utf8_decode($orden->user_name), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(26, 5, utf8_decode("ALMACEN:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, utf8_decode($orden->almacen->almacen), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("CERRADO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 5, utf8_decode($orden->cerrarOrden), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(17, 5, utf8_decode("C. UNITARIO:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 5, ''. number_format($orden->costo_unitario,0), 0, 0, 'R', 1);
        //FIN
        $this->SetXY(10, 56);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode("F. PROCESO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(24, 5, utf8_decode($orden->fecha_proceso), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(26, 5, utf8_decode("F. REGISTRO:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, utf8_decode($orden->fecha_registro), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 5, utf8_decode("F. ENTREGA:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 5, utf8_decode($orden->fecha_entrega), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(17, 5, utf8_decode("USER:"), 0, 0, 'R', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 5, utf8_decode($orden->autorizadoOrden), 0, 0, 'L', 1);
       
        //FIN
         $this->EncabezadoDetalles();
     
        //Lineas del encabezado
        $this->Line(10,70,10,102);
        $this->Line(33,70,33,102);
        $this->Line(96,70,96,102);
        $this->Line(119,70,119,102);
        $this->Line(147,70,147,102);
        $this->Line(177,70,177,102);
        $this->Line(202,70,202,102);
        $this->Line(10,102,202,102);//linea horizontal inferior  
        //Líneas creditos
        $this->Line(10,118,10,180);
        $this->Line(33,118,33,180);
        $this->Line(133,118,133,180);
        $this->Line(168,118,168,180);
        $this->Line(202,118,202,180);
        $this->Line(10,180,202,180);//linea horizontal inferior
        
      
    }
    function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('CODIGO', ('PRODUCTO'), ('CANTIDAD'), ('No LOTE'), ('MEDIDA'),('FECHA VCTO'));
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(23, 63, 23, 28, 30, 25);
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
        $materiales = OrdenProduccionMateriaPrima::find()->where(['=','id_orden_produccion',$model->id_orden_produccion])->orderBy('id_materia_prima asc')->all();
        $producto = OrdenProduccionProductos::find()->where(['=','id_orden_produccion', $model->id_orden_produccion])->orderBy('descripcion asc')->all();		
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
		
        foreach ($producto as $detalle) {                                                           
            $pdf->Cell(23, 4, $detalle->codigo_producto, 0, 0, 'L');
            $pdf->Cell(63, 4, utf8_decode($detalle->descripcion), 0, 0, 'L');
            $pdf->Cell(23, 4, $detalle->cantidad, 0, 0, 'C');
            $pdf->Cell(28, 4, $detalle->numero_lote, 0, 0, 'C');
            $pdf->Cell(30, 4, $detalle->medidaProducto->descripcion, 0, 0, 'C');
            $pdf->Cell(25, 4, $detalle->fecha_vencimiento, 0, 0, 'C');	
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);                              
        }
	$this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //productos
        $pdf->SetXY(10, 63);
        $pdf->Cell(192, 5, 'LISTADO DE PRODUCTOS', 1, 0, 'C',1);
        //materiales
        $pdf->SetXY(10, 109);
        $this->SetFont('', 'B', 7);
        $pdf->Cell(192, 5, utf8_decode('LISTADO DE MATERIA PRIMA'), 1, 0, 'C',1);
        $pdf->SetXY(10, 114);
        $pdf->Cell(23, 4, 'CODIGO', 1, 0, 'C',1);
        $pdf->Cell(100, 4, 'MATERIA PRIMA', 1, 0, 'C',1);
        $pdf->Cell(35, 4, utf8_decode('CANTIDAD'), 1, 0, 'C',1);
        $pdf->Cell(34, 4, utf8_decode('MEDIDA'), 1, 0, 'C',1);
        $pdf->SetXY(10, 119);
        $pdf->SetFont('Arial', '', 7);
        foreach ($materiales as $detalle) {                                    
            $pdf->Cell(23, 4, $detalle->materiaPrima->codigo_materia_prima, 0, 0, 'L');            
            $pdf->Cell(100, 4, utf8_decode($detalle->materiaPrima->materia_prima), 0, 0, 'L');
            $pdf->Cell(35, 4, ' '.number_format($detalle->cantidad, 0), 0, 0, 'R');
            $pdf->Cell(34, 4, utf8_decode($detalle->materiaPrima->medida->descripcion), 0, 0, 'C');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);                              
        }
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
global $id_orden_produccion;
$id_orden_produccion = $model->id_orden_produccion;
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("Orden_produccion$model->id_orden_produccion.pdf", 'D');

exit;
