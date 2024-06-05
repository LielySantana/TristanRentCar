<?php
include 'db.php';
include 'login-condition.php';

$id = '';
$vehiculo = '';
$cliente = '';
$ralladuras = 0;
$cantidad_combustible = '';
$goma_repuesta = 0;
$gato = 0;
$roturas_cristal = 0;
$estado_gomas = 0;
$fecha = '';
$empleado_inspeccion = '';
$estado = 0;

// Acción de eliminar
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM inspecciones WHERE id_transaction=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: inspecciones.php");
    } else {
        echo "Error al eliminar el registro: " . $conn->error;
    }   
}

// Acción de agregar o modificar
if (isset($_POST['save'])) {
    $id = $_POST['id_transaction'];
    $vehiculo = $_POST['vehiculo'];
    $cliente = $_POST['cliente'];
    $ralladuras = isset($_POST['ralladuras']) ? 1 : 0;
    $cantidad_combustible = $_POST['cantidad_combustible'];
    $goma_repuesta = isset($_POST['goma_repuesta']) ? 1 : 0;
    $gato = isset($_POST['gato']) ? 1 : 0;
    $roturas_cristal = isset($_POST['roturas_cristal']) ? 1 : 0;
    $estado_gomas = $_POST['estado_gomas'];
    $fecha = $_POST['fecha'];
    $empleado_inspeccion = $_POST['empleado_inspeccion'];
    $estado = isset($_POST['estado']) ? 1 : 0;

    if (!empty($id)) {
        // Modificar inspección
        $sql = "UPDATE inspecciones SET vehiculo='$vehiculo', cliente='$cliente', ralladuras='$ralladuras', cantidad_combustible='$cantidad_combustible', goma_repuesta='$goma_repuesta', gato='$gato', roturas_cristal='$roturas_cristal', estado_gomas='$estado_gomas', fecha='$fecha', empleado_inspeccion='$empleado_inspeccion', estado='$estado' WHERE id_transaction=$id";
    } else {
        // Agregar nueva inspección
        $sql = "INSERT INTO inspecciones (vehiculo, cliente, ralladuras, cantidad_combustible, goma_repuesta, gato, roturas_cristal, estado_gomas, fecha, empleado_inspeccion, estado) VALUES ('$vehiculo', '$cliente', '$ralladuras', '$cantidad_combustible', '$goma_repuesta', '$gato', '$roturas_cristal', '$estado_gomas', '$fecha', '$empleado_inspeccion', '$estado')";
    }
    $conn->query($sql) or die(mysqli_error($conn));

    // Si la inspección está activa, actualizar el estado del vehículo a activo
    if ($estado) {
        $sql_update_vehicle = "UPDATE vehiculos SET estado=1 WHERE id='$vehiculo'";
        $conn->query($sql_update_vehicle) or die(mysqli_error($conn));
    }

    header("Location: inspecciones.php");
}

// Obtener datos de la inspección seleccionada para edición
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM inspecciones WHERE id_transaction=$id";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $vehiculo = $row['vehiculo'];
        $cliente = $row['cliente'];
        $ralladuras = $row['ralladuras'];
        $cantidad_combustible = $row['cantidad_combustible'];
        $goma_repuesta = $row['goma_repuesta'];
        $gato = $row['gato'];
        $roturas_cristal = $row['roturas_cristal'];
        $estado_gomas = $row['estado_gomas'];
        $fecha = $row['fecha'];
        $empleado_inspeccion = $row['empleado_inspeccion'];
        $estado = $row['estado'];
    }
}

// Obtener lista de vehículos activos
$sql_vehiculos = "SELECT DISTINCT vehiculo FROM rentas_devoluciones";
$result_vehiculos = $conn->query($sql_vehiculos);

// Obtener lista de clientes activos
$sql_clientes = "SELECT DISTINCT cliente FROM rentas_devoluciones";
$result_clientes = $conn->query($sql_clientes);

// Obtener lista de inspecciones
$sql = "SELECT * FROM inspecciones";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>Inspecciones</h2>
    <form method="POST" action="" class="mb-4">
        <input type="hidden" name="id_transaction" value="<?php echo $id; ?>">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="vehiculo">Vehículo</label>
                <select class="form-control" name="vehiculo" id="vehiculo" required>
                    <option value="">Seleccione...</option>
                    <?php while ($row = $result_vehiculos->fetch_assoc()): ?>
                        <option value="<?php echo $row['vehiculo']; ?>" <?php echo $vehiculo == $row['vehiculo'] ? 'selected' : ''; ?>><?php echo $row['vehiculo']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="cliente">Cliente</label>
                <select class="form-control" name="cliente" id="cliente" required>
                    <option value="">Seleccione...</option>
                    <?php while ($row = $result_clientes->fetch_assoc()): ?>
                        <option value="<?php echo $row['cliente']; ?>" <?php echo $cliente == $row['cliente'] ? 'selected' : ''; ?>><?php echo $row['cliente']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="ralladuras">Ralladuras</label>
                <input type="checkbox" class="form-check-input" name="ralladuras" id="ralladuras" <?php echo $ralladuras ? 'checked' : ''; ?>>
                <label class="form-check-label" for="ralladuras">Sí</label>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="cantidad_combustible">Cantidad de Combustible</label>
                <select name="cantidad_combustible" class="form-control" id="cantidad_combustible">
                    <option value="1/4" <?php echo $cantidad_combustible == '1/4' ? 'selected' : ''; ?>>1/4</option>
                    <option value="1/2" <?php echo $cantidad_combustible == '1/2' ? 'selected' : ''; ?>>1/2</option>
                    <option value="3/4" <?php echo $cantidad_combustible == '3/4' ? 'selected' : ''; ?>>3/4</option>
                    <option value="lleno" <?php echo $cantidad_combustible == 'lleno' ? 'selected' : ''; ?>>Lleno</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="goma_repuesta">Goma Repuesta</label>
                <input type="checkbox" class="form-check-input" name="goma_repuesta" id="goma_repuesta" <?php echo $goma_repuesta ? 'checked' : ''; ?>>
                <label class="form-check-label" for="goma_repuesta">Sí</label>
            </div>
            <div class="form-group col-md-4">
                <label for="gato">Gato</label>
                <input type="checkbox" class="form-check-input" name="gato" id="gato" <?php echo $gato ? 'checked' : ''; ?>>
                <label class="form-check-label" for="gato">Sí</label>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="roturas_cristal">Roturas de Cristal</label>
                <input type="checkbox" class="form-check-input" name="roturas_cristal" id="roturas_cristal" <?php echo $roturas_cristal ? 'checked' : ''; ?>>
                <label class="form-check-label" for="roturas_cristal">Sí</label>
            </div>
            <div class="form-group col-md-4">
                <label for="estado_gomas">Estado de Gomas</label>
                <select name="estado_gomas" class="form-control" id="estado_gomas">
                    <option value="1" <?php echo $estado_gomas ? 'selected' : ''; ?>>Buen estado</option>
                    <option value="0" <?php echo !$estado_gomas ? 'selected' : ''; ?>>Mal estado</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="fecha">Fecha</label>
                <input type="date" class="form-control" name="fecha" id="fecha" value="<?php echo $fecha; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="empleado_inspeccion">Empleado Inspección</label>
                <input type="text" class="form-control" id="empleado_inspeccion" name="empleado_inspeccion" value="<?php echo $_SESSION['username']; ?>" readonly>
            </div>
            <div class="form-group col-md-4">
                <label for="estado">Estado</label>
                <input type="checkbox" class="form-check-input" name="estado" id="estado" <?php echo $estado ? 'checked' : ''; ?>>
                <label class="form-check-label" for="estado">Activo</label>
            </div>
        </div>
        <button type="submit" name="save" class="btn btn-primary">Guardar</button>
    </form>

    <h3>Lista de Inspecciones</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Vehículo</th>
                <th>Cliente</th>
                <th>Ralladuras</th>
                <th>Cantidad Combustible</th>
                <th>Goma Repuesta</th>
                <th>Gato</th>
                <th>Roturas Cristal</th>
                <th>Estado Gomas</th>
                <th>Fecha</th>
                <th>Empleado Inspección</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_transaction']; ?></td>
                    <td><?php echo $row['vehiculo']; ?></td>
                    <td><?php echo $row['cliente']; ?></td>
                    <td><?php echo $row['ralladuras'] ? 'Sí' : 'No'; ?></td>
                    <td><?php echo $row['cantidad_combustible']; ?></td>
                    <td><?php echo $row['goma_repuesta'] ? 'Sí' : 'No'; ?></td>
                    <td><?php echo $row['gato'] ? 'Sí' : 'No'; ?></td>
                    <td><?php echo $row['roturas_cristal'] ? 'Sí' : 'No'; ?></td>
                    <td><?php echo $row['estado_gomas'] ? 'Buen estado' : 'Mal estado'; ?></td>
                    <td><?php echo $row['fecha']; ?></td>
                    <td><?php echo $row['empleado_inspeccion']; ?></td>
                    <td><?php echo $row['estado'] ? 'Activo' : 'Inactivo'; ?></td>
                    <td>
                        <a href="?edit=<?php echo $row['id_transaction']; ?>" class="btn btn-warning">Editar</a>
                        <a href="?delete=<?php echo $row['id_transaction']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta inspección?');">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
include 'templates/footer.php';
$conn->close();
?>
