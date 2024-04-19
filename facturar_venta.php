<?php
require('libreria/principal.php');
require('libreria/route_protection.php');

class PDF extends FPDF
{
    function Header()
    {
        // Título
        $this->SetFont('Courier', 'B', 15);
        $this->Cell(0, 10, 'Factura de Venta', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        // Posición a 1.5 cm desde la parte inferior
        $this->SetY(-15);
        // Arial itálica de 8 puntos
        $this->SetFont('Courier', 'I', 8);
        // Color de texto en gris
        $this->SetTextColor(128);
        // Número de página
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }

    function PrintSaleDetails($fecha, $codigo_cliente, $nombre_cliente, $total_pagar, $articulos)
    {
        $this->SetFont('Courier', '', 12);
        $this->Cell(0, 10, 'Fecha: ' . $fecha, 0, 1);
        $this->Cell(0, 10, 'Codigo del Cliente: ' . $codigo_cliente, 0, 1);
        $this->Cell(0, 10, 'Nombre del Cliente: ' . $nombre_cliente, 0, 1);
        $this->Cell(0, 10, 'Total a Pagar: $' . number_format($total_pagar, 2), 0, 1);
        $this->Ln(10);

        // Imprimir detalles de los artículos vendidos
        $this->SetFont('Courier', 'B', 12);
        $this->Cell(20, 10, 'Cant.', 1, 0, 'C');
        $this->Cell(100, 10, 'Articulo', 1, 0, 'C');
        $this->Cell(30, 10, 'Precio', 1, 0, 'C');
        $this->Cell(30, 10, 'Total', 1, 1, 'C');
        $this->SetFont('Courier', '', 12);

        foreach ($articulos as $articulo) {
            $this->Cell(20, 10, $articulo['cantidad'], 1, 0, 'C');
            $this->Cell(100, 10, $articulo['nombre_articulo'], 1, 0, 'L');
            $this->Cell(30, 10, '$' . number_format($articulo['precio'], 2), 1, 0, 'R');
            $this->Cell(30, 10, '$' . number_format($articulo['total'], 2), 1, 1, 'R');
        }
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

// Insertar los artículos en la base de datos y crear un array con los detalles
$articulos = array();
for ($i = 0; $i < count($nombre_articulos); $i++) {
    $nombre_articulo = $nombre_articulos[$i];
    $cantidad = $cantidades[$i];
    $precio = $precios[$i];
    $total = $cantidad * $precio;
    $sql_articulo = "INSERT INTO articulos (ventaID, nombre_articulo, cantidad, precio, total) VALUES ('$venta_id', '$nombre_articulo', $cantidad, $precio, $total)";
    mysqli_query($con, $sql_articulo);

    $articulos[] = array(
        'nombre_articulo' => $nombre_articulo,
        'cantidad' => $cantidad,
        'precio' => $precio,
        'total' => $total
    );
}

// Crear PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->PrintSaleDetails($fecha, $codigo_cliente, $nombre_cliente, $total_pagar, $articulos);

// Descargar el archivo PDF
$pdf->Output('factura.pdf', 'D');
?>