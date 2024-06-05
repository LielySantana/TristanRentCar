<?php
include 'db.php';
include 'login-condition.php';


function generarIdDesdeDescripcion($descripcion) {
    return strtolower(str_replace(' ', '_', $descripcion));
}

// Acción de eliminar
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM marcas WHERE id='$id'";
    $conn->query($sql);
    header("Location: marcas.php"); 
    
}

// Acción de agregar o modificar
if (isset($_POST['save'])) {
    $descripcion = $_POST['descripcion'];
    $id = generarIdDesdeDescripcion($descripcion);
    $estado = isset($_POST['estado']) ? 1 : 0;

    if (!empty($_POST['id'])) {
        // Modificar marca
        $id_editar = $_POST['id'];
        $sql = "UPDATE marcas SET descripcion='$descripcion', estado='$estado' WHERE id='$id_editar'";
    } else {
        // Agregar nueva marca
        $sql = "INSERT INTO marcas (id, descripcion, estado) VALUES ('$id', '$descripcion', '$estado')";
    }
    $conn->query($sql);
    header("Location: marcas.php"); 
}

// Mostrar marcas
$sql = "SELECT * FROM marcas";
$result = $conn->query($sql);
?>


<div class="container mt-5">
    <h2>Marcas</h2>
    <form method="POST" action="" class="mb-4">
        <input type="hidden" name="id" value="">
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <input type="text" class="form-control" name="descripcion" placeholder="Descripción" required>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="estado" id="estado">
            <label class="form-check-label" for="estado">Activo</label>
        </div>
        <button type="submit" name="save" class="btn btn-primary">Guardar</button>
    </form>

    <?php
    if ($result->num_rows > 0) {
        echo "<table class='table'><tr><th>ID</th><th>Descripción</th><th>Estado</th><th>Acciones</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
            <td>" . $row["id"] . "</td>
            <td>" . $row["descripcion"] . "</td>
            <td>" . ($row["estado"] ? "Activo" : "Inactivo") . "</td>
            <td>
                <a href='marcas.php?edit=" . $row['id'] . "' class='btn btn-warning btn-sm'>Editar</a>
                <a href='marcas.php?delete=" . $row['id'] . "' class='btn btn-danger btn-sm'>Eliminar</a>
            </td></tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='alert alert-info'>No se encontraron resultados.</div>";
    }

    // Llenar el formulario con los datos de la marca a editar
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $sql = "SELECT * FROM marcas WHERE id='$id'";
        $result_edit = $conn->query($sql);
        if ($result_edit->num_rows > 0) {
            $row = $result_edit->fetch_assoc();
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
