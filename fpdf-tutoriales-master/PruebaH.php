<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('fpdf.php'); 
require('db.php'); 

class PDF extends FPDF
{

    // Cabecera de página
    function Header()
    {
        $this->Image('logo.jpeg', 270, 5, 20); // Logo de la empresa
        $this->SetFont('Arial', 'B', 19);
        $this->Cell(95);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(110, 15, utf8_decode('Tristan RentCar'), 1, 1, 'C', 0); // Título
        $this->Ln(3);

        // Información de la empresa
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(180);
        $this->Cell(96, 10, utf8_decode("Ubicación: Calle A, no. 22, DN"), 0, 1, '', 0);
        $this->Cell(180);
        $this->Cell(59, 10, utf8_decode("Teléfono: 8099999999"), 0, 1, '', 0);
        $this->Cell(180);
        $this->Cell(85, 10, utf8_decode("Correo: tristanrentcar@noemail.com"), 0, 1, '', 0);
        $this->Cell(180);
        $this->Cell(85, 10, utf8_decode("Sucursal: Principal"), 0, 1, '', 0);
        $this->Ln(10);

        // Título de la tabla
        $this->SetTextColor(228, 100, 0);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, utf8_decode("REPORTE DE RENTAS Y DEVOLUCIONES"), 0, 1, 'C', 0);
        $this->Ln(7);

        // Encabezados de las columnas
        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(10, 10, utf8_decode('No'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Empleado'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Vehículo'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Cliente'), 1, 0, 'C', 1);
        $this->Cell(40, 10, utf8_decode('Fecha Renta'), 1, 0, 'C', 1);
        $this->Cell(40, 10, utf8_decode('Fecha Devolución'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Monto x Día'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Cant. Días'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Comentario'), 1, 0, 'C', 1);
        $this->Cell(20, 10, utf8_decode('Estado'), 1, 1, 'C', 1);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');

        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $hoy = date('d/m/Y');
        $this->Cell(540, 10, utf8_decode($hoy), 0, 0, 'C');
    }
}

// Crear un objeto PDF
$pdf = new PDF();
$pdf->AddPage("landscape");
$pdf->AliasNbPages();

// Consulta SQL para obtener los datos de rentas_devoluciones
$sql = "SELECT * FROM rentas_devoluciones";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Datos encontrados, procesar cada fila
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetDrawColor(163, 163, 163);

    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(10, 10, utf8_decode($row['no_renta']), 1, 0, 'C', 0);
        $pdf->Cell(30, 10, utf8_decode($row['empleado']), 1, 0, 'C', 0);
        $pdf->Cell(30, 10, utf8_decode($row['vehiculo']), 1, 0, 'C', 0);
        $pdf->Cell(30, 10, utf8_decode($row['cliente']), 1, 0, 'C', 0);
        $pdf->Cell(40, 10, utf8_decode($row['fecha_renta']), 1, 0, 'C', 0);
        $pdf->Cell(40, 10, utf8_decode($row['fecha_devolucion']), 1, 0, 'C', 0);
        $pdf->Cell(30, 10, utf8_decode('$' . number_format($row['monto_por_dia'], 2)), 1, 0, 'C', 0);
        $pdf->Cell(30, 10, utf8_decode($row['cantidad_dias']), 1, 0, 'C', 0);
        $pdf->Cell(30, 10, utf8_decode($row['comentario']), 1, 0, 'C', 0);
        $pdf->Cell(20, 10, utf8_decode($row['estado'] ? 'Finalizada' : 'Pendiente'), 1, 1, 'C', 0);
    }
} else {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, utf8_decode('No se encontraron registros'), 0, 1, 'C');
}

// Limpiar el buffer de salida antes de enviar el PDF
ob_clean();

// Enviar el PDF al navegador
$pdf->Output('Prueba2.pdf', 'I');

// Cerrar conexión a la base de datos
$conn->close();
?>
