const path = require("path");
const express = require("express");
const app = express();
//llamamos al socket io
const SocketIo = require("socket.io");
//llamamos carpeta public

app.set("port", process.env.PORT || 3000);
app.use(express.static(path.join(__dirname, "public")));

//iniciamos el server en el puerto 3000
const server = app.listen(app.get("port"), () => {
	//console.log("Incio el server");
});

const io = SocketIo.listen(server);

//websockets
io.on("connection", (socket) => {
	//console.log("Nueva Conexion", socket.id);

	io.sockets.emit("user:login");
	socket.on("chat:msg", (data) => {
		io.sockets.emit("chat:msg", data);
	});

	socket.on("user:typing", (user) => {
		socket.broadcast.emit("user:typing", user);
	});
});
