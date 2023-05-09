<?php

//--TRAER LOS REGISTROS DE LA BASE DE DATOS

//1-IMPORTAR LA CONEXION
require './includes/config/database.php';
$db = conectarDB();


//2-ESCRIBIR EL QUERY
$query = 'SELECT * FROM propiedad';

//3- CONSULTAR LA BD
$resultadoConsulta = mysqli_query($db, $query);

//-----------------------------------------------------------------------------------------------------------------------------------

$registrosPorPagina = 5; // Definir el número de registros a mostrar por página
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; // Obtener la página actual
$inicio = ($paginaActual - 1) * $registrosPorPagina; // Calcular el inicio del registro

if ($paginaActual < 1 || !is_numeric($paginaActual)) {
    // Si el valor de la página actual es menor que 1, redirigir al usuario a la primera página
    header("Location: ?pagina=1");
    exit;
}

// Consulta SQL modificada para agregar la cláusula LIMIT
$consulta = "SELECT * FROM propiedad LIMIT $inicio, $registrosPorPagina";
$resultadoConsulta = mysqli_query($db, $consulta);

// Obtener el número total de registros
$totalRegistros = mysqli_num_rows(mysqli_query($db, "SELECT * FROM propiedad"));


//------------------------------------------------------------------------------------------------------------------------------------
//MUESTRA MSJ CONDICIONAL (ANUNCIO CREADO CORRECTAMENTE)
$resultado = $_GET['resultado'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if ($id) {

        //ELIMINAR LA IMAGEN (archivo)
        $query = "SELECT imagen FROM propiedad WHERE id = ${id}"; //BUSCAMOS LA IMAGEN EN LA BD
        $resultado = mysqli_query($db, $query);                 //LE PASAMOS LA CONEXION Y EL QUERY
        $propiedad = mysqli_fetch_assoc($resultado);           //NOS TRAE UN ARREGLO CON LA IMAGEN
        //var_dump($propiedad); 
        unlink('imagenes/' . $propiedad['imagen']);            //NOS PERMITE ELIMINARLA
        //exit;

        //ELIMINAR LA PROPIEDAD
        $query = "DELETE FROM propiedad WHERE id = ${id}";

        $resultado = mysqli_query($db, $query);
        if ($resultado) {
            header('Location: /adminP.php?resultado=3');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Bayon&family=Francois+One&family=Tajawal:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/build/css/app.css">
    <title>Administrador de propiedades</title>
</head>

<body>

    <header class="titulo adminP">
        <div class="titulo__texto">
            <h2>BienesRaices SoftBrothers</h2>
            <p>Administra Propiedades</p>
        </div>
    </header>

    <main>
        <div class="contenido">

            <?php if (intval($resultado) === 1) : ?>
                <p class="alerta exito">Anuncio Creado Correctamente</p>

            <?php elseif (intval($resultado) === 2) : ?>
                <p class="alerta exito">Anuncio Actualizado Correctamente</p>

            <?php elseif (intval($resultado) === 3) : ?>
                <p class="alerta exito">Anuncio Eliminado Correctamente</p>

            <?php endif; ?>

            <a href="/propiedad.php"><button class="boton-adminP">Nueva Propiedad</button></a>

            <table class="propiedades">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titulo</th>
                        <th>Imagen</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody> <!-- MOSTRAR LOS RESULTADOS -->
                    <?php while ($propiedad = mysqli_fetch_assoc($resultadoConsulta)) : ?> <!-- PARA RECORRER LAS CONSULTAS -->
                        <tr class="tr-centrar">
                            <td> <?php echo $propiedad['id'] ?> </td>
                            <td> <?php echo $propiedad['titulo'] ?> </td>
                            <td><img src="/imagenes/<?php echo $propiedad['imagen'] ?>" class="imagen-tabla"></td>
                            <td>$ <?php echo $propiedad['precio'] ?></td>
                            <td>
                                <form method="POST" class="margin-0" id="eliminarForm" onsubmit="return confirmarEliminacion(event)">
                                    <input type="hidden" name="id" value='<?php echo $propiedad['id'] ?>'> <!-- NO ES VISIBLE PERO TRAE EL ID QUE SE QUIERE ELIMINAR -->
                                    <input type="submit" value="Eliminar" class="boton-rojo">
                                </form>
                                <a href="/actualizarP.php?id=<?php echo $propiedad['id'] ?>" class="boton-verde">Actualizar</a>
                            </td>
                            <td>
                                <?php if ($propiedad['estadoPropiedad'] == 1) : ?>
                                    <a id="boton-vendida" class="boton-verde--estado" href="status.php?id=<?php echo $propiedad['id']; ?>&estadoPropiedad=0" onclick="cambiarEstado(event)">
                                        Publicada
                                    </a>
                                <?php else : ?>
                                    <a class="boton-rojo--estado" href="status.php?id=<?php echo $propiedad['id']; ?>&estadoPropiedad=1" onclick="cambiarEstado(event)">
                                        Vendida
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>


            <?php if ($totalRegistros > 5) {
                // Calcular el número total de páginas
                $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

                if ($paginaActual > $totalPaginas) {
                    // Si el usuario ingresa un valor mayor que el número de páginas, redirigir al último registro de la página
                    header("Location: ?pagina=$totalPaginas");
                    exit;
                }
            ?>

                <!-- Mostrar los enlaces de paginación -->
                <div class="paginacion">

                    <?php if ($paginaActual > 1) : ?>
                        <a class="anterior-siguiente" href="?pagina=<?php echo $paginaActual - 1; ?>">Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                        <?php if ($i == $paginaActual) : ?>
                            <span class="pagina-actual"><?php echo $i; ?></span>
                        <?php else : ?>
                            <a href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas) : ?>
                        <a class="anterior-siguiente" href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente</a>
                    <?php endif; ?>
                </div>

            <?php } ?>

        </div>
    </main>

    <!-- CERRAR LA CONEXION A LA BD  -->
    <?php mysqli_close($db); ?>

    <footer class="pie">
        <p>Todos Los Derechos Reservados por SoftBrothers</p>
    </footer>

    <script defer src="/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script defer src="/script.js"></script>
</body>

</html>