<?php
 include 'db.php';
 include 'login-condition.php';

function generarIdCliente($nombre, $cedula) {
    $partes_nombre = explode(' ', $nombre);
    $iniciales = '';
    foreach ($partes_nombre as $parte) {
        $iniciales .= substr($parte, 0, 1);
    }
    $cedula = str_replace('-', '', $cedula);
    $ultimos_cuatro = substr($cedula, -4);
    $id_cliente = $iniciales . $ultimos_cuatro;
    return $id_cliente;
}

// Accion de eliminar
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM empleados WHERE id='$id'";
    $conn->query($sql);
    header("Location: empleados.php");
}

// Accion de agregar o modificar
if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $tanda_laboral = $_POST['tanda_laboral'];
    $porcentaje_comision = $_POST['porcentaje_comision'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $rol = $_POST['rol'];
    $estado = isset($_POST['estado']) ? 1 : 0;

    if (!empty($id)) {
        // Modificar empleados
        $sql = "UPDATE empleados SET nombre='$nombre', cedula='$cedula', tanda_laboral='$tanda_laboral', porcentaje_comision='$porcentaje_comision', fecha_ingreso='$fecha_ingreso', rol='$rol', estado='$estado' WHERE id='$id'";
    } else {
        // Agregar nuevo empleados
        $id = generarIdCliente($nombre, $cedula);
        $sql = "INSERT INTO empleados (id, nombre, cedula, tanda_laboral, porcentaje_comision, fecha_ingreso, rol, estado) VALUES ('$id', '$nombre', '$cedula', '$tanda_laboral', '$porcentaje_comision', '$fecha_ingreso', '$rol', '$estado')";
    }

    $conn->query($sql);
    header("Location: empleados.php");
}

// Obtener los empleados para mostrar en la tabla
$sql = "SELECT * FROM empleados";
$result = $conn->query($sql);
?>

<!-- Formulario de inserción/edición de datos -->
<div class="container mt-5">
    <h2>Empleados</h2>
    <form method="POST" action="" class="mb-4">
        <input type="hidden" name="id" value="">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" name="nombre" placeholder="Nombre" required>
            </div>
            <div class="form-group col-md-4">
                <label for="cedula">Cédula</label>
                <input type="text" class="form-control" name="cedula" placeholder="Cédula" required>
            </div>
            <div class="form-group col-md-4">
                <label for="tanda_laboral">Tanda Laboral</label>
                <select name="tanda_laboral" class="form-control">
                    <option value="Matutina">Matutina</option>
                    <option value="Vespertina">Vespertina</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="porcentaje_comision">Porcentaje de Comisión</label>
                <input type="text" class="form-control" name="porcentaje_comision" placeholder="Porcentaje de Comisión" required>
            </div>
            <div class="form-group col-md-4">
                <label for="fecha_ingreso">Fecha de Ingreso</label>
                <input type="date" class="form-control" name="fecha_ingreso" required>
            </div>
            <div class="form-group col-md-4">
                <label for="rol">Rol</label>
                <select name="rol" class="form-control">
                    <option value="admin">admin</option>
                    <option value="user">user</option>
                </select>
            </div>
            <div class="form-group col-md-4 form-check align-self-end">
                <input type="checkbox" class="form-check-input" name="estado" id="estado">
                <label class="form-check-label" for="estado">Activo</label>
            </div>
        </div>
        <button type="submit" name="save" class="btn btn-primary">Guardar</button>
    </form>

    <?php
    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered'><thead class='thead-dark'><tr><th>ID</th><th>Nombre</th><th>Cédula</th><th>Tanda Laboral</th><th>Porcentaje de Comisión</th><th>Fecha de Ingreso</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["nombre"] . "</td>
                <td>" . $row["cedula"] . "</td>
                <td>" . $row["tanda_laboral"] . "</td>
                <td>" . $row["porcentaje_comision"] . "</td>
                <td>" . $row["fecha_ingreso"] . "</td>
                <td>" . $row["rol"] . "</td>
                <td>" . ($row["estado"] ? "Activo" : "Inactivo") . "</td>
                <td>
                    <a href='?edit=" . $row["id"] . "' class='btn btn-warning btn-sm'>Editar</a>
                    <a href='?delete=" . $row["id"] . "' class='btn btn-danger btn-sm'>Eliminar</a>
                </td>
            </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<div class='alert alert-info'>No se encontraron resultados.</div>";
    }

    // Rellenar el formulario con los datos del empleado a editar
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $sql = "SELECT * FROM empleados WHERE id='$id'";
        $result_edit = $conn->query($sql);
        if ($result_edit->num_rows > 0) {
            $row = $result_edit->fetch_assoc();
            echo "<script>
                document.querySelector('input[name=id]').value = '" . $row['id'] . "';
                document.querySelector('input[name=nombre]').value = '" . $row['nombre'] . "';
                document.querySelector('input[name=cedula]').value = '" . $row['cedula'] . "';
                document.querySelector('select[name=tanda_laboral]').value = '" . $row['tanda_laboral'] . "';
                document.querySelector('input[name=porcentaje_comision]').value = '" . $row['porcentaje_comision'] . "';
                document.querySelector('input[name=fecha_ingreso]').value = '" . $row['fecha_ingreso'] . "';
                document.querySelector('select[name=rol]').value = '" . $row['rol'] . "';
                document.querySelector('input[name=estado]').checked = " . ($row['estado'] ? 'true' : 'false') . ";
            </script>";
        }
    }
    ?>
</div>
<br><br><br>
<?php
include 'templates/footer.php';
$conn->close();
?>
