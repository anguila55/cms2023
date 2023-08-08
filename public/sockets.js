const socket = io.connect('http://cms.onlife.com.ar:9187');


const user = document.querySelector(".user-input ");
const container =  $("#cont_messages");

//mostramos el mensaje de usuario logueado
socket.on('nuevo_usuario',(datos) => {
    console.log('nuevo usuario conectado'+ datos.user);
});

//Todos los clientes escuchan el emit del server
socket.on('nuevo_mensaje',(datos) => {
    console.log('datos que llegan desde el emit del server'+ datos.user);
 console.log('este es el mensaje' + datos.mensaje);
    let msgType = "";
	let flex = "";
	if (datos.user != user.value) {
		msgType = "other";
		flex = "end";
	} else {
		msgType = "you";
		flex = "start";
	}
	container.append(` <div class="col-12 d-flex justify-content-${flex}">
    <p class="${msgType}"><strong>${datos.user}:</strong> <br>${datos.mensaje}</p>
</div>`) ;
//Agregamos al destinatario el user que le envio el msj
chatprivado2(datos.user);

	//pateamos el scroll abajo para ver nuevos mensajes
	container.scrollTop = container.scrollHeight;
  $("#mensaje").val('');
  
  //  $("#destinatario").val(datos.user);
});


//Podemos enviar el mensaje presionando enter
$("#formData").submit(function(event) {
    //Evita que la pagina se recargue
    event.preventDefault();
   
   enviar_msj();
   
});


function enviar_msj(){
    // console.log('enviando...');
     mensaje = $("#mensaje").val();
     destinatario = $('#destinatario').val();
    //  console.log('este es el destinatario' + destinatario);
//Agregamos la validacion para que indentifique si esta enviando el mensaje a un destinatario 
     if (destinatario != '') {
         
         datamsj = {
            "mensaje":mensaje,
            "usuario":usuario,
            "destinatario": destinatario
        }
        console.log(datamsj);
        socket.emit('send_mensaje',datamsj);
     }else{
       alert('seleccione un usuario para comenzar a chatear');
     }

    
}
//Actualizamos los usuarios que ingresan en aca
socket.on('updateUsers',function(data){
    $('#users').html('');
    for(var i = 0; i < data.users.length; i++){
        html = '';
    
      html +='<a class="list-group-item bg-blue-grey bg-lighten-5  border-right-2"> ' 
      html +=  '<span class="media">'
      html +=   '<span class="avatar avatar-md avatar-online mr-2">'
      html +=     '<img class="media-object d-flex mr-3 bg-primary rounded-circle" src="../app-assets/img/portrait/small/avatar-s-1.jpg" alt="Generic placeholder image">'
      html +=   '</span>'
      html +=    '<div class="media-body">'
      html +=     '<h6  onclick="chatprivado(\''+data.users[i]+'\' )"  class="list-group-item-heading ">'+data.users[i]+'' 
      html +=      '<span class="float-right info">En linea</span>'
      html +=     '</h6>'
      html +=    '</div>'
      html +=   '</span>'
      html += '</a>';

        $('#users').append(html);
    }
           
});
//Obtenemos el nombre de la persona con la que deseamos chatear
function chatprivado(dest){
  console.log(dest);
     let destinatario = $("#destinatario").val(dest);

    

     $("#userdest").html(destinatario.val());


    container.empty();
    container.show();

}

function chatprivado2(destino){
  console.log(destino);
     let destinatario = $("#destinatario").val(destino);


    
      // $("#userdest2").html(destinatario.val());


}




function userdata(){
    console.log('hola');
    const datos = document.getElementsByClassName('user');
    console.log(datos);
    data = $(".user").val();
    console.log(data);
}