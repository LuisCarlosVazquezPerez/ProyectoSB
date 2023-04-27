<?php
//BASE DE DATOS CONECTARLA
require './includes/config/database.php';
$db = conectarDB();

//ARREGLO CON MENSAJES DE ERRORES
$errores = [];

//VARIABLES SE CREAN VACIAS
$titulo = '';
$descripcion = '';
$precio = '';
$coloniaCasa = '';
$calleCasa = '';
$numCasa = '';

//EJECUTA EL CODIGO DEDSPUES DE QUE EL USUARIO ENVIA EL FORMULARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {  //VALIDA QUE SEA POST

    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";

    // echo "<pre>";
    // var_dump($_FILES);
    // echo "</pre>";

    //exit; //PARA QUE NO SE EJECUTE EL CODIGO DE ABAJO

    //SE LES ASIGNA EL VALOR
    $titulo = mysqli_real_escape_string($db, $_POST['titulo']);  //[name='titulo'];
    $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);   //mysqli_real_escape_string es para evitar inyeccion sql.
    $precio = mysqli_real_escape_string($db, $_POST['precio']);
    $coloniaCasa = mysqli_real_escape_string($db, $_POST['coloniaCasa']);
    $calleCasa = mysqli_real_escape_string($db, $_POST['calleCasa']);
    $numCasa = mysqli_real_escape_string($db, $_POST['numCasa']);
    $creado = date('Y/m/d'); //INGRESA LA FECHA AUTOMATICAMENTE
    $estadoPropiedad = 1 ; //AGREGA POR DEFAULT EN LA COLUMA ESTADO 'PUBLICADA'
    $imagen = $_FILES['imagen']; //ASIGNAR LA IMAGEN A UNA VARIABLE

    if (!$titulo) {
        $errores[] = 'Debes agregar un titulo';
    }
    if (!$descripcion) {
        $errores[] = 'Debes agregar una descripcion';
    }
    if (!$precio) {
        $errores[] = 'Debes agregar un precio';
    }
    if (!$coloniaCasa) {
        $errores[] = 'Debes agregar una colonia';
    }
    if (!$calleCasa) {
        $errores[] = 'Debes agregar una calle';
    }
    if (!$numCasa) {
        $errores[] = 'Debes agregar el numero de casa';
    }
    if (!$imagen['name'] || $imagen['error']) { //Si la imagen no existe o no la agregan || si la imagen se pasa de los 2 mb
        $errores[] = 'Debes agregar una imagen'; //PHP tiene limitado de 2 mb
        //Si se pasa de dos megas te retorna un error
    }
    //VALIDAR POR SIZE DE LA IMAGEN (1 mb maximo)
    $medida = 1000 * 1000; //LOS DATOS LOS MIDE EN BYTES, 1000000 bytes = 1mb
    if ($imagen['size'] > $medida) {
        $errores[] = 'La imagen es muy pesada';
    }



    //REVISAR QUE EL ARRAY DE ERRORES ESTE VACIO
    //ISSET REVISA QUE LA VARIABLE ESTE CREADA /  EMPTY REVISA QUE UN ARREGLO ESTE VACIO
    if (empty($errores)) {
        //SUBIDA DE ARCHIVOS (IMAGEN)

        //CREAR CARPETAS
        $carpetaImagenes = 'imagenes';
        if (!is_dir($carpetaImagenes)) { //is_dir RETORNA SI UNA CARPETA EXISTE
            mkdir($carpetaImagenes); //mkdir PARA CREAR UN DIRECTORIO
        }

        //GENERAR NOMBRE UNICO PARA LAS IMAGENES
        $nombreImagen = md5(uniqid(rand(), true)) . '.jpg'; //md5(uniqid(rand())) GENERAN NUMEROS ALEATORIOS PERO NO TIENE NADA QUE VER CON SEGURIDAD (EL ALG FUE HACKEADO)

        //SUBIR LA IMAGEN 
        move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . "/" . $nombreImagen);  //PARA MOVER LA IMAGEN EN MEMORIA, AL SERVIDOR, HACIA LA CARPETA IMAGENES


        //INSERTAR EN LA BASE DE DATOS
        $query = " INSERT INTO propiedad (titulo, descripcion, precio, imagen , coloniaCasa, calleCasa, numCasa, fechaPub, estadoPropiedad)
    VALUES ('$titulo', '$descripcion', '$precio','$nombreImagen', '$coloniaCasa', '$calleCasa', '$numCasa', '$creado', '$estadoPropiedad') ";

        //echo $query;  //PARA VER QUE EL QUERY ESTE ESCRITO CORRECTAMENTE

        $resultado = mysqli_query($db, $query);
        if ($resultado) {
            //REDIRECCIONAR AL USUARIO.
            header('Location: /adminP.php?resultado=1');
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

    <link rel="stylesheet" href="build/css/app.css">
    <title>Propiedades</title>
</head>

<body>
    <header class="titulo">
        <div class="titulo__texto">
            <h2>BienesRaices SoftBrothers</h2>
            <p>Crear Propiedad</p>
        </div>
    </header>


    <main>
    
        <div class="contenido">
            <div class="volver-boton">
                <a href="/AdminP.php"><button class="boton-adminP">Volver</button></a>
            </div>
            <!-- PARA MOSTRAR LA ALERTA DE LOS ERRORES -->
            <?php foreach ($errores as $error) : ?> <!--FOREACH PARA EJECUTAR AL MENOS 1 VEZ POR CADA VEZ QUE HAY UN ELEMENTO EN EL ARREGLO -->

                <div class="alerta error">
                    <?php echo $error; ?>
                </div>

            <?php endforeach; ?>


            <form action="propiedad.php" class="contenido__formulario" enctype="multipart/form-data" method="POST" id="formPropiedad"> <!--enctype para subir imagenes-->

                <div>
                    <label class="label" for="titulo">Titulo</label>
                    <input maxlength="20" class="tipotexto" type="text" name="titulo" id="titulo" placeholder="Ingrese Titulo Llamativo" value="<?php echo $titulo ?>">
                    <!-- EN VALUE SE AGREGA EL PHP PARA QUE NO SE BORRE LO QUE SE AGREGO AL MOMENTO DE FALTAR DATOS -->
                </div>

                <div>
                    <label class="label" for="descripcion">Descripcion</label>
                    <input class="tipotexto" type="text" name="descripcion" id="descripcion" placeholder="Ingrese Descripcion" value="<?php echo $descripcion ?>">
                </div>

                <div>
                    <label class="label" for="precio">Precio</label>
                    <input min="1" max="99999999" class="tipotexto" type="number" name="precio" id="precio" min="0" placeholder="Ingrese precio de la propiedad" value="<?php echo $precio ?>">
                </div>

                <div>
                    <label class="label" for="imagen">Imagen</label>
                    <input class="tipotexto" type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">
                </div>

                <div>
                    <label class="label" for="coloniaCasa">Casa Colonia</label>
                    <input class="tipotexto" type="text" name="coloniaCasa" id="coloniaCasa" min="0" placeholder="Ingrese la colonia de la casa" value="<?php echo $coloniaCasa ?>">
                </div>

                <div>
                    <label class="label" for="calleCasa">Calle</label>
                    <input class="tipotexto" type="text" name="calleCasa" id="calleCasa" min="0" placeholder="Ingrese la calle de la propiedad" value="<?php echo $calleCasa ?>">
                </div>

                <div>
                    <label class="label" for="numCasa">Numero Casa</label>
                    <input class="tipotexto" type="text" name="numCasa" id="numCasa" min="0" placeholder="Ingrese el numero de la casa" value="<?php echo $numCasa ?>">
                </div>

                <div class="boton">
                    <input class="boton__submit" type="submit" value="Enviar">
                </div>

            </form> <!--Acaba la etiqueta form-->
        </div> <!--Acaba div que contiene formulario-->
    </main>

    <footer class="pie">
        <p>Todos Los Derechos Reservados por SoftBrothers</p>
    </footer>

    <script defer src="/script.js"></script>
    <script defer src="/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
</body>

</html>