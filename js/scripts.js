
//Llamamos la función para que los eventos siempre esté a la escucha
eventListener();


//Creamos un evento para manejar el progreso de lo que se lleva manejado
document.addEventListener('DOMContentLoaded', function(){
    actualizarProgreso();
});

// Variable que contiene el ul de la lista de proyectos
let lista_proyectos=document.querySelector("#proyectos");

function eventListener() {
    document.querySelector('.crear-proyecto').addEventListener('click', creaProyecto);

    //Boton para agregar una tarea que llama la funcion agregarTarea
    document.querySelector('.nueva-tarea').addEventListener('click',agregarTarea);

    //Botones para acciones de las tareas
    document.querySelector('.listado-pendientes').addEventListener('click', accionesTareas);
}

function creaProyecto(e) {
    e.preventDefault();
    let proyecto=document.createElement('li');
    proyecto.innerHTML="<input type='text' id='nuevo-proyecto'>";
    lista_proyectos.appendChild(proyecto);

    // Seleccionar el input con el id nuevo-proyecto para darle un valor que se inserte en la BD
    let inputNuevoProyecto=document.querySelector('#nuevo-proyecto');
    
    //Le asignamos un evenrto al presionar tecla enter (EVENTOS DEL TECLADO).
    inputNuevoProyecto.addEventListener('keypress',function(e){
        let tecla=e.which || e.keyCode;
        if (tecla===13){
            insertarProyecto(inputNuevoProyecto.value);
            lista_proyectos.removeChild(proyecto);
            
        }
    });
    
}

//Función que nos inserta el proyecto nuevo en el DOM del documento HTML

function insertarProyecto(nombreProyecto){

    //Inyectar el html
    let nuevoProyecto=document.createElement('li');

    /*let enlaceLista=document.createElement('a');
    enlaceLista.setAttribute('href','#');
    enlaceLista.innerHTML=nuevoProyecto;
    lista.appendChild(enlaceLista); */

    /*Una manera de insertar en el dom un poco mas avanzado es con el template string */
    nuevoProyecto.innerHTML=`
        <a href='#'>${nombreProyecto}</a>
    `;

    lista_proyectos.appendChild(nuevoProyecto);
    guardarProyecto(nombreProyecto);
}

//Funcion Ajax para enviar los datos a la Database.

function guardarProyecto(nombreProyecto){

    /* Creamos una variable para enviar los datos por medio de formData */
    let datos=new FormData();
    datos.append('proyecto',nombreProyecto);
    datos.append('accion','crear');

    /* Crear Objeto de tipo Ajax */
    var xhr=new XMLHttpRequest();

    /* Abrir la conexión del objeto Ajax */
    xhr.open("POST",'includes/modelos/modelo-proyecto',true);

    /*Carga del objeto */
    xhr.onload=function(){
        if (this.status===200){
            
            var respuesta=JSON.parse(xhr.responseText);

            var proyecto=respuesta.nombreProyecto,
                id_proyecto=respuesta.id,
                tipo=respuesta.accion,
                resultado=respuesta.respuesta;

            /* Comprobar la inserción */
            if (resultado==='correcto'){

                /*Verificar si la acción que se hace es CREAR  */
                if (tipo==='crear') {
                    
                    let nuevoProyecto=document.createElement('li');
                    /*Una manera de insertar en el dom un poco mas avanzado es con el template string */
                    nuevoProyecto.innerHTML=`
                    <a href="index.php?id_proyecto=${id_proyecto}" id="proyecto:${id_proyecto}">
                    ${proyecto}
                    </a>
                    `; 

                    /*Agregar el proyecto al html */
                    lista_proyectos.appendChild(nuevoProyecto);

                    /*Alerta con swal------------- */
                    swal('','Proyecto '+proyecto+' Creado exitosamente','success').then(
                        window.location.href='index.php?id_proyecto='+id_proyecto+''
                    );
                }
                // En caso que la aación sea diferente a crear
                else{

                }
                
            }
            /* En caso que no se ejecute la inserción */

            else {
                swal('Error','Registro no insertado','error');
            }
            
        }
        
    }

    /* Envío de datos */

    xhr.send(datos);
    
}

//Función para añadir tareas
function agregarTarea(e){
    e.preventDefault();

    let nombreTarea=document.querySelector('.nombre-tarea').value.trim(),
        id_proyecto=document.querySelector('#id_proyecto').value;
    if (nombreTarea!==""){

        var datos=new FormData();
        datos.append('tarea',nombreTarea);
        datos.append('accion','crear');
        datos.append('id_proyecto',id_proyecto);
        
        var xhr=new XMLHttpRequest();

        /* Abrir la conexión del objeto Ajax */
        xhr.open("POST",'includes/modelos/modelo-tarea',true);
    
        /*Carga del objeto */
        xhr.onload=function(){
            if (this.status===200){
                var respuesta=JSON.parse(xhr.responseText);
                console.log(respuesta);
                
                // Asignar valores
                var tarea=respuesta.tarea,
                    resultado=respuesta.respuesta,
                    id_insertado=respuesta.id,
                    tipo=respuesta.tipo;

                // Insercion correcta de la tarea
                if (resultado==='correcto'){
                    swal('','Tarea '+tarea+' Creada exitosamente','success');

                     //Eliminar el parrafo que contiene el texto en caso de no haber tareas
                     let listaVacia=document.querySelectorAll('.lista-vacia');
                     if (listaVacia.length>0) {
                        document.querySelector('.lista-vacia').remove();   
                     }
                     

                    //Construit el template
                    let nuevaTarea=document.createElement('li');

                    //Añadir un id
                    nuevaTarea.id='tarea'+id_insertado;

                    //Añadir clase para estilos css
                    nuevaTarea.classList.add('tarea');

                    //Construir html
                    nuevaTarea.innerHTML=`
                        <p>${tarea}</p>
                        <div class="acciones">
                            <i class="far fa-check-circle"></i>
                            <i class="fas fa-trash"></i>
                        </div>
                    `;

                    //Añadir al DOM
                    let listado=document.querySelector('.listado-pendientes ul');
                    listado.appendChild(nuevaTarea);

                    //Actualiza el progreso en la barra de porcentaje
                    actualizarProgreso();
                   

                }

                // hubo un error de inserción
                else {
                    
                }


            }
            
        }
    
        /* Envío de datos */
    
        xhr.send(datos);
    }
    else {
        swal('','No has ingresado ninguna tarea','error');
    }
}


// Función para acciones de lista de tareas. Sea editar o eliminar
function accionesTareas(e) {
    e.preventDefault();
    
    if(e.target.classList.contains("btn-completar")) {
        if (e.target.classList.contains('completo')) {
            e.target.classList.remove('completo');
            
            cambiarEstadoTarea(e.target,0);
        }
        else {
            e.target.classList.add('completo');
            cambiarEstadoTarea(e.target,1);
        }
    }

    if(e.target.classList.contains("btn-eliminar")){
        //alert('Hiciste click en eliminar');

        swal({
            title: "Seguro(a)?",
            text: "Esta acción no se puede deshacer!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor:'#3085d6',
            cancelButtonColor:'#d33',
            cancelButtonText:'cancelar',
            confirmButtonText: 'si, borrar!'
          }).then((result) => {
            if (result.value){

            
              let tareaEliminar=e.target.parentElement.parentElement;
            
                  // Borrar de la base de datos
                  eliminarTareaBd(tareaEliminar);

                  // Borrar del html
                  tareaEliminar.remove();


            swal("Esta tarea ha sido eliminada!", {
                type: "success",
              });

            } 
            else {
              swal("Tu tarea no se eliminó!");
            }
          });

        
    }
    
        
    
    
}

//Funcion para completar o descompletar tarea
function cambiarEstadoTarea(tarea,estado) {
  //Funcion split que sirve para separar el valor de un elemento. Sin split sería tarea:1 con split(':') sería 1
    let idTarea=tarea.parentElement.parentElement.id.split(':');

    /* Formadata para enviar datos */
    let datos=new FormData();

    /* Agregar datos al formdata */
    datos.append('id_tarea',idTarea[1]);
    datos.append('accion','actualizar');
    datos.append('estado',estado);

    
    /*Creación del objeto Ajax */
    let xhr=new XMLHttpRequest();

    /* Apertura de la conexión */
    xhr.open('POST','includes/modelos/modelo-tarea.php',true);
    
    /* Onload */
    xhr.onload=function(){
        if (this.status===200) {
            console.log(xhr.responseText);
            //Actualiza el progreso en la barra de porcentaje
            actualizarProgreso();
            
        }
        
    }

    xhr.send(datos);
        

}


//Funcion para eliminar tarea de la base de datos
function eliminarTareaBd(tarea) {
    let idTarea=tarea.id.split(':');

    /* Formadata para enviar datos */
    let datos=new FormData();

    /* Agregar datos al formdata */
    
    datos.append('id_tarea',idTarea[1]);
    datos.append('accion','eliminar');

    
    /*Creación del objeto Ajax */
    let xhr=new XMLHttpRequest();

    /* Apertura de la conexión */
    xhr.open('POST','includes/modelos/modelo-tarea.php',true);
    
    /* Onload */
    xhr.onload=function(){
        if (this.status===200) {
            
            // Comprueba que haya tarea pendientes
            var tareasRestantes=document.querySelectorAll('li.tarea');
            if (tareasRestantes.length==0){
                document.querySelector('.listado-pendientes ul').innerHTML="<p class='lista-vacia'>No hay tareas</p>";
            }

            //Actualiza el progreso en la barra de porcentaje
            actualizarProgreso();
            
        }
        
    }

    xhr.send(datos);
    
}


// Funcion que actualiza el progreso del avance

function actualizarProgreso(){
    //Obtener todas las tareas
    let tareas=document.querySelectorAll('li.tarea');
    console.log(tareas.length);
    
    //Obtener tareas completadas
    let tareas_completas=document.querySelectorAll('i.completo');
    console.log(tareas_completas.length);

    //Obtener el porcentaje
    let resultado=Math.round((tareas_completas.length*100)/tareas.length);
    console.log(resultado);

    //Obtener la barra de porcentaje
    let porcentaje=document.querySelector("div#porcentaje");
    porcentaje.style.width=resultado+'%';

    // Enviar un alert en caso que se haya complerado el progreso

    if (porcentaje.style.width==='100%'){
        alert('completado');
    }
    
    
    
    
}