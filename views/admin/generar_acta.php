<?php
session_start();
// 1. Seguridad: Solo usuarios logueados
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

// 2. Verificar que nos pasaron un ID
if (!isset($_GET['id'])) {
    die("Error: No se especificó ningún activo para generar el acta.");
}

$id_activo = $_GET['id'];

// 3. Importar FPDF y Conexión
// Ajustamos la ruta según tu estructura: estamos en views/admin, así que bajamos 2 niveles
require_once __DIR__ . '/../../libs/fpdf/fpdf.php';
require_once __DIR__ . '/../../config/conexion.php';

class PDF extends FPDF {
    // Cabecera de página
    function Header() {
        // Logo (Asegúrate de tener el archivo o comenta esta línea si no lo tienes)
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

// 4. Obtener datos de la BD
$database = new Conexion();
$conn = $database->getConexion();

$query = "SELECT a.*, 
          u.nombre_completo as responsable, u.email,
          s.nombre as sede, 
          c.nombre as categoria 
          FROM activos a
          LEFT JOIN usuarios u ON a.id_usuario_responsable = u.id_usuario
          LEFT JOIN sedes s ON a.id_sede_actual = s.id_sede
          LEFT JOIN categorias c ON a.id_categoria = c.id_categoria
          WHERE a.id_activo = ?";

$stmt = $conn->prepare($query);
$stmt->bindParam(1, $id_activo);
$stmt->execute();
$activo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$activo) {
    die("Activo no encontrado.");
}

// 5. Generar el PDF
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

// Texto Legal
$pdf->SetFont('Arial','',12);
$texto = "Por medio de la presente, se hace entrega formal del siguiente equipo de computación/tecnológico al colaborador " . $activo['responsable'] . ", quien se compromete a su cuidado y uso exclusivo para actividades laborales de la empresa.";
$pdf->MultiCell(0,7,utf8_decode($texto));
$pdf->Ln(10);

// Detalles del Equipo (Tabla)
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,utf8_decode('DETALLES DEL EQUIPO:'),0,1,'L');

$pdf->SetFont('Arial','',11);
// Ancho de celdas
$w_label = 50;
$w_data = 100;
$h = 8;

// Fila 1: Código
$pdf->Cell($w_label, $h, utf8_decode('Código Interno:'),1,0,'L');
$pdf->Cell($w_data, $h, utf8_decode($activo['codigo_interno']),1,1,'L');

// Fila 2: Marca
$pdf->Cell($w_label, $h, utf8_decode('Marca:'),1,0,'L');
$pdf->Cell($w_data, $h, utf8_decode($activo['marca']),1,1,'L');

// Fila 3: Modelo
$pdf->Cell($w_label, $h, utf8_decode('Modelo:'),1,0,'L');
$pdf->Cell($w_data, $h, utf8_decode($activo['modelo']),1,1,'L');

// Fila 4: Serie
$pdf->Cell($w_label, $h, utf8_decode('Número de Serie:'),1,0,'L');
$pdf->Cell($w_data, $h, utf8_decode($activo['serie']),1,1,'L');

// Fila 5: Estado
$pdf->Cell($w_label, $h, utf8_decode('Estado de Entrega:'),1,0,'L');
$pdf->Cell($w_data, $h, utf8_decode($activo['estado']),1,1,'L');

// Fila 6: Ubicación
$pdf->Cell($w_label, $h, utf8_decode('Sede / Proyecto:'),1,0,'L');
$pdf->Cell($w_data, $h, utf8_decode($activo['sede']),1,1,'L');

$pdf->Ln(20);

// Firmas
$pdf->SetY(-60); // Posición desde abajo
$pdf->SetFont('Arial','B',11);

// Firma Empleado
$pdf->Cell(90, 0, '__________________________', 0, 0, 'C');
$pdf->Cell(90, 0, '__________________________', 0, 1, 'C');
$pdf->Ln(5);
$pdf->Cell(90, 5, utf8_decode('RECIBÍ CONFORME'), 0, 0, 'C');
$pdf->Cell(90, 5, utf8_decode('ENTREGADO POR'), 0, 1, 'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(90, 5, utf8_decode($activo['responsable']), 0, 0, 'C');
$pdf->Cell(90, 5, utf8_decode('Dpto. Infraestructura IT'), 0, 1, 'C');

$pdf->Output();
?>