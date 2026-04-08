<?php
// 1. Llamamos al controlador que trae la lógica y la variable $activo
require_once '../../controllers/CargarActaController.php';

// 2. Importar FPDF
require_once '../../libs/fpdf/fpdf.php';

class PDF extends FPDF {
    // Cabecera de página
    function Header() {
        // Logo
        $this->Image('../../public/img/logo.png',10,8,33);
        
        $this->SetFont('Arial','B',15);
        $this->Cell(80); // Mover a la derecha
        $this->Cell(30,10,'KLUANE DRILLING ECUADOR S.A.',0,0,'C');
        $this->Ln(20); // Salto de línea
    }

    // Pie de página
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }
}

// 3. Generar el PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Título del Documento
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,utf8_decode('ACTA DE ENTREGA DE EQUIPOS'),0,1,'C');
$pdf->Ln(10);

// Fecha y Lugar
$pdf->SetFont('Arial','',12);
date_default_timezone_set('America/Guayaquil');
$fecha_actual = date('d/m/Y');
$pdf->Cell(0,10,utf8_decode('Quito, ' . $fecha_actual),0,1,'R');
$pdf->Ln(10);

// Texto Legal (Manejamos si no hay responsable asignado)
$nombre_responsable = $activo['responsable'] ? $activo['responsable'] : "___________________";

$pdf->SetFont('Arial','',12);
$texto = "Por medio de la presente, se hace entrega formal del siguiente equipo de computación/tecnológico al colaborador " . $nombre_responsable . ", quien se compromete a su cuidado y uso exclusivo para actividades laborales de la empresa.";
$pdf->MultiCell(0,7,utf8_decode($texto));
$pdf->Ln(10);

// Detalles del Equipo (Tabla)
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,utf8_decode('DETALLES DEL EQUIPO:'),0,1,'L');

$pdf->SetFont('Arial','',11);
$w_label = 50;
$w_data = 100;
$h = 8;

// Filas de la tabla
$pdf->Cell($w_label, $h, utf8_decode('Código Interno:'),1,0,'L');
$pdf->Cell($w_data, $h, utf8_decode($activo['codigo_interno']),1,1,'L');

$pdf->Cell($w_label, $h, utf8_decode('Marca:'),1,0,'L');
$pdf->Cell($w_data, $h, utf8_decode($activo['marca']),1,1,'L');

$pdf->Cell($w_label, $h, utf8_decode('Modelo:'),1,0,'L');
$pdf->Cell($w_data, $h, utf8_decode($activo['modelo']),1,1,'L');

$pdf->Cell($w_label, $h, utf8_decode('Número de Serie:'),1,0,'L');
$pdf->Cell($w_data, $h, utf8_decode($activo['serie']),1,1,'L');

$pdf->Cell($w_label, $h, utf8_decode('Estado de Entrega:'),1,0,'L');
$pdf->Cell($w_data, $h, utf8_decode($activo['estado']),1,1,'L');

$pdf->Cell($w_label, $h, utf8_decode('Sede / Proyecto:'),1,0,'L');
$pdf->Cell($w_data, $h, utf8_decode($activo['sede']),1,1,'L');

$pdf->Ln(20);

// Firmas
$pdf->SetY(-60);
$pdf->SetFont('Arial','B',11);

// Firma Empleado y Dpto IT
$pdf->Cell(90, 0, '__________________________', 0, 0, 'C');
$pdf->Cell(90, 0, '__________________________', 0, 1, 'C');
$pdf->Ln(5);
$pdf->Cell(90, 5, utf8_decode('RECIBÍ CONFORME'), 0, 0, 'C');
$pdf->Cell(90, 5, utf8_decode('ENTREGADO POR'), 0, 1, 'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(90, 5, utf8_decode($nombre_responsable), 0, 0, 'C');
$pdf->Cell(90, 5, utf8_decode('Dpto. Infraestructura IT'), 0, 1, 'C');

$pdf->Output();
?>