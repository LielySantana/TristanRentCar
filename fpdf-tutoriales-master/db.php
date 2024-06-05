<?php
// En db.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tristanrentcar";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

?>



<!-- Base de datos query mySql  -->

<!-- Login

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL
);

CREATE TABLE tipos_vehiculo (
    id VARCHAR(100) PRIMARY KEY,
    descripcion VARCHAR(255),
    estado BOOLEAN
);

CREATE TABLE marcas (
    id VARCHAR(100) PRIMARY KEY,
    descripcion VARCHAR(255),
    estado BOOLEAN
);

CREATE TABLE modelos (
    id VARCHAR(100) PRIMARY KEY,
    id_marca VARCHAR(100),
    descripcion VARCHAR(255),
    estado BOOLEAN,
    FOREIGN KEY (id_marca) REFERENCES marcas(id)
);

CREATE TABLE tipos_combustible (
    id VARCHAR(100) PRIMARY KEY,
    descripcion VARCHAR(255),
    estado BOOLEAN
);

CREATE TABLE vehiculos (
    id VARCHAR(100) PRIMARY KEY,
    descripcion VARCHAR(255),
    no_chasis VARCHAR(255),
    no_motor VARCHAR(255),
    no_placa VARCHAR(255),
    tipo_vehiculo VARCHAR(100),
    marca VARCHAR(100),
    modelo VARCHAR(100),
    tipo_combustible VARCHAR(100),
    imagen VARCHAR(255),
    estado BOOLEAN,
    FOREIGN KEY (tipo_vehiculo) REFERENCES tipos_vehiculo(id),
    FOREIGN KEY (marca) REFERENCES marcas(id),
    FOREIGN KEY (modelo) REFERENCES modelos(id),
    FOREIGN KEY (tipo_combustible) REFERENCES tipos_combustible(id)
);

CREATE TABLE clientes (
    id VARCHAR(100) PRIMARY KEY,
    nombre VARCHAR(255),
    cedula VARCHAR(255),
    no_tarjeta_cr VARCHAR(255),
    limite_credito DECIMAL(10, 2),
    tipo_persona ENUM('fisica', 'juridica'),
    estado BOOLEAN
);

CREATE TABLE empleados (
    id VARCHAR(100) PRIMARY KEY,
    nombre VARCHAR(255),
    cedula VARCHAR(255),
    tanda_laboral ENUM('matutina', 'vespertina'),
    porcentaje_comision DECIMAL(5, 2),
    fecha_ingreso DATE,
    rol VARCHAR(255) NOT NULL,
    estado BOOLEAN
);


CREATE TABLE inspecciones (
    id_transaction INT AUTO_INCREMENT PRIMARY KEY,
    vehiculo VARCHAR(100),
    cliente VARCHAR(100),
    ralladuras BOOLEAN,
    cantidad_combustible ENUM('1/4', '1/2', '3/4', 'lleno'),
    goma_repuesta BOOLEAN,
    gato BOOLEAN,
    roturas_cristal BOOLEAN,
    estado_gomas BOOLEAN,
    fecha DATE,
    empleado_inspeccion VARCHAR(100),
    estado BOOLEAN,
    FOREIGN KEY (vehiculo) REFERENCES vehiculos(id),
    FOREIGN KEY (cliente) REFERENCES clientes(id),
    FOREIGN KEY (empleado_inspeccion) REFERENCES empleados(id)
);

CREATE TABLE rentas_devoluciones (
    no_renta INT AUTO_INCREMENT PRIMARY KEY,
    empleado VARCHAR(100),
    vehiculo VARCHAR(100),
    cliente VARCHAR(100),
    fecha_renta DATE,
    fecha_devolucion DATE,
    monto_por_dia DECIMAL(10, 2),
    cantidad_dias INT,
    comentario TEXT,
    estado BOOLEAN,
    FOREIGN KEY (empleado) REFERENCES empleados(id),  -- Modificado para apuntar a id en vez de nombre
    FOREIGN KEY (vehiculo) REFERENCES vehiculos(id),
    FOREIGN KEY (cliente) REFERENCES clientes(id)     -- Modificado para apuntar a id en vez de nombre
);

-->