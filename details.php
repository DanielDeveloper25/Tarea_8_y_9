<?php
include('db_config.php');
$con = connection();

// Obtener el ID de la factura desde la URL
if (isset($_GET['id'])) {
    $id_venta = mysqli_real_escape_string($con, $_GET['id']);

    // Obtener los datos de la venta
    $sql_venta = "SELECT * FROM ventas WHERE ventaID = '$id_venta'";
    $query_venta = mysqli_query($con, $sql_venta);
    $venta = mysqli_fetch_assoc($query_venta);

    // Obtener los datos de los artículos de la venta
    $sql_articulos = "SELECT * FROM articulos WHERE ventaID = '$id_venta'";
    $query_articulos = mysqli_query($con, $sql_articulos);

    // Obtener los comentarios de la venta
    $sql_comentarios = "SELECT comentario FROM ventas WHERE ventaID = '$id_venta'";
    $query_comentarios = mysqli_query($con, $sql_comentarios);
    $comentarios = mysqli_fetch_assoc($query_comentarios);
} else {
    $venta = null; // Asignar null a $venta si no se recibió un ID válido
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Factura</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

<div class="container mt-4">
    <h1 class="text-center"><i class="fas fa-file-invoice"></i> Detalles de Factura</h1>
    <hr>

    <div class="card">
        <div class="card-header bg-primary text-white">
            Detalles de la Venta
        </div>
        <div class="card-body">
        <?php if ($venta) : ?>
            <p><strong>Fecha:</strong> <?php echo $venta['fecha']; ?></p>
            <p><strong>Código del Cliente:</strong> <?php echo $venta['codigo_cliente']; ?></p>
            <p><strong>Nombre del Cliente:</strong> <?php echo $venta['nombre_cliente']; ?></p>
            <p><strong>Total a Pagar:</strong> $<?php echo number_format($venta['total_pagar'], 2); ?></p>
            <p><strong>Comentarios:</strong> <?php echo !empty($comentarios['comentario']) ? $comentarios['comentario'] : 'Sin comentarios'; ?></p> <!-- Mostrar comentarios -->
        <?php else: ?>
            <p>No se encontraron detalles de la venta.</p>
        <?php endif; ?>
        </div>
    </div>

    <?php if (isset($query_articulos)) : ?>
    <div class="mt-4">
        <h2>Artículos Vendidos</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($articulo = mysqli_fetch_assoc($query_articulos)) {
                    echo "<tr>";
                    echo "<td>".$articulo['articuloID']."</td>";
                    echo "<td>".$articulo['nombre_articulo']."</td>";
                    echo "<td>".$articulo['cantidad']."</td>";
                    echo "<td>$".number_format($articulo['precio'], 2)."</td>";
                    echo "<td>$".number_format($articulo['total'], 2)."</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
</body>
</html>

