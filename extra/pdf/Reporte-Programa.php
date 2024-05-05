<?php
require('fpdf/fpdf.php');
class PDF extends FPDF
{
// Cabecera de página
function Header()
{   
    $this->SetFont('Arial','B',11);
    $this->Cell(0,5,utf8_decode("Centro de Diseño y Metrologia"),0,0,'C');
    $this->Image('../../images/logito.png',270, 0, 25, 25, 'png');
    $this->Ln(15);
}


// Pie de página
function Footer()
{
    $this->SetY(-15);
    $this->SetFont('Arial','',8);
    $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
}
}

$idprograma = $_GET['IdPrograma']; 

require '../conexion.php';
$query = "SELECT * FROM programas WHERE Status = 1 AND IdPrograma= $idprograma";
$resultado = $link->query($query);

$pdf = new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage('LANDSCAPE', 'A4');
$pdf->SetFont('Arial', 'B', 13);
$pdf->SetTextColor(16,87,97);
$pdf->Cell(0, 5, 'REPORTE PROGRAMA',0 ,0 , 'C');
$pdf->SetDrawColor(61, 174, 233);
$pdf->SetLineWidth(1);
$pdf->line(111, 32, 185, 32);


$pdf->Ln(30); 
$pdf->SetTextColor(40, 40, 40);
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetFontSize(10);
$pdf->SetFillColor(255,255,255);
$pdf->Cell(25,6,'ID', 0, 0,'C',1);
$pdf->Cell(160,6,'NOMBRE', 0, 0,'C',1);
$pdf->Cell(30,6,'TIPO', 0, 0,'C',1);
$pdf->Cell(30,6,'DURACION', 0,0,'C',1);
$pdf->Cell(30,6,'VERSION', 0, 0,'C',1);
$pdf->SetDrawColor(61, 174, 233);
$pdf->SetLineWidth(1);
$pdf->line(10, 65, 287, 65);

$pdf->Ln(11);

while($row = $resultado->fetch_assoc()){
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetFillColor(240,240,240);
    $pdf->SetFontSize(10);
    $pdf->SetDrawColor(255,255,255);
    $pdf->Cell(25,10, $row['Id'],1 , 0, 'C', 1);    
    $pdf->Cell(160,10, $row['Nombre'],1 , 0, 'L', 1);
    $pdf->Cell(30,10, $row['Tipo'],1 , 0, 'C', 1);
    $pdf->Cell(30,10, $row['Duracion'],1 , 0, 'C', 1);
    $pdf->Cell(30,10, $row['Version'],1 , 0, 'C', 1);
}

$pdf->Output('D','Reporte programa.pdf');
?>