<?php
    require('libreria/route_protection.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Factura</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center"><i class="fas fa-plus"></i> Agregar Factura</h1>
        <hr>
        <form action="facturar_venta.php" method="POST">
            <div class="form-group">
                <input type="hidden" class="form-control" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label for="codigo_cliente">Código del Cliente:</label>
                <input type="text" class="form-control" id="codigo_cliente" name="codigo_cliente" required>
            </div>
            <div class="form-group">
                <label for="nombre_cliente">Nombre del Cliente:</label>
                <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" required>
            </div>
            <div class="form-group">
                <input type="hidden" step="0.01" min="0" class="form-control" id="total_pagar" name="total_pagar" required>
            </div>
            <hr>
            <h2>Agregar Artículos</h2>
            <div id="articulos">
                <div class="form-row mb-2">
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Nombre del Artículo" name="nombre_articulo[]" required>
                    </div>
                    <div class="col">
                        <input type="number" step="1" min="1" class="form-control" placeholder="Cantidad" name="cantidad[]" required>
                    </div>
                    <div class="col">
                        <input type="number" step="0.01" min="0" class="form-control" placeholder="Precio Unitario" name="precio[]" required>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-primary" onclick="agregarArticulo()"><i class="fas fa-plus"></i> Agregar Artículo</button>
            <div class="form-group">
                <label for="comentario">Comentario:</label>
                <textarea class="form-control" id="comentario" name="comentario" placeholder="Comentario (opcional)"></textarea>
            </div>
            <hr>
            <div class="mt-4 d-flex justify-content-between">
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al Dashboard</a>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar Factura</button>
            </div>

        </form>
    </div>

    <script>
        function agregarArticulo() {
            var articulosDiv = document.getElementById("articulos");
            var newArticuloDiv = document.createElement("div");
            newArticuloDiv.className = "form-row mb-2";
            newArticuloDiv.innerHTML = `
                <div class="col">
                    <input type="text" class="form-control" placeholder="Nombre del Artículo" name="nombre_articulo[]" required>
                </div>
                <div class="col">
                    <input type="number" step="1" min="1" class="form-control" placeholder="Cantidad" name="cantidad[]" required>
                </div>
                <div class="col">
                    <input type="number" step="0.01" min="0" class="form-control" placeholder="Precio Unitario" name="precio[]" required>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger" onclick="eliminarArticulo(this)"><i class="fas fa-trash"></i></button>
                </div>
            `;
            articulosDiv.appendChild(newArticuloDiv);
        }

        function eliminarArticulo(btn) {
            var articuloDiv = btn.parentNode.parentNode;
            articuloDiv.parentNode.removeChild(articuloDiv);
        }
    </script>
</body>
</html>