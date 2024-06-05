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
    $sql = "DELETE FROM clientes WHERE id='$id'";
    $conn->query($sql);
    header("Location: clientes.php");
}

// Accion de agregar o modificar
if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $no_tarjeta_cr = $_POST['no_tarjeta_cr'];
    $limite_credito = $_POST['limite_credito'];
    $tipo_persona = $_POST['tipo_persona'];
    $estado = isset($_POST['estado']) ? 1 : 0;

    if (!empty($id)) {
        // Modificar cliente
        $sql = "UPDATE clientes SET nombre='$nombre', cedula='$cedula', no_tarjeta_cr='$no_tarjeta_cr', limite_credito='$limite_credito', tipo_persona='$tipo_persona', estado='$estado' WHERE id='$id'";
    } else {
        // Agregar nuevo cliente
        $id = generarIdCliente($nombre, $cedula);
        $sql = "INSERT INTO clientes (id, nombre, cedula, no_tarjeta_cr, limite_credito, tipo_persona, estado) VALUES ('$id', '$nombre', '$cedula', '$no_tarjeta_cr', '$limite_credito', '$tipo_persona', '$estado')";
    }

    $conn->query($sql);
    header("Location: clientes.php");
}

// Obtener los clientes para mostrar en la tabla
$sql = "SELECT * FROM clientes";
$result = $conn->query($sql);
?>

<!-- Formulario de inserción/edición de datos -->
<div class="container mt-5">
    <h2>Clientes</h2>
    <form method="POST" action="" class="mb-4">
        <input type="hidden" name="id" value="">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" placeholder="Nombre" required>
            </div>
            <div class="form-group col-md-4">
                <label for="cedula" class="form-label">Cédula o RNC</label>
                <input type="text" class="form-control" name="cedula" placeholder="Cédula o RNC" required>
            </div>
            <div class="form-group col-md-4">
                <label for="no_tarjeta_cr" class="form-label">No. Tarjeta CR</label>
                <input type="text" class="form-control" name="no_tarjeta_cr" placeholder="No. Tarjeta CR" required>
            </div>
            <div class="form-group col-md-4">
                <label for="limite_credito" class="form-label">Límite de Crédito</label>
                <input type="text" class="form-control" name="limite_credito" placeholder="Límite de Crédito" required>
            </div>
            <div class="form-group col-md-4">
                <label for="tipo_persona" class="form-label">Tipo de Persona</label>
                <select name="tipo_persona" class="form-select">
                    <option value="fisica">Física</option>
                    <option value="juridica">Jurídica</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <div class="form-check mt-4 align-self-end">
                    <input type="checkbox" class="form-check-input" name="estado" id="estado">
                    <label class="form-check-label" for="estado">Activo</label>
                </div>
            </div>
        </div>
        <button type="submit" name="save" class="btn btn-primary mt-3">Guardar</button>
    </form>

    <?php
    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered'><thead class='thead-dark'><tr><th>ID</th><th>Nombre</th><th>Cédula</th><th>No. Tarjeta de Crédito</th><th>Límite de crédito</th><th>Tipo de persona</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["nombre"] . "</td>
                <td>" . $row["cedula"] . "</td>
                <td>" . $row["no_tarjeta_cr"] . "</td>
                <td>" . $row["limite_credito"] . "</td>
                <td>" . $row["tipo_persona"] . "</td>
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

    // Rellenar el formulario con los datos del cliente a editar
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $sql = "SELECT * FROM clientes WHERE id='$id'";
        $result_edit = $conn->query($sql);
        if ($result_edit->num_rows > 0) {
            $row = $result_edit->fetch_assoc();
            echo "<script>
                document.querySelector('input[name=id]').value = '" . $row['id'] . "';
                document.querySelector('input[name=nombre]').value = '" . $row['nombre'] . "';
                document.querySelector('input[name=cedula]').value = '" . $row['cedula'] . "';
                document.querySelector('input[name=no_tarjeta_cr]').value = '" . $row['no_tarjeta_cr'] . "';
                document.querySelector('select[name=limite_credito]').value = '" . $row['limite_credito'] . "';
                document.querySelector('select[name=tipo_persona]').value = '" . $row['tipo_persona'] . "';
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
