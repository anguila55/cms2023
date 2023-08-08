//declaramos las variables globales
var notifData = {"notificaciones":[]};
var cantnotif = 0;
notificationActive();
$("#notinum").hide();

function notificationActive(){
	var percodnotif = $('#percodnotif').val();	
	//var socket = io.connect('demo.btoolbox.com:9284');
	var socket = io.connect('demo.btoolbox.com:9284');
	
	socket.emit('notifregister', 'REGISTRACIONUSUARIONOTIF:##:'+percodnotif);
	socket.off('notifstart');
	socket.on('notifstart', function(data) {
		cantnotif = 0;
	});
	
	socket.off('notifrecibe');
	socket.on('notifrecibe', function(data) {
		//var indexPage = false;
		//if(document.URL.indexOf('/index')!==-1){
		//	urlnotif = urlnotif.replace('../','');
		//	indexPage = true;
		//}
		var icon 	= ' ft-message-circle ';
		var color 	= ' danger ';
		var vdata 	= data.split(':##:');

		var notreg 		= vdata[0];
		var pernombre 	= vdata[1];
		var perapelli 	= vdata[2];
		var percompan 	= vdata[3];
		var nottitulo 	= vdata[4];
		var notcodigo 	= vdata[5];
		var percoddst   = vdata[6];
		var percargo 	= vdata[7];
		var de 	= vdata[8];

		var splittitulo = nottitulo.split("##");
		var urllink=''
		nottitulo=splittitulo[0];
		if(splittitulo.length>1){
			nottitulo=splittitulo[0];
			urllink= splittitulo[1];
		}else{
			nottitulo=splittitulo[0];
		}
		
		
		var notexists = false;
		for(var i=0;i<notifData.notificaciones.length;i++){
			if(notifData.notificaciones[i].notreg==notreg){
				notexists=true;
			}
		}
		if(notexists==false){
			var itm = {"notreg":notreg};
			notifData.notificaciones.push(itm);
		}
		
		$('#not_'+notreg).remove();
		
		//Redireccion segun tipo de notificacion
		var dir = '';
		switch(parseInt(notcodigo)){
			case 1: //Solicitud
				dir = '../reuniones/bsq?T=2';
				break;
			case 2: //Confirmado
				dir = '../reuniones/bsq?T=3';
				break;
			case 3: //Cancelado
				dir = '../reuniones/bsq?T=4';
				break;
			case 4: //Cambio de Horario
				dir = '../reuniones/bsq?T=3';
				break;
			case 5: //Chat
				dir = '../chat/bsq?=chatid' + percoddst;
				break;
			case 6: //sin Link
				dir = '';
				break;
			case 7: //Home
				dir = '../index';
				break;
			case 8: //Reuniones
			case 900: //Reuniones
				dir = '../reuniones/bsq';
				break;
			case 9: //Mensajes
				dir = '../chat/bsq';
				break;
			case 10: //Programa
				dir = '../actividades/bsq';
				break;
			case 11: //Sponsor
				dir = '../stand/bsq';
				break;
			case 12: //Asistente
				dir = '../directorio/bsq';
				break;				
			case 13: //Prensa
				dir = '../prensa/bsq';
				break;
			case 14: //Comunidad
				dir = '../muro/bsq';
				break;
			case 100: //UrlLink
				dir = urllink;
				break;
			
		}
		
		//if(indexPage){
		//	 dir = dir.replace('../','');
		//}
		
		//Insertamos los datos recibidos
		$('#notification').append(
			"<a id='not_"+notreg+"' href='"+dir+"' class='dropdown-item noti-container py-2'>"+
				"<i class='"+icon+color+" float-left d-block font-medium-4 mt-2 mr-2'></i>"+
				"<span class='noti-wrapper'>"+
					"<span class='noti-title line-height-1 text-truncate d-block text-bold-400 "+color+" titulo'>"+nottitulo+"</span>"+
					"<span class='noti-text d-block text-truncate'>"+ pernombre +" "+ perapelli+"  - "+ percargo+" "+ de+" "+ percompan +"</span>"+
				"</span>"+
			 "</a>");
		cantnotif++;
		$("#notinum").html(cantnotif);
		$("#notinum").show();
	});
}

function notificationupd() {
	
	var indexPage = false;
	var urlnotifupd = '../notificaciones/notres.php';
	var urlnotiflei = '../notificaciones/notleidos.php';
	if(document.URL.indexOf('/index')!==-1){
		 urlnotifupd = 'notificaciones/notres.php';
		 urlnotiflei = 'notificaciones/notleidos.php';
		 indexPage=true;
	}
	
	//Busco las ultimas notificaciones
	$.ajax({
		type:"POST",
		url: urlnotiflei,
		data:null,
	}).done(function(rsp){
		var data = $.parseJSON(rsp);
		console.log(data);
		var icon	=' ft-message-circle ';
		var color	=' info ';
		
		$.each(data.notificaciones, function (){			
			//Redireccion segun tipo de notificacion
			var dir = '';
			var nottitulo = this.nottitulo;
			var splittitulo = nottitulo.split("##");
			var urllink=''
			nottitulo=splittitulo[0];
			if(splittitulo.length>1){
				nottitulo=splittitulo[0];
				urllink= splittitulo[1];
			}else{
				nottitulo=splittitulo[0];
			}
			switch(parseInt(this.notcodigo)){
				case 1: //Solicitud
					dir = '../reuniones/bsq?T=2';
					break;
				case 2: //Confirmado
					dir = '../reuniones/bsq?T=3';
					break;
				case 3: //Cancelado
					dir = '../reuniones/bsq?T=4';
					break;
				case 4: //Cambio de Horario
					dir = '../reuniones/bsq?T=3';
					break;
				case 5: //Chat
					dir = '../chat/bsq?chatid=' + this.percoddst;
					break;
				case 6: //sin Link
					dir = '';
					break;
				case 7: //Home
					dir = '../index';
					break;
				case 8: //Reuniones
				case 900: //Reuniones
					dir = '../reuniones/bsq';
					break;
				case 9: //Mensajes
					dir = '../chat/bsq';
					break;
				case 10: //Programa
					dir = '../actividades/bsq';
					break;
				case 11: //Sponsor
					dir = '../stand/bsq';
					break;
				case 12: //Asistente
					dir = '../directorio/bsq';
					break;				
				case 13: //Prensa
					dir = '../prensa/bsq';
					break;
				case 14: //Comunidad
					dir = '../muro/bsq';
					break;
				case 100: //Comunidad
					dir = urllink;
					break;
			}
			
			if(indexPage){
				 dir = dir.replace('../','');
			}
			$('#not_'+this.notreg).remove();
			//Insertamos los datos recibidos
            $('#notification').append(
				"<a id='not_"+this.notreg+"'  href='"+dir+"' class='dropdown-item noti-container py-2'>"+
					"<i class='"+icon+color+" float-left d-block font-medium-4 mt-2 mr-2'></i>"+
					"<span class='noti-wrapper'>"+
						"<span class='noti-title line-height-1 d-block text-bold-400 "+color+" titulo'>"+nottitulo+"</span>"+
						"<span class='noti-text'>"+ this.pernombre+" "+ this.perapelli+" -"+ this.percargo+" "+ this.de+" "+ this.percompan+"</span>"+
					"</span>"+
				 "</a>");
         });
	});
	
	$.ajax({
		type:"POST",
		url: urlnotifupd,
		data:notifData,
	}).done(function(rsp){
		notifData = {"notificaciones":[]};
		cantnotif=0;
		$("#notinum").hide();
	});
};
