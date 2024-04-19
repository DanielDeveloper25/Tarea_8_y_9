<?php
function obtenerCantidadFacturasMes() {
    include('db_config.php');
    $con = connection();

    $mes_actual = date('m');
    $anio_actual = date('Y');

    $sql = "SELECT COUNT(*) as cantidad FROM ventas WHERE MONTH(fecha) = $mes_actual AND YEAR(fecha) = $anio_actual";
    $query = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($query);

    return $row['cantidad'];
}

function obtenerTotalCobradoMes() {
    include('db_config.php');
    $con = connection();

    $mes_actual = date('m');
    $anio_actual = date('Y');

    $sql = "SELECT SUM(total_pagar) as total FROM ventas WHERE MONTH(fecha) = $mes_actual AND YEAR(fecha) = $anio_actual";
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
        echo "<td><a href='details.php?id=".$row['ventaID']."' class='btn btn-sm btn-outline-primary'><i class='fas fa-eye'></i> Ver Detalles</a></td>";
        echo "</tr>";
    }   
}

// Función para obtener las ventas por hora del día actual
function obtenerVentasPorHora() {
    include('db_config.php');
    $con = connection();

    $sql = "SELECT DATE_FORMAT(fecha, '%H') AS hora, SUM(total_pagar) AS total_ventas 
            FROM ventas
            WHERE DATE(fecha) = CURDATE()
            GROUP BY hora
            ORDER BY hora";
    $query = mysqli_query($con, $sql);

    $ventas_por_hora = array();
    while($row = mysqli_fetch_assoc($query)) {
        $ventas_por_hora[$row['hora']] = $row['total_ventas'];
    }

    return $ventas_por_hora;
}

// Función para obtener los top 5 productos más vendidos
function obtenerTopProductos() {
    include('db_config.php');
    $con = connection();

    $sql = "SELECT a.nombre_articulo, SUM(a.cantidad) AS total_cantidad
            FROM articulos a
            JOIN ventas v ON a.ventaID = v.ventaID
            WHERE MONTH(v.fecha) = MONTH(CURDATE()) AND YEAR(v.fecha) = YEAR(CURDATE())
            GROUP BY a.nombre_articulo
            ORDER BY total_cantidad DESC
            LIMIT 5";
    $query = mysqli_query($con, $sql);

    $top_productos = array();
    while($row = mysqli_fetch_assoc($query)) {
        $top_productos[] = array(
            'nombre' => $row['nombre_articulo'],
            'cantidad' => $row['total_cantidad']
        );
    }

    return $top_productos;
}

