<?php
include 'db.php';
include 'login-condition.php';

function generarIdDesdeDescripcion($descripcion) {
    return strtolower(str_replace(' ', '_', $descripcion));
}

// Acción de eliminar
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM modelos WHERE id='$id'";
    $conn->query($sql);
    header("Location: modelos.php");
}

// Acción de agregar o modificar
if (isset($_POST['save'])) {
    $id_marca = $_POST['id_marca'];
    $descripcion = $_POST['descripcion'];
    $id = generarIdDesdeDescripcion($descripcion);
    $estado = isset($_POST['estado']) ? 1 : 0;

    if (!empty($_POST['id'])) {
        // Modificar modelo
        $id_editar = $_POST['id'];
        $sql = "UPDATE modelos SET id_marca='$id_marca', descripcion='$descripcion', estado='$estado' WHERE id='$id_editar'";
    } else {
        // Agregar nuevo modelo
        $sql = "INSERT INTO modelos (id, id_marca, descripcion, estado) VALUES ('$id', '$id_marca', '$descripcion', '$estado')";
    }
    $conn->query($sql);
    header("Location: modelos.php");
}

// Obtener lista de marcas activas
$sql_marcas = "SELECT id, descripcion FROM marcas WHERE estado=1";
$result_marcas = $conn->query($sql_marcas);

// Obtener lista de modelos
$sql = "SELECT * FROM modelos";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>Modelos</h2>
    <form method="POST" action="" class="mb-4">
        <input type="hidden" name="id" value="">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="id_marca">Marca</label>
                <select class="form-control" name="id_marca" required>
                    <option value="">Seleccione...</option>
                    <?php while ($row = $result_marcas->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['descripcion']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="descripcion">Descripción</label>
                <input type="text" class="form-control" name="descripcion" placeholder="Descripción" required>
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
        echo "<table class='table table-bordered'><thead class='thead-dark'><tr><th>ID</th><th>ID Marca</th><th>Descripción</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["id_marca"] . "</td>
                <td>" . $row["descripcion"] . "</td>
                <td>" . ($row["estado"] ? "Activo" : "Inactivo") . "</td>
                <td>
                    <a href='modelos.php?edit=" . $row["id"] . "' class='btn btn-warning btn-sm'>Editar</a>
                    <a href='modelos.php?delete=" . $row["id"] . "' class='btn btn-danger btn-sm'>Eliminar</a>
                </td>
            </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<div class='alert alert-info'>No se encontraron resultados.</div>";
    }

    // Rellenar el formulario con los datos del modelo a editar
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $sql = "SELECT * FROM modelos WHERE id='$id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<script>
                document.querySelector('input[name=id]').value = '" . $row['id'] . "';
                document.querySelector('select[name=id_marca]').value = '" . $row['id_marca'] . "';
                document.querySelector('input[name=descripcion]').value = '" . $row['descripcion'] . "';
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
