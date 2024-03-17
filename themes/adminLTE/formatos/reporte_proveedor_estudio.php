<?php

use inquid\pdf\FPDF;
use app\models\ProveedorEstudios;
use app\models\ProveedorEstudioDetalles;
use app\models\Municipios;
use app\models\Departamentos;
use app\models\MatriculaEmpresa;

class PDF extends FPDF {

    function Header() {
        $id_estudio = $GLOBALS['id_estudio'];
        $estudio = ProveedorEstudios::findOne($id_estudio);
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
        $this->Cell(162, 7, utf8_decode("ESTUDIO DE PROVEEDOR"), 0, 0, 'l', 0);
        $this->Cell(30, 7, utf8_decode('N°. '.str_pad($estudio->id_estudio, 5, "0", STR_PAD_LEFT)), 0, 0, 'l', 0);
       // $this->SetFillColor(200, 200, 200);
        $this->SetXY(10, 48);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("Nit/Cedula:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(24, 5, utf8_decode($estudio->nit_cedula), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Proveedor:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, utf8_decode($estudio->nombre_completo), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, utf8_decode("Validado:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(10, 5, utf8_decode($estudio->validadoEstudio), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(22, 5, utf8_decode("Aprobado:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 5, utf8_encode($estudio->aprobadoEstudio), 0, 0, 'L', 1);
        //FIN
        $this->SetXY(10, 52);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, utf8_decode("User:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(24, 5, utf8_decode($estudio->user_name), 0, 0, 'L',1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(26, 5, utf8_decode("Fecha registro:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 5, utf8_decode($estudio->fecha_registro), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, utf8_decode("Porcentaje:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(10, 5, utf8_decode($estudio->total_porcentaje), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(22, 5, utf8_decode("Cerrado:"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 5, utf8_decode($estudio->procesoCerrado), 0, 0, 'L', 1);
        //FIN
        //FIN
         $this->EncabezadoDetalles();
     
        //Lineas del encabezado
        $this->Line(10,65,10,192);
        $this->Line(23,65,23,192);
        $this->Line(82,65,82,192);
        $this->Line(92,65,92,192);
        $this->Line(106,65,106,192);
        $this->Line(120,65,120,192);
        $this->Line(138,65,138,192);
        $this->Line(152,65,152,192);
        $this->Line(202,65,202,192);
        $this->Line(10,192,202,192);//linea horizontal inferior  
        //Líneas creditos
      
    }
    function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('CODIGO', ('REQUISITO'), ('%'), ('APLICA'), ('FISICO'),('VALIDADO'),('CUMPLE'), ('NOTA'));
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(13, 59, 10, 14, 14, 18, 14, 50);
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
        $detalles = ProveedorEstudioDetalles::find()->where(['=','id_estudio',$model->id_estudio])->orderBy('id_requisito asc')->all();

        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
		
        foreach ($detalles as $detalle) {                                                           
            $pdf->Cell(13, 4, $detalle->id_requisito, 0, 0, 'L');
            $pdf->Cell(60, 4, utf8_decode($detalle->requisito), 0, 0, 'L');
            $pdf->Cell(10, 4, $detalle->porcentaje, 0, 0, 'C');
            $pdf->Cell(14, 4, $detalle->aplicaEstudio, 0, 0, 'C');
            $pdf->Cell(14, 4, $detalle->documentoFisico, 0, 0, 'C');
            $pdf->Cell(18, 4, $detalle->validadoEstudio, 0, 0, 'C');	
            $pdf->Cell(14, 4, $detalle->cumpleRequisito, 0, 0, 'C');
            $pdf->Cell(50, 4, $detalle->observacion, 0, 0, 'L');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 20);                              
        }
	$this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //productos
        $pdf->SetXY(10, 59);
        $pdf->Cell(192, 5, 'LISTADO DE REQUISITOS', 1, 0, 'C',1);
        //materiales
        $pdf->SetXY(10, 200);
        $this->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(29, 6, 'Observacion:', 0, 'J');
        $pdf->SetXY(38, 201);
        $this->SetFont('Arial', '', 10);
        $pdf->MultiCell(112, 4, utf8_decode($model->observacion), 0, 'J');
        
	//firma trabajador
        $pdf->SetXY(10, 240);
        $this->SetFont('', 'B', 9);
        $pdf->Cell(35, 5, 'FIRMA QUIEN PROCESA: ________________________________________________', 0, 0, 'L',0);
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
global $id_estudio;
$id_estudio = $model->id_estudio;
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("EstudioProveedor_$model->id_estudio.pdf", 'D');

exit;
