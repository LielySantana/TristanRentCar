<?php
include 'db.php';

header('Content-Type: application/json');

if (isset($_GET['vehiculo'])) {
    $vehiculo_id = $_GET['vehiculo'];
    $sql = "SELECT * FROM rentas_devoluciones WHERE vehiculo='$vehiculo_id'";
} elseif (isset($_GET['cliente'])) {
    $cliente_id = $_GET['cliente'];
    $sql = "SELECT * FROM rentas_devoluciones WHERE cliente='$cliente_id'";
}

$result = $conn->query($sql);
$data = $result->fetch_assoc();

echo json_encode($data);

$conn->close();
?>
