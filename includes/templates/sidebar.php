<aside class="contenedor-proyectos">
        <div class="panel crear-proyecto">
            <a href="#" class="boton">Nuevo Proyecto <i class="fas fa-plus"></i> </a>
        </div>
    
        <div class="panel lista-proyectos">
            <h2>Proyectos</h2>
            <ul id="proyectos">
                <?php

                $proyectos=getProyectos();
                if ($proyectos) {
                    # code...
               
                foreach ($proyectos as $proyecto){
                    # code...
                ?>
                 <li><a href="index.php?id_respuesta=<?php echo $proyecto['id']; ?>" id="proyecto:<?php echo $proyecto['id']; ?>">
                <?php
                echo $proyecto['nombre'];
                    }
                }
                ?>
                </a></li>
            </ul>
        </div>
    </aside>
