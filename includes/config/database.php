<?php

function conectarDB() : mysqli{
    $db = mysqli_connect("localhost", "root","root","bd_casas");
    if(!$db){
       echo "Error no se pudo conectar";
       exit; 
    } 

    return $db;

}