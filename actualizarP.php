<?php
//ACTUALIZAR
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: /adminP.php');
}

//-------------------------

//BASE DE DATOS CONECTARLA
require './includes/config/database.php';
$db = conectarDB();

//OBTENER LA CONSULTA DE LA PROPIEDAD
$consulta = "SELECT * FROM propiedad WHERE id = ${id}";
$resultado = mysqli_query($db, $consulta);
$propiedad = mysqli_fetch_assoc($resultado);



//ARREGLO CON MENSAJES DE ERRORES
$errores = [];

//VARIABLES SE CREAN VACIAS-> POSTERIORMENTE EN ACTUALIZAR NOS LA TRAEMOS CON EL FETCH
$titulo = $propiedad['titulo'];
$descripcion = $propiedad['descripcion'];
$precio = $propiedad['precio'];
$coloniaCasa = $propiedad['coloniaCasa'];
$calleCasa = $propiedad['calleCasa'];
$numCasa = $propiedad['numCasa'];
$imagenPropiedad = $propiedad['imagen'];

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
    $estadoPropiedad = 'Publicada'; //AGREGA POR DEFAULT EN LA COLUMA ESTADO 'PUBLICADA'
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

    //VALIDAR POR SIZE DE LA IMAGEN (1 mb maximo)
    $medida = 1000 * 1000; //LOS DATOS LOS MIDE EN BYTES, 1000000 bytes = 1mb
    if ($imagen['size'] > $medida) {
        $errores[] = 'La imagen es muy pesada';
    }



    //REVISAR QUE EL ARRAY DE ERRORES ESTE VACIO
    //ISSET REVISA QUE LA VARIABLE ESTE CREADA /  EMPTY REVISA QUE UN ARREGLO ESTE VACIO
    if (empty($errores)) {
        //ACTUALIZAR (IMAGEN)

        //CREAR CARPETAS
        $carpetaImagenes = 'imagenes/';
        if (!is_dir($carpetaImagenes)) { //is_dir RETORNA SI UNA CARPETA EXISTE
            mkdir($carpetaImagenes); //mkdir PARA CREAR UN DIRECTORIO
        }

        $nombreImagen = '';

        if ($imagen['name']) {
            //SI HAY IMAGEN ENTONCES ELIMINAR LA PREVIA PARA NO ACUMULAR EN EL SERVIDOR
            unlink($carpetaImagenes . $propiedad['imagen']); //propiedad['imagen'] viene del fetch
            //unlink es para eliminar archivos
            //-----------------------------------------------------------------------
            //GENERAR NOMBRE UNICO PARA LAS IMAGENES
            $nombreImagen = md5(uniqid(rand(), true)) . '.jpg'; //md5(uniqid(rand())) GENERAN NUMEROS ALEATORIOS PERO NO TIENE NADA QUE VER CON SEGURIDAD (EL ALG FUE HACKEADO)
            //SUBIR LA IMAGEN 
            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes  . $nombreImagen);  //PARA MOVER LA IMAGEN EN MEMORIA, AL SERVIDOR, HACIA LA CARPETA IMAGENES
        } else {
            $nombreImagen = $propiedad['imagen']; //REMEMBER: propiedad viene desde la BD
        }

        //----ACTUALIZAR
        $query = "UPDATE propiedad SET 
        titulo = '${titulo}', 
        descripcion = '${descripcion}', 
        precio = '${precio}', 
        imagen = '${nombreImagen}', 
        coloniaCasa = '${coloniaCasa}', 
        calleCasa = '${calleCasa}', 
        numCasa = '${numCasa}' WHERE id = $id ";

        // echo $query;  //PARA VER QUE EL QUERY ESTE ESCRITO CORRECTAMENTE.
        // exit;

        $resultado = mysqli_query($db, $query);
        if ($resultado) {
            //REDIRECCIONAR AL USUARIO.
            header('Location: /adminP.php?resultado=2');
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
            <p>Actualizar Propiedad</p>
        </div>
    </header>


    <main>
        <div class="contenido">

            <!-- PARA MOSTRAR LA ALERTA DE LOS ERRORES -->
            <?php foreach ($errores as $error) : ?> <!--FOREACH PARA EJECUTAR AL MENOS 1 VEZ POR CADA VEZ QUE HAY UN ELEMENTO EN EL ARREGLO -->

                <div class="alerta error">
                    <?php echo $error; ?>
                </div>

            <?php endforeach; ?>


            <form class="contenido__formulario" enctype="multipart/form-data" method="POST" id="formPropiedad"> <!--enctype para subir imagenes-->
                <!-- SI NO LE PONES ACTION LO ENVIA AL MISMO ARCHIVO -->
                <div>
                    <label class="label" for="titulo">Titulo</label>
                    <input class="tipotexto" type="text" name="titulo" id="titulo" placeholder="Ingrese Titulo Llamativo" value="<?php echo $titulo ?>">
                    <!-- EN VALUE SE AGREGA EL PHP PARA QUE NO SE BORRE LO QUE SE AGREGO AL MOMENTO DE FALTAR DATOS -->
                </div>

                <div>
                    <label class="label" for="descripcion">Descripcion</label>
                    <input class="tipotexto" type="text" name="descripcion" id="descripcion" placeholder="Ingrese Descripcion" value="<?php echo $descripcion ?>">
                </div>

                <div>
                    <label class="label" for="precio">Precio</label>
                    <input class="tipotexto" type="number" name="precio" id="precio" min="0" placeholder="Ingrese precio de la propiedad" value="<?php echo $precio ?>">
                </div>

                <div>
                    <label class="label" for="imagen">Imagen</label>
                    <input class="tipotexto" type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">
                </div>

                <div>
                    <img src="/imagenes/<?php echo $imagenPropiedad; ?>" class="imagen-small">
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
                    <input class="boton__submit" type="submit" value="Actualizar">
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