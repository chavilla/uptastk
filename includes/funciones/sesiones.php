<?php

/**Función que revisa que un usuario esté autenticado------------------------------------ */
function usuario_autenticado(){
    if (!revisar_usuario()) {
        header('location:login.php');
        exit();
    }
}

function revisar_usuario(){

    return isset($_SESSION['nombre']);
}

function destroySession() {

    $_SESSION = [];

    if(ini_get('session.use_cookies')) {

        $params = session_get_cookie_params();
        setcookie(session_name(),
                  '',
                  time() - 42000,
                  $params['path'],
                  $params['domain'],
                  $params['secure'],
                  $params['httponly']);
    }
    @session_destroy();
}

session_start();
usuario_autenticado();


//Código útil para cerrar las sesiones de usuario

/* if(isset($_GET['destroy']) && $_GET['destroy']=='true') {
    session_destroy();
    header('location:../../login.php');
} */

