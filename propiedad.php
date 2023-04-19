<?php
//BASE DE DATOS CONECTARLA
require './includes/config/database.php';
$db = conectarDB();

//ARREGLO CON MENSAJES DE ERRORES
$errores = [];

//EJECUTA EL CODIGO DEDSPUES DE QUE EL USUARIO ENVIA EL FORMULARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {  //VALIDA QUE SEA POST

    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";

    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $coloniaCasa = $_POST['coloniaCasa'];
    $calleCasa = $_POST['calleCasa'];
    $numCasa = $_POST['numCasa'];

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

    //REVISAR QUE EL ARRAY DE ERRORES ESTE VACIO
    //ISSET REVISA QUE LA VARIABLE ESTE CREADA /  EMPTY REVISA QUE UN ARREGLO ESTE VACIO
    if (empty($errores)) {

        //INSERTAR EN LA BASE DE DATOS
        $query = " INSERT INTO propiedad (titulo, descripcion, precio, coloniaCasa, calleCasa, numCasa)
    VALUES ( '$titulo', '$descripcion', '$precio', '$coloniaCasa', '$calleCasa', '$numCasa') ";

        //echo $query;  //PARA VER QUE EL QUERY ESTE ESCRITO CORRECTAMENTE

        $resultado = mysqli_query($db, $query);
        if ($resultado) {
            echo "Insertado Correctamente";
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



            <!-- PARA MOSTRAR LA ALERTA DE LOS ERRORES -->
        <?php foreach ($errores as $error) : ?> <!--FOREACH PARA EJECUTAR AL MENOS 1 VEZ POR CADA VEZ QUE HAY UN ELEMENTO EN EL ARREGLO -->
           
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
            
        <?php endforeach; ?>



        <form action="propiedad.php" class="contenido__formulario" enctype="multipart/form-data" method="POST"> <!--enctype para subir imagenes-->

            <div>
                <label class="label" for="titulo">Titulo</label>
                <input class="tipotexto" type="text" name="titulo" id="titulo" placeholder="Ingrese Titulo Llamativo">
            </div>

            <div>
                <label class="label" for="descripcion">Descripcion</label>
                <input class="tipotexto" type="text" name="descripcion" id="descripcion" placeholder="Ingrese Descripcion">
            </div>

            <div>
                <label class="label" for="precio">Precio</label>
                <input class="tipotexto" type="number" name="precio" id="precio" min="0" placeholder="Ingrese precio de la propiedad">
            </div>

            <div>
                <label class="label" for="imagen">Imagen</label>
                <input class="tipotexto" type="file" name="imagen" id="imagen">
            </div>

            <div>
                <label class="label" for="coloniaCasa">Casa Colonia</label>
                <input class="tipotexto" type="text" name="coloniaCasa" id="coloniaCasa" min="0" placeholder="Ingrese la colonia de la casa">
            </div>

            <div>
                <label class="label" for="calleCasa">Calle</label>
                <input class="tipotexto" type="text" name="calleCasa" id="calleCasa" min="0" placeholder="Ingrese la calle de la propiedad">
            </div>

            <div>
                <label class="label" for="numCasa">Numero Casa</label>
                <input class="tipotexto" type="text" name="numCasa" id="numCasa" min="0" placeholder="Ingrese el numero de la casa">
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

</body>

</html>