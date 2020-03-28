<?php

$accion=$_POST['accion'];

if($accion==='crear'){

    $id_proyecto=(int)$_POST['id_proyecto'];
    $tarea=$_POST['tarea'];
    include '../funciones/conexion.php';

    try {
        //Conectando a la base de datos con el statement
        $stmt=$conexion->prepare('INSERT INTO tareas(nombre,id_proyecto) VALUES(?,?)');
        //Parametrizar los statements
        $stmt->bind_param('si',$tarea,$id_proyecto);
        // Ejecución de la consulta
        $stmt->execute();

        //Validar que una columna se vea modificada
        if ($stmt->affected_rows>0){
                 $respuesta=array(
                'respuesta'=>'correcto',
                'id'=>$stmt->insert_id,
                'tarea'=>$tarea,
                'id_proyecto'=>$id_proyecto,
                'tipo'=>$accion
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

if ($accion==='actualizar'){

    $id_tarea=(int)$_POST['id_tarea'];
    $estado=$_POST['estado'];
    include '../funciones/conexion.php';

    try {
        //Conectando a la base de datos con el statement
        $stmt=$conexion->prepare('UPDATE tareas SET estado=? where id=?');
        //Parametrizar los statements
        $stmt->bind_param('ii',$estado,$id_tarea);
        // Ejecución de la consulta
        $stmt->execute();

        //Validar que una columna se vea modificada
        if ($stmt->affected_rows>0){
                 $respuesta=array(
                'respuesta'=>'correcto'
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


if ($accion==='eliminar'){

    $id_tarea=(int)$_POST['id_tarea'];
    
    include '../funciones/conexion.php';

    try {
        //Conectando a la base de datos con el statement
        $stmt=$conexion->prepare('DELETE FROM tareas where id=?');
        //Parametrizar los statements
        $stmt->bind_param('i', $id_tarea);
        // Ejecución de la consulta
        $stmt->execute();

        //Validar que una columna se vea modificada
        if ($stmt->affected_rows>0){
                 $respuesta=array(
                'respuesta'=>'correcto'
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