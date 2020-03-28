<?php

$accion=$_POST['accion'];
$proyecto=$_POST['proyecto'];

if($accion==='crear'){

    include '../funciones/conexion.php';

    try {
        //Conectando a la base de datos con el statement
        $stmt=$conexion->prepare('INSERT INTO proyectos(nombre) VALUES(?)');
        //Parametrizar los statements
        $stmt->bind_param('s',$proyecto);
        // Ejecución de la consulta
        $stmt->execute();

        //Validar que una columna se vea modificada
        if ($stmt->affected_rows>0){
                 $respuesta=array(
                'respuesta'=>'correcto',
                'id'=>$stmt->insert_id,
                'nombreProyecto'=>$proyecto,
                'accion'=>$accion
                 );
        }
        else{
            //HUbo un error
            $respuesta=array(
                'respuesta'=>'Hubo un error'
            );
        }

        //Cierre del statement y la conexión
        $stmt->close();
        $conexion->close();

    } catch (Exception $e){
        //Excepcion en caso de haber un error

        $respuesta=array(
            'error'->$e->getMessage()
        );
    }
    echo json_encode($respuesta,JSON_UNESCAPED_UNICODE);
}