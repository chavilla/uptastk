<?php

$usuario=$_POST['usuario'];
$password=$_POST['password'];
$accion=$_POST['accion'];


/** Código donde entrara la pagna en caso que le demos registrar un usuario--------- */

if($accion==='crear'){

    /*Primero se hace el hash del password--------- */
    $opciones=array(
        'coste'=>12
    );

    /* Se cifra con la función password_hash. Esta recibe tres parámetros para su funcionamiento
    1) El string de la contraseña, 2) el modo de encriptación y 3) el coste que usualmente es un entero */
    $cifrado=password_hash($password,PASSWORD_BCRYPT,$opciones);

    /* $resultado=array(
        'usuario'=>$usuario,
        'password'=>$cifrado
    ); */
    include '../funciones/conexion.php';

    try {
        //Conectando a la base de datos con el statement
        $stmt=$conexion->prepare('INSERT INTO usuarios(usuario, clave) VALUES(?,?)');
        //Parametrizar los statements
        $stmt->bind_param('ss',$usuario,$cifrado);
        // Ejecución de la consulta
        $stmt->execute();

        //Validar que una columna se vea modificada
        if ($stmt->affected_rows>0){
                 $respuesta=array(
                'respuesta'=>'correcto',
                'id'=>$stmt->insert_id,
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
            'pass'->$e->getMessage()
        );
    }
    echo json_encode($respuesta,JSON_UNESCAPED_UNICODE);
}

if($accion==='login'){
    try 
    {
        include '../funciones/conexion.php';

        $stmt=$conexion->prepare('SELECT usuario, id, clave FROM usuarios WHERE usuario=?');
        $stmt->bind_param('s',$usuario);
        $stmt->execute();

        /*Con esta función y las siguientes lineas podemos loguear al usuario 
        Tenemos que colocar los parámetros en el orden de datos de la consulta--------------------------------*/
        $stmt->bind_result($nombre_usuario, $id_usuario, $pass_usuario);
        $stmt->fetch();

        if($nombre_usuario)
        {
            //Si el usuario existe verificar el password
            if (password_verify($password,$pass_usuario)){

                //Iniciar la sesión de usuario en caso que este logueado y la contraseña estén bien;
                session_start();
                
                // Variable super globsl que pasará al archivo de sesiones
                $_SESSION['nombre']=$usuario;

                $respuesta=array(
                    'respuesta'=>'correcto',
                    'usuario'=>$nombre_usuario,
                    'id'=>$id_usuario,
                    'password'=>$pass_usuario,
                    'tipo'=>$accion
                );
            }
            else{
                $respuesta=array(
                    'respuesta'=>'Contraseña incorrecta'
                );
            }
           
        }
        else
        {
            $respuesta=array(
                'error'=>'Usuario no válido'
            );
        }

        $stmt->close();
        $conexion->close();

    } // Cierre del try
    catch (Exception $e)
    {
        //Excepcion en caso de haber un error

        $respuesta=array(
            'pass'->$e->getMessage()
        );
    } // Cierre del catch
    echo json_encode($respuesta,JSON_UNESCAPED_UNICODE);

}


?>