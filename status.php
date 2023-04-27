<?php
require './includes/config/database.php';
$db = conectarDB();

$id=$_GET['id'];
$estadoPropiedad=$_GET['estadoPropiedad'];

$query="UPDATE propiedad SET estadoPropiedad=$estadoPropiedad WHERE id=$id";

mysqli_query($db, $query);
header('Location: /adminP.php');

?>