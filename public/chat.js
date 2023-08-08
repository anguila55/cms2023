// const socket = io();
var socket = io.connect('http://cms.onlife.com.ar:9187');
//Mensaje de bienvenida.
// socket.on('data', function(data){
//    $(".msg-input").append('<p> ' + data.text + ' </p>');
// });
const send = document.querySelector(".send");
const user = document.querySelector(".user-input ");
const message = document.querySelector(".msg-input ");
const msgcontainer = document.querySelector(".msg-container");
const typing = document.querySelector(".typing");
const toast = document.querySelector(".toast-container");
const cont = document.getElementById('.contentbutton');
//Envio mensaje

document.addEventListener('DOMContentLoaded', () => {
	cont.forEach( node => node.addEventListener('keypress', e => {
	  if(e.keyCode == 13) {
		e.preventDefault();
		send.click(function(){
			console.log('llmaste');
		});
	  }
	}))
  });

send.addEventListener("keyup", function(event) {
    event.preventDefault();
    if (event.keyCode === 13) {
		console.log('enter');
        // send.click();
    }
});
// send.addEventListener("click", () => {
// 	console.log('data');
// 	let data = { msg: message.value, user: user.value };
// 	console.log(data);
// 	socket.emit("chat:msg", data);
// 	message.value = "";
// });

//Recibo mensajes
socket.on("chat:msg", (data) => {
	let msgType = "";
	let flex = "";
	if (data.user != user.value) {
		msgType = "other";
		flex = "end";
	} else {
		msgType = "you";
		flex = "start";
	}
	msgcontainer.innerHTML += ` <div class="col-12 d-flex justify-content-${flex}">
    <p class="${msgType}"><strong>${data.user}:</strong> <br>${data.msg}</p>
</div>`;

	//pateamos el scroll abajo para ver nuevos mensajes
	msgcontainer.scrollTop = msgcontainer.scrollHeight;
	message.value = "";
});

//emitimos evento cuando  el usuario esta tipeando
message.addEventListener("keypress", () => {
	socket.emit("user:typing", user.value);
});

//recibimos evento si esta tipiando
socket.on("user:typing", (user) => {
	typing.innerHTML = `<em>${user} esta escribiendo..</em>`;

	setTimeout(() => {
		typing.innerHTML = "";
	}, 2000);
});

socket.on("user:login", () => {
	toast.classList.remove("false");
	toast.classList.add("true");

	setTimeout(() => {
		toast.classList.add("hide");
	}, 2000);
});
