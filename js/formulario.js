

// Llamamos para que los listener siempre esten a la espera
eventListener();


//Creamos la función eventLister
function eventListener(){
    document.querySelector('#formulario').addEventListener('submit', validarRegistro);

}

function validarRegistro(e) {
    e.preventDefault();

    var usuario=document.querySelector('#usuario').value.trim(),
        password=document.querySelector('#password').value.trim(),
        tipo=document.querySelector('#tipo').value;
        // Asegurarse que los campos no estén vacíos
        if (usuario==='' || password==='') {
            swal( "Error" ,  " Todos los campos son obligatorios " ,  "error" );
        }
        else{

            /* En caso que los datos sean correctos llamamos a AJAX. Pero primero debemos enviar mediante formdata */
            const datos=new FormData();
            datos.append('usuario', usuario);
            datos.append('password', password);
            datos.append('accion', tipo);

            /* Creacion del objeto Ajax */
            const xhr=new XMLHttpRequest();

            /* Apertura de la conexion del objeto Ajax */
            xhr.open('POST','includes/modelos/modelo-admin.php',true);

            /* Envío de datos a través de Ajax------------- */
            xhr.onload=function(){
                if(this.status===200){
                    var respuesta=JSON.parse(xhr.responseText);
                    console.log(respuesta);
                    
                    if (respuesta.respuesta==='correcto'){
                        if (respuesta.tipo==='crear'){
                            swal('','Contacto creado con éxito','success');
                            document.querySelector("#formulario").reset();
                        }
                        else if (respuesta.tipo==='login'){
                            /*En esta parte tenemos un código js que nos redirecciona en caso de haber una
                            contraseña y un usuario logueados con la función then */
                                swal('','Bienvenido. Presiona ok para continuar','success')
                                .then(resultado=>{
                                    if (resultado.value){
                                        window.location.href="index.php";
                                    }
                                });
                            
                        }
                        
                    }
                    else{
                        swal('Error','Usuario o contraseña no válido','error');
                    }
                }
            }

            /* Envío de datoscon el formdata */
            xhr.send(datos);
            

        }
    
}