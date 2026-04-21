<?php
// 1. Llamamos al controlador que ya hizo los cálculos y validaciones
require_once '../../controllers/CargarPdfMatrizController.php';

// 2. Importamos FPDF
require_once '../../libs/fpdf/fpdf.php';

class PDF extends FPDF {
    function Header() {
        // Logo oficial en la esquina superior izquierda
        if(file_exists('../../public/img/logo.png')) {
            $this->Image('../../public/img/logo.png', 10, 8, 33);
        }
    }
    
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        // Agregada la decodificación correcta para el guion largo y acentos
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb} - Sistema Kluane Inventario',0,0,'C');
    }
}

$pdf = new PDF('L','mm','A4'); // Horizontal (Landscape)
$pdf->AliasNbPages();
$pdf->AddPage();

// --- ENCABEZADO TIPO EXCEL EC-IT-F-09 ---
$pdf->SetFont('Arial','B',16);
// Desplazamos el título a la derecha (X=50) para dejar espacio al logo
$pdf->SetXY(50, 12);
$pdf->Cell(140, 10, 'KLUANE DRILLING ECUADOR S.A.', 0, 0, 'L');

// Cuadro de Código a la derecha
$pdf->SetFont('Arial','B',9);
$pdf->SetXY(240, 10); 
$pdf->MultiCell(40, 5, "EC-IT-F-09\nREV-0\nOCT-2025", 1, 'C');

$pdf->SetXY(10, 28);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0, 10, utf8_decode('MATRIZ DE GESTIÓN DE EQUIPOS TECNOLÓGICOS EN CAMPAMENTO'), 0, 1, 'C');

$pdf->Ln(2);

// --- RESUMEN (KPIs) ---
$pdf->SetFont('Arial','B',10);
$pdf->Cell(30, 6, 'PROYECTO:', 0, 0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(50, 6, utf8_decode($nombre_sede), 0, 1);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(30, 6, 'FECHA IMP:', 0, 0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(50, 6, date('Y-m-d H:i'), 0, 1);

$pdf->Ln(5);

// Cuadros de Resumen (Simulando el Excel)
$pdf->SetFillColor(230, 230, 230);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(40, 6, 'Total Operativo', 1, 0, 'C', true);
$pdf->Cell(40, 6, utf8_decode('Necesita Reparación'), 1, 0, 'C', true);
$pdf->Cell(30, 6, 'Laptops', 1, 0, 'C', true);
$pdf->Cell(30, 6, 'Radios', 1, 1, 'C', true);

$pdf->SetFont('Arial','',10);
$pdf->Cell(40, 8, $total_operativos, 1, 0, 'C');
$pdf->Cell(40, 8, $total_danados, 1, 0, 'C');
$pdf->Cell(30, 8, $conteo_laptops, 1, 0, 'C');
$pdf->Cell(30, 8, $conteo_radios, 1, 1, 'C');

$pdf->Ln(8);

// --- TABLA DE DATOS ---
$pdf->SetFillColor(50, 50, 50); // Fondo Oscuro
$pdf->SetTextColor(255, 255, 255); // Letra Blanca
$pdf->SetFont('Arial','B',9);

// Anchos de columnas (Suma exacta = 270mm, encaja perfecto en A4 Landscape)
$w = array(10, 40, 45, 50, 40, 30, 30, 25);
$header = array('N', 'Equipo', 'Serie', 'Responsable', utf8_decode('Área'), 'Fecha', utf8_decode('Ubicación'), 'Estado');

for($i=0;$i<count($header);$i++) {
    $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
}
$pdf->Ln();

// Restaurar colores para los datos
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','',8);

$fill = false;
$contador = 1;

foreach ($filas as $row) {
    $pdf->Cell($w[0],6,$contador,1,0,'C',$fill);
    $pdf->Cell($w[1],6,utf8_decode(substr($row['equipo'],0,22)),1,0,'L',$fill);
    $pdf->Cell($w[2],6,utf8_decode($row['serie']),1,0,'L',$fill);
    
    // Responsable (recortar si es muy largo)
    $resp = $row['responsable'] ? $row['responsable'] : '-- VACANTE --';
    $pdf->Cell($w[3],6,utf8_decode(substr($resp,0,25)),1,0,'L',$fill);
    
    $pdf->Cell($w[4],6,utf8_decode($row['area']),1,0,'C',$fill);
    $pdf->Cell($w[5],6,$row['fecha_asignacion'],1,0,'C',$fill);
    $pdf->Cell($w[6],6,utf8_decode($row['ubicacion']),1,0,'C',$fill);
    $pdf->Cell($w[7],6,utf8_decode($row['estado']),1,0,'C',$fill);
    
    $pdf->Ln();
    $fill = !$fill; // Alternar color de filas
    $contador++;
}

// Firmas al final
$pdf->Ln(25);
$pdf->SetFont('Arial','B',9);

$pdf->Cell(90, 0, '__________________________', 0, 0, 'C');
$pdf->Cell(90, 0, '__________________________', 0, 0, 'C');
$pdf->Cell(90, 0, '__________________________', 0, 1, 'C');

$pdf->Ln(5);

$pdf->Cell(90, 4, 'ELABORADO POR', 0, 0, 'C');
// Corrección ortográfica: LOGÍSTICA
$pdf->Cell(90, 4, utf8_decode('REVISADO POR (LOGÍSTICA)'), 0, 0, 'C');
$pdf->Cell(90, 4, 'RECIBIDO EN SITIO', 0, 1, 'C');

$pdf->SetFont('Arial','',8);
$pdf->Cell(90, 4, utf8_decode($_SESSION['nombre_completo']), 0, 0, 'C'); 
$pdf->Cell(90, 4, '(Firma)', 0, 0, 'C');
$pdf->Cell(90, 4, '(Firma Jefe Campamento)', 0, 1, 'C');

$pdf->Output();
?>