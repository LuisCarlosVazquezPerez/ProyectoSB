<?php
//--TRAER LOS REGISTROS DE LA BASE DE DATOS

//1-IMPORTAR LA CONEXION
require './includes/config/database.php';
$db = conectarDB();


//2-ESCRIBIR EL QUERY
$query = 'SELECT * FROM propiedad';

//3- CONSULTAR LA BD
$resultadoConsulta = mysqli_query($db, $query);



//------------------------------------------------------------------------------------------------------------------------------------
//MUESTRA MSJ CONDICIONAL (ANUNCIO CREADO CORRECTAMENTE)
$resultado = $_GET['resultado'];
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
                                <a href="" class="boton-rojo">Eliminar</a>
                                <a href="/actualizarP.php?id=<?php echo $propiedad['id'] ?>" class="boton-verde">Actualizar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>


            </table>

        </div>
    </main>

    <!-- CERRAR LA CONEXION A LA BD  -->
    <?php mysqli_close($db); ?>

    <footer class="pie">
        <p>Todos Los Derechos Reservados por SoftBrothers</p>
    </footer>
</body>

</html>