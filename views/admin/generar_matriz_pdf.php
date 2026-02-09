<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../libs/fpdf/fpdf.php';
require_once '../../models/Matriz.php';

if (!isset($_GET['sede'])) {
    die("Error: Seleccione una sede.");
}

$id_sede = $_GET['sede'];
$matrizModel = new Matriz();
$datos = $matrizModel->obtenerDatosMatriz($id_sede); // Obtenemos los equipos
$nombre_sede = "Proyecto Desconocido"; // Valor por defecto

// Necesitamos recorrer los datos una vez para sacar estadísticas y el nombre de la sede
$filas = [];
$total_operativos = 0;
$total_danados = 0;
$conteo_laptops = 0;
$conteo_radios = 0;

while ($row = $datos->fetch(PDO::FETCH_ASSOC)) {
    $filas[] = $row;
    $nombre_sede = $row['ubicacion']; // Capturamos el nombre real
    
    // Contadores para el encabezado (KPIs)
    if($row['estado'] == 'Operativo') $total_operativos++;
    if($row['estado'] == 'Dañado') $total_danados++;
    if(strpos(strtoupper($row['equipo']), 'LAPTOP') !== false) $conteo_laptops++;
    if(strpos(strtoupper($row['equipo']), 'RADIO') !== false) $conteo_radios++;
}

class PDF extends FPDF {
    function Header() {
        // No ponemos nada aquí para controlar manualmente la posición en el cuerpo
    }
    
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb} - Sistema Kluane Inventario',0,0,'C');
    }
}

$pdf = new PDF('L','mm','A4'); // Horizontal (Landscape) para que quepa la tabla
$pdf->AliasNbPages();
$pdf->AddPage();

// --- ENCABEZADO TIPO EXCEL EC-IT-F-09 ---
$pdf->SetFont('Arial','B',16);
$pdf->Cell(180, 10, 'KLUANE DRILLING ECUADOR S.A.', 0, 0, 'L');

// Cuadro de Código a la derecha
$pdf->SetFont('Arial','B',9);
$pdf->SetXY(240, 10); 
$pdf->MultiCell(40, 5, "EC-IT-F-09\nREV-0\nOCT-2025", 1, 'C');

$pdf->SetXY(10, 25);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0, 10, utf8_decode('MATRIZ DE GESTIÓN DE EQUIPOS TECNOLÓGICOS EN CAMPAMENTO'), 0, 1, 'C');

$pdf->Ln(5);

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

$pdf->Ln(10);

// --- TABLA DE DATOS ---
$pdf->SetFillColor(50, 50, 50); // Fondo Oscuro
$pdf->SetTextColor(255, 255, 255); // Letra Blanca
$pdf->SetFont('Arial','B',9);

// Anchos de columnas
$w = array(10, 40, 45, 50, 40, 30, 30, 25);
$header = array('N', 'Equipo', 'Serie', 'Responsable', utf8_decode('Área'), 'Fecha', utf8_decode('Ubicación'), 'Estado');

for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
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
$pdf->Ln(30);
$pdf->SetFont('Arial','B',9);

$pdf->Cell(90, 0, '__________________________', 0, 0, 'C');
$pdf->Cell(90, 0, '__________________________', 0, 0, 'C');
$pdf->Cell(90, 0, '__________________________', 0, 1, 'C');

$pdf->Ln(5);

$pdf->Cell(90, 4, 'ELABORADO POR', 0, 0, 'C');
$pdf->Cell(90, 4, 'REVISADO POR (LOGISTICA)', 0, 0, 'C');
$pdf->Cell(90, 4, 'RECIBIDO EN SITIO', 0, 1, 'C');

$pdf->SetFont('Arial','',8);
$pdf->Cell(90, 4, utf8_decode($_SESSION['nombre_completo']), 0, 0, 'C'); // Tu nombre
$pdf->Cell(90, 4, '(Firma)', 0, 0, 'C');
$pdf->Cell(90, 4, '(Firma Jefe Campamento)', 0, 1, 'C');

$pdf->Output();
?>