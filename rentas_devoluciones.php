<?php
include 'db.php';
include 'login-condition.php';


$id_vehiculo_renta = '';

// Verificar si la carga de la página viene por un id desde el homepage
if (isset($_GET['id'])) {
    $id_vehiculo_renta = $_GET['id'];
}

// Obtener datos de la renta seleccionada
$renta_data = [
    'no_renta' => '',
    'empleado' => '',
    'vehiculo' => '',
    'cliente' => '',
    'fecha_renta' => '',
    'fecha_devolucion' => '',
    'monto_por_dia' => '',
    'cantidad_dias' => '',
    'comentario' => '',
    'estado' => 1
];

if ($id_vehiculo_renta) {
    $sql_renta = "SELECT * FROM rentas_devoluciones WHERE no_renta=$id_vehiculo_renta";
    $result_renta = $conn->query($sql_renta);
    if ($result_renta->num_rows > 0) {
        $renta_data = $result_renta->fetch_assoc();
    }
}


$sql_empleados = "SELECT id, nombre FROM empleados WHERE estado=1";
$result_empleados = $conn->query($sql_empleados);

// Obtener lista de vehículos activos
$sql_vehiculos = "SELECT id, modelo FROM vehiculos WHERE estado=1";
$result_vehiculos = $conn->query($sql_vehiculos);

// Obtener lista de clientes
$sql_clientes = "SELECT id, nombre FROM clientes WHERE estado=1";
$result_clientes = $conn->query($sql_clientes);

// Criterios de búsqueda disponibles
$criterios = [
    'empleado' => 'Empleado',
    'vehiculo' => 'Vehículo',
    'cliente' => 'Cliente',
    'comentario' => 'Comentario'
];

// Filtro por criterio
$where = '';
if (isset($_GET['filtro']) && isset($_GET['criterio'])) {
    $filtro = $_GET['filtro'];
    $criterio = $_GET['criterio'];

    // Validar el criterio seleccionado
    if (array_key_exists($criterio, $criterios)) {
        $where = " WHERE $criterio LIKE '%$filtro%'";
    }
}

// Acción de eliminar
if (isset($_GET['delete'])) {
    $no_renta = $_GET['delete'];
    $sql_delete = "DELETE FROM rentas_devoluciones WHERE no_renta = $no_renta";
    $conn->query($sql_delete);
    // Redirigir al mismo archivo con GET limpia después de eliminar
    header("Location: rentas_devoluciones.php");
}

// Acción de agregar o modificar
if (isset($_POST['save'])) {
    $no_renta = $_POST['no_renta'];
    $empleado = $_POST['empleado'];
    $vehiculo = $_POST['vehiculo'];
    $cliente = $_POST['cliente'];
    $fecha_renta = $_POST['fecha_renta'];
    $fecha_devolucion = $_POST['fecha_devolucion'];
    $monto_por_dia = $_POST['monto_por_dia'];
    $cantidad_dias = $_POST['cantidad_dias'];
    $comentario = $_POST['comentario'];
    $estado = isset($_POST['estado']) ? 1 : 0;

    if ($no_renta) {
        // Modificar renta/devolución
        $sql = "UPDATE rentas_devoluciones SET empleado='$empleado', vehiculo='$vehiculo', cliente='$cliente', fecha_renta='$fecha_renta', fecha_devolucion='$fecha_devolucion', monto_por_dia='$monto_por_dia', cantidad_dias='$cantidad_dias', comentario='$comentario', estado='$estado' WHERE no_renta=$no_renta";
    } else {
        // Agregar nueva renta/devolución
        $sql = "INSERT INTO rentas_devoluciones (empleado, vehiculo, cliente, fecha_renta, fecha_devolucion, monto_por_dia, cantidad_dias, comentario, estado) VALUES ('$empleado', '$vehiculo', '$cliente', '$fecha_renta', '$fecha_devolucion', '$monto_por_dia', '$cantidad_dias', '$comentario', '$estado')";
    }
    $conn->query($sql);
    header("Location: rentas_devoluciones.php");
}

// Obtener lista de rentas/devoluciones según el filtro
$sql = "SELECT * FROM rentas_devoluciones $where";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>Rentas y Devoluciones</h2>
    <form method="GET" action="" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="criterio">Criterio:</label>
                <select class="form-control" id="criterio" name="criterio">
                    <option value="">Seleccionar...</option>
                    <?php foreach ($criterios as $key => $value): ?>
                        <option value="<?php echo $key; ?>" <?php echo (isset($_GET['criterio']) && $_GET['criterio'] === $key) ? 'selected' : ''; ?>><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-md-7">
                <label for="filtro">Filtro:</label>
                <input type="text" class="form-control" id="filtro" name="filtro" value="<?php echo isset($_GET['filtro']) ? $_GET['filtro'] : ''; ?>">
            </div>
            <div class="form-group col-md-2">
                <label for="">&nbsp;</label>
                <br>
                <button type="submit" class="btn btn-primary btn-block">Buscar</button>
            </div>
        </div>
    </form>

    <h2><?php echo $id_vehiculo_renta ? 'Editar Renta/Devolución' : 'Agregar Renta/Devolución'; ?></h2>
    <form method="POST" action="">
        <input type="hidden" name="no_renta" value="<?php echo $renta_data['no_renta']; ?>">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="empleado">Empleado:</label>
                <input type="text" class="form-control" id="empleado" name="empleado" value="<?php echo $_SESSION['username']; ?>" readonly>
            </div>
            <div class="form-group col-md-4">
                <label for="vehiculo">Vehículo:</label>
                <select class="form-control" id="vehiculo" name="vehiculo" required>
                    <option value="">Seleccionar...</option>
                    <?php $result_vehiculos->data_seek(0); ?>
                    <?php while ($row = $result_vehiculos->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo ($renta_data['vehiculo'] == $row['id']) ? 'selected' : ''; ?>><?php echo $row['modelo']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="cliente">Cliente:</label>
                <select class="form-control" id="cliente" name="cliente" required>
                    <option value="">Seleccionar...</option>
                    <?php $result_clientes->data_seek(0); ?>
                    <?php while ($row = $result_clientes->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo ($renta_data['cliente'] == $row['id']) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="fecha_renta">Fecha de Renta:</label>
                <input type="date" class="form-control" id="fecha_renta" name="fecha_renta" value="<?php echo $renta_data['fecha_renta']; ?>" required>
            </div>
            <div class="form-group col-md-3">
                <label for="fecha_devolucion">Fecha de Devolución:</label>
                <input type="date" class="form-control" id="fecha_devolucion" name="fecha_devolucion" value="<?php echo $renta_data['fecha_devolucion']; ?>" required>
            </div>
            <div class="form-group col-md-2">
                <label for="monto_por_dia">Monto por Día:</label>
                <input type="number" class="form-control" id="monto_por_dia" name="monto_por_dia" value="<?php echo $renta_data['monto_por_dia']; ?>" required>
            </div>
            <div class="form-group col-md-2">
                <label for="cantidad_dias">Cantidad de Días:</label>
                <input type="number" class="form-control" id="cantidad_dias" name="cantidad_dias" value="<?php echo $renta_data['cantidad_dias']; ?>" required>
            </div>
            <div class="form-group col-md-2">
                <label for="estado">Estado:</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="estado" name="estado" value="1" <?php echo ($renta_data['estado']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="estado">
                        Activo
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="comentario">Comentario:</label>
            <textarea class="form-control" id="comentario" name="comentario" rows="3"><?php echo $renta_data['comentario']; ?></textarea>
        </div>
        <br>
        <button type="submit" name="save" class="btn btn-primary"><?php echo $id_vehiculo_renta ? 'Guardar Cambios' : 'Agregar'; ?></button>
        <a href="fpdf-tutoriales-master/PruebaH.php" target="_blank" class="btn btn-sm btn-warning">Generar Reporte</a>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>No. Renta</th>
                    <th>Empleado</th>
                    <th>Vehículo</th>
                    <th>Cliente</th>
                    <th>Fecha de Renta</th>
                    <th>Fecha de Devolución</th>
                    <th>Monto por Día</th>
                    <th>Cantidad de Días</th>
                    <th>Comentario</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['no_renta']; ?></td>
                        <td><?php echo $row['empleado']; ?></td>
                        <td><?php echo $row['vehiculo']; ?></td>
                        <td><?php echo $row['cliente']; ?></td>
                        <td><?php echo $row['fecha_renta']; ?></td>
                        <td><?php echo $row['fecha_devolucion']; ?></td>
                        <td><?php echo $row['monto_por_dia']; ?></td>
                        <td><?php echo $row['cantidad_dias']; ?></td>
                        <td><?php echo $row['comentario']; ?></td>
                        <td><?php echo $row['estado'] ? 'Activo' : 'Inactivo'; ?></td>
                        <td>
                            <a href="?id=<?php echo $row['no_renta']; ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="?delete=<?php echo $row['no_renta']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta renta/devolución?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>    
</div>
<br><br><br>

<?php
include 'templates/footer.php';
?>
