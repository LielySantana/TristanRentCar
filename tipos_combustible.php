<?php
include 'db.php';
include 'login-condition.php';

function generarIdDesdeDescripcion($descripcion) {
    return strtolower(str_replace(' ', '_', $descripcion));
}

// Acción de eliminar
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM tipos_combustible WHERE id='$id'";
    $conn->query($sql);
    header("Location: tipos_combustible.php");
}

// Acción de agregar o modificar
if (isset($_POST['save'])) {
    $descripcion = $_POST['descripcion'];
    $id = generarIdDesdeDescripcion($descripcion);
    $estado = isset($_POST['estado']) ? 1 : 0;

    if (!empty($_POST['id'])) {
        // Modificar tipo de combustible
        $id_editar = $_POST['id'];
        $sql = "UPDATE tipos_combustible SET descripcion='$descripcion', estado='$estado' WHERE id='$id_editar'";
    } else {
        // Agregar nuevo tipo de combustible
        $sql = "INSERT INTO tipos_combustible (id, descripcion, estado) VALUES ('$id', '$descripcion', '$estado')";
    }
    $conn->query($sql);
    header("Location: tipos_combustible.php");
}

// Obtener lista de tipos de combustible
$sql = "SELECT * FROM tipos_combustible";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>Tipos de Combustible</h2>
    <form method="POST" action="" class="mb-4">
        <input type="hidden" name="id" value="">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="descripcion">Descripción</label>
                <input type="text" class="form-control" name="descripcion" placeholder="Descripción" required>
            </div>
            <div class="form-group col-md-6 form-check align-self-end">
                <input type="checkbox" class="form-check-input" name="estado" id="estado">
                <label class="form-check-label" for="estado">Activo</label>
            </div>
        </div>
        <button type="submit" name="save" class="btn btn-primary">Guardar</button>
    </form>

    <?php
    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered'><thead class='thead-dark'><tr><th>ID</th><th>Descripción</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["descripcion"] . "</td>
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

    // Rellenar el formulario con los datos del tipo de combustible a editar
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $sql = "SELECT * FROM tipos_combustible WHERE id='$id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<script>
                document.querySelector('input[name=id]').value = '" . $row['id'] . "';
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
