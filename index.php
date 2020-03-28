<?php

include "includes/funciones/conexion.php";
include "includes/funciones/sesiones.php";
include "includes/funciones/funciones.php";
include "includes/templates/header.php";

//Obtener el id de la url
    
    if(isset($_GET['id_respuesta'])) {
        # code...
        $id=$_GET['id_respuesta'];
        $proyectoActual=getNombre($id);
        $tareas=getTareasProyecto($id);
    }
 ?>

<div class="barra">
    <h3>Bienvenido <?php echo $_SESSION['nombre']; ?></h3>
    <h1>UpTask - Administración de Proyectos</h1>
    <a href="login.php?destroy=true">Cerrar Sesión</a>
</div>

<div class="contenedor">
    <?php
        include "includes/templates/sidebar.php";
    ?> 
    <main class="contenido-principal">
        <?php
         
        if (isset($proyectoActual)){
        ?>
            <h1>
            Proyecto Actual
                <span>
                    <?php
                    
                        foreach ($proyectoActual as $proyecto) {
                            echo $proyecto['nombre'];
                        }
                    ?>
                    
                </span>
            </h1>

            <form action="#" class="agregar-tarea">
                <div class="campo">
                    <label for="tarea">Tarea:</label>
                    <input type="text" placeholder="Nombre Tarea" class="nombre-tarea"> 
                </div>
                <div class="campo enviar">
                    <input type="hidden" id="id_proyecto" value="<?php echo $id; ?>">
                    <input type="submit" class="boton nueva-tarea" value="Agregar">
                </div>
            </form>

        <?php
        }

        else {
            echo "<p>Selecciona un proyecto a la izquierda</p>";
        }
        ?>
        
 

        <h2>Listado de tareas:</h2>

        <div class="listado-pendientes">
            <ul>
            <?php
                if(isset($tareas) && $tareas->num_rows>0){
                    foreach ($tareas as $tarea) {
                        ?>

                <li id="tarea:<?php echo $tarea['id'];?>" class="tarea">
                <p><?php echo $tarea['nombre'];?></p>
                    <div class="acciones">
                        <i class="far fa-check-circle btn-completar <?php echo ($tarea['estado']==='1' ? 'completo':'') ?>"></i>
                        <i class="fas fa-trash btn-eliminar"></i>
                    </div>
                </li>  
                    <?php
                    }
                }
                else {
                    echo "<p class='lista-vacia'>No hay tareas</p>";
                }
            ?>
            </ul>
        </div>

        <!-- Barra del avance-->
        <div class="avance">
            <h2>Avance del curso</h2>
            <div id='barra-avance' class='barra-avance'>
                <div id="porcentaje" class="porcentaje">
                    
                </div>
            </div>
        </div>

    </main>
</div><!--.contenedor-->


<?php
    include "includes/templates/footer.php";
?>