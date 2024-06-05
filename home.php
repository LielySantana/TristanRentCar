
<?php 
   include 'login-condition.php';
    ?>
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent a Car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="10000">
                <img src="https://www.dchacademyhonda.com/static/brand-honda/vehicle/2024/Honda/CR-V/MRP/01.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item" data-bs-interval="2000">
                <img src="https://img.sm360.ca/ir/w1280h1024/images/article/groupe-honda-de-terrebonne/122384//honda_civic_style_landscapecard_desktop1700753334491.png" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="https://www.kia.com/content/dam/kia/us/en/vehicles/sorento-hev/2024/mep/in-page-gallery/kia_sorento-hev_2024_asset-carousel-1.jpg" class="d-block w-100" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <main class="container mt-5">
        <h2>Vehículos Disponibles</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
                include 'db.php'; 

                $sql = "SELECT id, descripcion, imagen, estado FROM vehiculos WHERE estado = 1";
                $result = $conn->query($sql);

                $vehiculos = [];

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $precio_renta = 70.00; 

                        $vehiculo = [
                            'id' => $row['id'],
                            'imagen' => $row['imagen'],
                            'descripcion' => $row['descripcion'],
                            'precio_renta' => $precio_renta, 
                        ];

                        $vehiculos[] = $vehiculo;
                    }
                }

                $conn->close(); 

                foreach ($vehiculos as $vehiculo) {
                    echo '<div class="col">
                            <div class="card h-100">
                                <img src="' . $vehiculo['imagen'] . '" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">' . $vehiculo['descripcion'] . '</h5>
                                    <p class="card-text">Precio de renta por día: $' . number_format($vehiculo['precio_renta'], 2) . '</p>
                                    <a href="rentas_devoluciones.php?id=' . $vehiculo['id'] . '" class="btn btn-primary">Rentar</a>
                                </div>
                            </div>
                        </div>';
                }
            ?>


        </div>
    </main>
    <br><br><br>

    <?php include 'templates/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
