<?php


/* Obtener el nombre del archivo y dependiendo de este aplicar estilos y scripts */

function obtenerArchivo(){
    $archivo=basename($_SERVER['PHP_SELF']);
    $pagina=str_replace(".php","",$archivo);

    return $pagina;
}

function getProyectos(){
    include "conexion.php";

    try {
        //code...
        return $conexion->query('select * from proyectos order by id');
    } catch (Exception $e) {
        //throw $th;
        echo "Error ". $e->getMessage();
        return false;
    }
}

function getNombre($id=null){

    include "conexion.php";

    try {
        //code...
        return $conexion->query("select nombre from proyectos where id={$id}");
    } catch (Exception $e) {
        //throw $th;
        echo "Error ". $e->getMessage();
        return false;
    }

}


function getTareasProyecto($id=null){
    include "conexion.php";

    try {
        //code...
        return $conexion->query("SELECT id, nombre, estado from tareas where id_proyecto={$id}");
    } catch (Exception $e) {
        //throw $th;
        echo "Error ". $e->getMessage();
        return false;
    }
}