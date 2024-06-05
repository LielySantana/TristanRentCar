<?php
include 'db.php';
include 'login-condition.php';

function generarIdDesdeDescripcion($descripcion) {
    $palabras = explode(' ', $descripcion);
    $id = '';

    foreach ($palabras as $palabra) {
        $id .= substr($palabra, 0, 2);
    }

    $id = strtolower(str_replace(' ', '', $id));

    return $id;
}

// Acción de eliminar
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM vehiculos WHERE id='$id'";
    $conn->query($sql);
    header("Location: vehiculos.php");
}

// Acción de agregar o modificar
if (isset($_POST['save'])) {
    $descripcion = $_POST['descripcion'];
    $id = generarIdDesdeDescripcion($descripcion);
    $no_chasis = $_POST['no_chasis'];
    $no_motor = $_POST['no_motor'];
    $no_placa = $_POST['no_placa'];
    $tipo_vehiculo = $_POST['tipo_vehiculo'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $tipo_combustible = $_POST['tipo_combustible'];
    $imagen = $_POST['imagen'];
    $estado = isset($_POST['estado']) ? 1 : 0;

    if (!empty($_POST['id'])) {
        // Modificar vehículo
        $id_editar = $_POST['id'];
        $sql = "UPDATE vehiculos SET descripcion='$descripcion', no_chasis='$no_chasis', no_motor='$no_motor', no_placa='$no_placa', tipo_vehiculo='$tipo_vehiculo', marca='$marca', modelo='$modelo', tipo_combustible='$tipo_combustible', imagen='$imagen', estado='$estado' WHERE id='$id_editar'";
    } else {
        // Agregar nuevo vehículo
        $sql = "INSERT INTO vehiculos (id, descripcion, no_chasis, no_motor, no_placa, tipo_vehiculo, marca, modelo, tipo_combustible, imagen, estado) VALUES ('$id', '$descripcion', '$no_chasis', '$no_motor', '$no_placa', '$tipo_vehiculo', '$marca', '$modelo', '$tipo_combustible', '$imagen', '$estado')";
    }
    $conn->query($sql);
    header("Location: vehiculos.php");
    
}

// Obtener lista de tipos de vehículo activos
$sql_tipos_vehiculo = "SELECT id, descripcion FROM tipos_vehiculo WHERE estado=1";
$result_tipos_vehiculo = $conn->query($sql_tipos_vehiculo);

// Obtener lista de marcas activas
$sql_marcas = "SELECT id, descripcion FROM marcas WHERE estado=1";
$result_marcas = $conn->query($sql_marcas);

// Obtener lista de modelos activos
$sql_modelos = "SELECT id, descripcion FROM modelos WHERE estado=1";
$result_modelos = $conn->query($sql_modelos);

// Obtener lista de tipos de combustible activos
$sql_tipos_combustible = "SELECT id, descripcion FROM tipos_combustible WHERE estado=1";
$result_tipos_combustible = $conn->query($sql_tipos_combustible);

// Obtener lista de vehículos
$sql = "SELECT * FROM vehiculos";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>Vehículos</h2>
    <form method="POST" action="" class="mb-4">
        <input type="hidden" name="id" value="">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="descripcion">Descripción</label>
                <input type="text" class="form-control" name="descripcion" placeholder="Descripción" required>
            </div>
            <div class="form-group col-md-4">
                <label for="no_chasis">No. de Chasis</label>
                <input type="text" class="form-control" name="no_chasis" placeholder="No. de Chasis" required>
            </div>
            <div class="form-group col-md-4">
                <label for="no_motor">No. de Motor</label>
                <input type="text" class="form-control" name="no_motor" placeholder="No. de Motor" required>
            </div>
            <div class="form-group col-md-4">
                <label for="no_placa">No. de Placa</label>
                <input type="text" class="form-control" name="no_placa" placeholder="No. de Placa" required>
            </div>
            <div class="form-group col-md-4">
                <label for="tipo_vehiculo">Tipo de Vehículo</label>
                <select class="form-control" name="tipo_vehiculo" required>
                    <option value="">Seleccione...</option>
                    <?php while($row = $result_tipos_vehiculo->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['descripcion']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="marca">Marca</label>
                <select class="form-control" name="marca" required>
                    <option value="">Seleccione...</option>
                    <?php while($row = $result_marcas->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['descripcion']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="modelo">Modelo</label>
                <select class="form-control" name="modelo" required>
                    <option value="">Seleccione...</option>
                    <?php while($row = $result_modelos->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['descripcion']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="tipo_combustible">Tipo de Combustible</label>
                <select class="form-control" name="tipo_combustible" required>
                    <option value="">Seleccione...</option>
                    <?php while($row = $result_tipos_combustible->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['descripcion']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="imagen">Link de imagen</label>
                <input type="text" class="form-control" name="imagen" placeholder="Vinculo de Imagen" required>
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
        echo "<table class='table table-bordered'><thead class='thead-dark'><tr><th>ID</th><th>Descripción</th><th>No. de Chasis</th><th>No. de Motor</th><th>No. de Placa</th><th>Tipo de Vehículo</th><th>Marca</th><th>Modelo</th><th>Tipo de Combustible</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["descripcion"] . "</td>
                <td>" . $row["no_chasis"] . "</td>
                <td>" . $row["no_motor"] . "</td>
                <td>" . $row["no_placa"] . "</td>
                <td>" . $row["tipo_vehiculo"] . "</td>
                <td>" . $row["marca"] . "</td>
                <td>" . $row["modelo"] . "</td>
                <td>" . $row["tipo_combustible"] . "</td>
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

    // Rellenar el formulario con los datos del vehículo a editar
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $sql = "SELECT * FROM vehiculos WHERE id='$id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<script>
                document.querySelector('input[name=id]').value = '" . $row['id'] . "';
                document.querySelector('input[name=descripcion]').value = '" . $row['descripcion'] . "';
                document.querySelector('input[name=no_chasis]').value = '" . $row['no_chasis'] . "';
                document.querySelector('input[name=no_motor]').value = '" . $row['no_motor'] . "';
                document.querySelector('input[name=no_placa]').value = '" . $row['no_placa'] . "';
                document.querySelector('input[name=tipo_vehiculo]').value = '" . $row['tipo_vehiculo'] . "';
                document.querySelector('select[name=marca]').value = '" . $row['marca'] . "';
                document.querySelector('select[name=modelo]').value = '" . $row['modelo'] . "';
                document.querySelector('select[name=tipo_combustible]').value = '" . $row['tipo_combustible'] . "';
                document.querySelector('input[name=imagen]').value = '" . $row['imagen'] . "';
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
