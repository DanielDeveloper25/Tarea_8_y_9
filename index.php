<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Facturación</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

<div class="container mt-4">
<h1 class="text-center"><i class="fas fa-file-invoice"></i> Sistema de Facturación</h1>
<hr>
<a href="create.php" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Agregar factura</a>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Resumen del día
                </div>
                <div class="card-body">
                    <p>Cantidad de facturas: <?php echo obtenerCantidadFacturas(); ?></p>
                    <p>Total cobrado: <?php echo obtenerTotalCobrado(); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    Lista de Facturas
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Código del Cliente</th>
                                <th>Nombre del Cliente</th>
                                <th>Total a Pagar</th>
                                <th>Comentarios</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php mostrarFacturas(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php

function obtenerCantidadFacturas() {
    include('db_config.php');
    $con = connection();

    $sql = "SELECT COUNT(*) as cantidad FROM ventas";
    $query = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($query);

    return $row['cantidad'];
}

function obtenerTotalCobrado() {
    include('db_config.php');
    $con = connection();

    $sql = "SELECT SUM(total_pagar) as total FROM ventas";
    $query = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($query);

    return "$" . number_format($row['total'], 2);
}

function mostrarFacturas() {
    include('db_config.php');
    $con = connection();

    $sql = "SELECT * FROM ventas";
    $query = mysqli_query($con, $sql);

    while($row = mysqli_fetch_assoc($query)) {
        echo "<tr>";
        echo "<td>".$row['ventaID']."</td>";
        echo "<td>".$row['fecha']."</td>";
        echo "<td>".$row['codigo_cliente']."</td>";
        echo "<td>".$row['nombre_cliente']."</td>";
        echo "<td>".$row['total_pagar']."</td>";
        echo "<td>".($row['comentario'] ? $row['comentario'] : 'Sin comentarios')."</td>";
        echo "<td><a href='details.php?id=".$row['ventaID']."' class='btn btn-sm btn-info'><i class='fas fa-eye'></i> Ver Detalles</a></td>";
        echo "</tr>";
    }   
}

?>
