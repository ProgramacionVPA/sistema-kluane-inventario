<?php
// 1. Llamar a la librería (Fíjate que la ruta coincida con las carpetas que creamos)
require('libs/fpdf/fpdf.php');

// 2. Crear el objeto PDF
// 'P' = Vertical, 'mm' = Milímetros, 'A4' = Tamaño de hoja
$pdf = new FPDF('P','mm','A4');

// 3. Añadir una página
$pdf->AddPage();

// 4. Elegir letra (Arial, Negrita, Tamaño 16)
$pdf->SetFont('Arial','B',16);

// 5. Escribir algo
// Cell(Ancho, Alto, Texto, Borde, SaltoLinea, Alineación)
$pdf->Cell(190, 10, 'KLUANE DRILLING ECUADOR S.A.', 0, 1, 'C');

// Escribir algo más pequeño
$pdf->SetFont('Arial','',12);
$pdf->Cell(190, 10, 'Prueba de generacion de reportes PDF - Sprint 3', 0, 1, 'C');

// 6. Mostrar el PDF en el navegador
$pdf->Output();
?>