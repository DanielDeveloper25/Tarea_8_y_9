 <?php
require('libreria/principal.php');
require('libreria/route_protection.php');
?> 

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Facturación</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container-fluid mt-4">
<div class="row">
        <div class="col-auto ml-auto">
            <a href="logout.php" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
        </div>
    </div>
    <h1 class="text-center"><i class="fas fa-file-invoice"></i> Sistema de Facturación</h1>
    <hr>
    <a href="create.php" class="btn btn-outline-success mb-3"><i class="fas fa-plus"></i> Agregar factura</a>


    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-outline-dark text-dark">
                    Ventas del día
                </div>
                <div class="card-body">
                    <canvas id="ventasDiaChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-outline-dark text-dark">
                    Top 5 Productos Más Vendidos
                </div>
                <div class="card-body">
                    <canvas id="topProductosChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-outline-dark text-dark">
                    Resumen del Mes
                </div>
                <div class="card-body">
                    <p>Cantidad de facturas: <?php echo obtenerCantidadFacturasMes(); ?></p>
                    <p>Total cobrado: <?php echo obtenerTotalCobradoMes(); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
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

<script>
    // Gráfico de ventas del día
    var ctx = document.getElementById('ventasDiaChart').getContext('2d');
    var ventasDiaChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php
                $ventas_por_hora = obtenerVentasPorHora();
                $horas = array_keys($ventas_por_hora);
                echo '"' . implode('", "', $horas) . '"';
            ?>],
            datasets: [{
                label: 'Ventas ($)',
                data: [<?php
                    $ventas = array_values($ventas_por_hora);
                    echo implode(', ', $ventas);
                ?>],
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico de top productos más vendidos
    var ctx = document.getElementById('topProductosChart').getContext('2d');
    var topProductosChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: [<?php
                $top_productos = obtenerTopProductos();
                $nombres = array_column($top_productos, 'nombre');
                echo '"' . implode('", "', $nombres) . '"';
            ?>],
            datasets: [{
                data: [<?php
                    $cantidades = array_column($top_productos, 'cantidad');
                    echo implode(', ', $cantidades);
                ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Top 5 Productos Más Vendidos'
                }
            }
        }
    });
</script>


</body>
</html>



