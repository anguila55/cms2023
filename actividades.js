
function countdownCharlas(producto){

    var y = setInterval(function() {
    
        if (producto.tipo == 0){
                    var nowcharla = new Date().getTime();
    
                    var start = producto.key*1000 - nowcharla;
                    var finish = producto.fin*1000 - nowcharla;
    
                    var charla1= document.querySelector('#idcharlaR' + producto.value);
                    var charlavivo1= document.querySelector('#idcharlavivoR' + producto.value);
                    var ageconf= document.querySelector('#addeventatc' + producto.cant);
                    
                    
                    charla1.style.display= "";
                    charlavivo1.style.display= "none";
                    
                    if (start < 0 && finish > 0) {
                        charla1.style.display= "none";
                        charlavivo1.style.display= "";
                        
                        
                    }
                    if (finish < 0) {
                    clearInterval(y);
                    charla1.style.display= "";
                    charla1.innerHTML = "<span class='badge bg-secondary p-2 white'>FINALIZADA</span>";
                    ageconf.style.display= "none";
                    
                    charlavivo1.style.display= "none";
                   
                    
                    
                    }
                }else{
                    var now = new Date().getTime();
	
						var distance = (producto.key)*1000 - now;
	
						
						var charla = document.querySelector('#idcharla' + producto.value)
						var charlavivo = document.querySelector('#idcharlavivo' + producto.value)
						var ageconf= document.querySelector('#addeventatc' + producto.cant);

						charla.style.display= "";
                        charlavivo.style.display= "none";
						
						if (distance < 0 && distance >= ((producto.fin*60000)*-1)) {
							charla.style.display= "none";
							charlavivo.style.display= "";
							
						}
						if (distance < ((producto.fin*60000)*-1)) {
						clearInterval(y);
                        charla.style.display= "";
                        charla.innerHTML = "<span class='badge bg-secondary p-2 white'>FINALIZADA</span>";
                        ageconf.style.display= "none";
                        
                        charlavivo.style.display= "none";
						;
                }
            }
                    }, 2000);

        

    }


function Mostrar(producto,idioma){
	let noticias = '';
    
    
        if (producto.tipo == 0){
            noticias = document.createElement("tbody")
            noticias.classList.add("scroll1")
            noticias.classList.add("filaactividades")

            if(idioma === 'ING'){
                noticias.innerHTML = `<tr class="">
                <td class="width-override-100"> <span class="badge badge-info p-2 " style="width:50px;"><i class="icon-camcorder white"></i></span>
                </td>
                <!-- Fecha -->
                <td class="text-truncate width-override-140"><i class=" font-medium-1"></i>
                ${producto.agefch}
                </td>
                <!-- End Fecha -->
        
                <!-- Horaio -->
                <td class=" text-truncate width-override-230"><i
                        class="icon-clock mr-1"></i>${producto.agehorini}-${producto.agehorfin}
                </td>
                <!-- End Horario -->
        
                <!-- Descripcion -->
                <td class="text-truncate text-bold-500 width-override-430"> <a 
                        class="titulo-charla">${producto.titulocharla}</a></td>
                <!-- End Descripción -->
        
                <!-- Ver en vivo -->
                
                <td class="text-truncate width-override-260" style="text-align: right;"> <a id="idcharlaR${producto.agereg}"><span
                            class="badge bg-secondary p-2 white ">LIVE</span></a>
                            <a style="display:none;" id="idcharlavivoR${producto.agereg}" 
                        href="../sala/bsq.php?A=${producto.agereg}"><span
                            class="badge badge-danger p-2  "><i class="fa fa-circle white mr-2"></i>LIVE</span>
                </a><a class="badge p-2 white white addeventatc addeventatc__index fw-700 text-uppercase" data-dropdown-x="left" style="background-color: var(--addevent); position: inherit;  z-index:inherit;"><i
                class="icon-calendar pl-1 pr-1"></i>
                <span class="start">${producto.fechacalendar} ${producto.horacalendar}</span>
                <span class="end">${producto.fechacalendar} ${producto.minutosfincalendar}</span>
                <span class="title">${producto.titulocalendar}</span>
                <span class="location">${producto.locationcalendar}</span>
                <span class="description">${producto.SisNombreEvento}</span>
            </a></td>
                <!-- End ver en vivo -->
            </tr>`
            }else{
                noticias.innerHTML = `<tr class="">
            <td class="width-override-100"> <span class="badge badge-info p-2 " style="width:50px;"><i class="icon-camcorder white"></i></span>
            </td>
            <!-- Descripcion -->
            <td class="text-truncate text-bold-500 width-override-470"> <a 
                    class="titulo-charla">${producto.titulocharla}</a></td>
            <!-- End Descripción -->
            <!-- Fecha -->
            <td class="text-truncate width-override-340"><i class=" font-medium-1"></i>
            ${producto.agefch} <span style="margin-left:2rem;"><i
            class="icon-clock mr-1"></i>${producto.agehorini}-${producto.agehorfin}</span>
            </td>
            <!-- End Fecha -->
    
            <!-- Ver en vivo -->
            
            <td class="text-truncate width-override-260" style="text-align: right;"> <a id="idcharlaR${producto.agereg}"><span
                        class="badge bg-secondary p-2 white ">VIVO</span></a>
                        <a style="display:none;" id="idcharlavivoR${producto.agereg}" 
                    href="../sala/bsq.php?A=${producto.agereg}"><span
                        class="badge badge-danger p-2  "><i class="fa fa-circle white mr-2"></i>LIVE</span>
            </a><a class="badge p-2 white white addeventatc addeventatc__index fw-700 text-uppercase" data-dropdown-x="left" style="background-color: var(--addevent); position: inherit;  z-index:inherit;"><i
            class="icon-calendar pl-1 pr-1"></i>
            <span class="start">${producto.fechacalendar} ${producto.horacalendar}</span>
            <span class="end">${producto.fechacalendar} ${producto.minutosfincalendar}</span>
            <span class="title">${producto.titulocalendar}</span>
            <span class="location">${producto.locationcalendar}</span>
            <span class="description">${producto.SisNombreEvento}</span>
        </a></td>
            <!-- End ver en vivo -->
        </tr>`  
            }

          

        }else{
            noticias = document.createElement("tbody")
        noticias.classList.add("scroll1")
        noticias.classList.add("filaactividades")

        if(idioma === 'ING'){
            noticias.innerHTML = `<tr class="">
        <td class="width-override-100"> <span class="badge badge-warning p-2 " style="width:50px;"><i class="fa fa-handshake-o white"></i></span>
        </td>
        <!-- Descripcion -->
            <td class="text-truncate text-bold-500 width-override-470"> <a 
                    class="titulo-charla">${producto.titulocharla}</a></td>
            <!-- End Descripción -->
            <!-- Fecha -->
            <td class="text-truncate width-override-340"><i class=" font-medium-1"></i>
            ${producto.agefch} <span style="margin-left:2rem;"><i
            class="icon-clock mr-1"></i>${producto.agehorini}</span>
            </td>
            <!-- End Fecha -->
       

        <!-- Ver en vivo -->
        
        <td class="text-truncate width-override-260" style="text-align: right;"> <a id="idcharla${producto.agereg}"><span
                    class="badge bg-secondary p-2 white ">COMING SOON</span></a>
                    <a style="display:none;" id="idcharlavivo${producto.agereg}" 
                    onclick="showCall(${producto.agereg});"><span
                    class="badge badge-danger p-2 "><i class="fa fa-circle white mr-2"></i>ENTER</span>
        </a><a class="badge p-2 white white addeventatc addeventatc__index fw-700 text-uppercase" data-dropdown-x="left" style="background-color: var(--addevent); position: inherit;  z-index:inherit;"><i
        class="icon-calendar pl-1 pr-1"></i>
        <span class="start">${producto.fechacalendar} ${producto.horacalendar}:${producto.minutoscalendar}</span>
        <span class="end">${producto.fechacalendar} ${producto.minutosfincalendar}</span>
        <span class="title">${producto.titulocalendar}</span>
        <span class="location">${producto.locationcalendar}</span>
        <span class="description">${producto.SisNombreEvento}</span>
    </a>
        </td>
        <!-- End ver en vivo -->
    </tr>`
        }else{
           noticias.innerHTML = `<tr class="">
        <td class="width-override-100"> <span class="badge badge-warning p-2 " style="width:50px;"><i class="fa fa-handshake-o white"></i></span>
        </td>
        <!-- Descripcion -->
            <td class="text-truncate text-bold-500 width-override-470"> <a 
                    class="titulo-charla">${producto.titulocharla}</a></td>
            <!-- End Descripción -->
            <!-- Fecha -->
            <td class="text-truncate width-override-340"><i class=" font-medium-1"></i>
            ${producto.agefch} <span style="margin-left:2rem;"><i
            class="icon-clock mr-1"></i>${producto.agehorini}</span>
            </td>
            <!-- End Fecha -->
       

        <!-- Ver en vivo -->
        
        <td class="text-truncate width-override-260" style="text-align: right;"> <a id="idcharla${producto.agereg}"><span
                    class="badge bg-secondary p-2 white ">PROXIMAMENTE</span></a>
                    <a style="display:none;" id="idcharlavivo${producto.agereg}" 
                    onclick="showCall(${producto.agereg});"><span
                    class="badge badge-danger p-2 "><i class="fa fa-circle white mr-2"></i>INGRESAR</span>
        </a><a class="badge p-2 white white addeventatc addeventatc__index fw-700 text-uppercase" data-dropdown-x="left" style="background-color: var(--addevent); position: inherit;  z-index:inherit;"><i
        class="icon-calendar pl-1 pr-1"></i>
        <span class="start">${producto.fechacalendar} ${producto.horacalendar}:${producto.minutoscalendar}</span>
        <span class="end">${producto.fechacalendar} ${producto.minutosfincalendar}</span>
        <span class="title">${producto.titulocalendar}</span>
        <span class="location">${producto.locationcalendar}</span>
        <span class="description">${producto.SisNombreEvento}</span>
    </a>
        </td>
        <!-- End ver en vivo -->
    </tr>`
        }
       

        }
        

    
    
    
    if (noticias != ''){
        document.querySelector("#recent-orders").appendChild( noticias )
    }
    
    
    }