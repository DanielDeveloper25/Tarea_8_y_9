<?php
require('fpdf.php');
include('db_config.php');

class PDF extends FPDF {
    function Header() {
        // Título
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, 'Factura de Venta', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        // Posición a 1.5 cm desde la parte inferior
        $this->SetY(-15);
        // Arial itálica de 8 puntos
        $this->SetFont('Arial', 'I', 8);
        // Color de texto en gris
        $this->SetTextColor(128);
        // Número de página
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }

    function PrintSaleDetails($fecha, $codigo_cliente, $nombre_cliente, $total_pagar) {
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, 'Fecha: ' . $fecha, 0, 1);
        $this->Cell(0, 10, 'Código del Cliente: ' . $codigo_cliente, 0, 1);
        $this->Cell(0, 10, 'Nombre del Cliente: ' . $nombre_cliente, 0, 1);
        $this->Cell(0, 10, 'Total a Pagar: ' . $total_pagar, 0, 1);
    }
}

// Obtener datos de la venta
$fecha = $_POST["fecha"];
$codigo_cliente = $_POST["codigo_cliente"];
$nombre_cliente = $_POST["nombre_cliente"];

// Inicializar el total en 0
$total_pagar = 0;

// Obtener datos de los artículos vendidos
$nombre_articulos = $_POST["nombre_articulo"];
$cantidades = $_POST["cantidad"];
$precios = $_POST["precio"];
$comentario = isset($_POST["comentario"]) ? $_POST["comentario"] : "";

// Calcular el total sumando los totales de cada artículo
for ($i = 0; $i < count($nombre_articulos); $i++) {
    $cantidad = $cantidades[$i];
    $precio = $precios[$i];
    $total_pagar += $cantidad * $precio;
}

// Conectar a la base de datos
$con = connection();

// Insertar la venta en la base de datos
$sql_venta = "INSERT INTO ventas (fecha, codigo_cliente, nombre_cliente, total_pagar, comentario) VALUES ('$fecha', '$codigo_cliente', '$nombre_cliente', $total_pagar, '$comentario')";
$query_venta = mysqli_query($con, $sql_venta);

// Obtener el ID de la venta recién insertada
$venta_id = mysqli_insert_id($con);

// Insertar los artículos en la base de datos
for ($i = 0; $i < count($nombre_articulos); $i++) {
    $nombre_articulo = $nombre_articulos[$i];
    $cantidad = $cantidades[$i];
    $precio = $precios[$i];
    $total = $cantidad * $precio;

    $sql_articulo = "INSERT INTO articulos (ventaID, nombre_articulo, cantidad, precio, total) VALUES ('$venta_id', '$nombre_articulo', $cantidad, $precio, $total)";
    mysqli_query($con, $sql_articulo);
}

// Crear PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->PrintSaleDetails($fecha, $codigo_cliente, $nombre_cliente, $total_pagar);

// Descargar el archivo PDF
$pdf->Output('factura.pdf', 'D');

// Redireccionar a index.php en un script separado
$redirect_url = "index.php";
echo "<script>window.open('$redirect_url', '_self')</script>";
exit();
?>