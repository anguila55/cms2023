function Mostrar(producto){

	let noticias = '';
    
    if ((producto.pretamano == 1 || producto.pretamano == '') && producto.pretipo != 2 ){
        noticias = document.createElement("div")
        noticias.classList.add("col-sm-12")
        noticias.classList.add("col-md-3")
        noticias.classList.add("col-lg-3")
        noticias.classList.add("d-flex")
        noticias.classList.add("justify-content-center")
        noticias.innerHTML = `<div class="mb-3 justify-content-center" style="cursor:pointer; width:100%;">
                <div class="card-contact px-3" style="height:400px;">
            

                    <div class="mb-2">
            
                        <div class="img-fluid imagentarjeta" style="background-image: url(${producto.preimg});"> </div>
                    </div>
            
                    <div>

                    <div class="company text-truncate font-small-2 ml-1" style="text-align:left;">
    
                            <button class="btn bg-main-event text-white tag" style="cursor: default; padding: 3px !important;">${producto.precatego}</button>
                        </div>
            
                        <div class="title ml-1" style="text-align:left;">
    
                            <h2 class="card-title text-bold-500 titlevent" style="font-size: 1.3rem;">${producto.pretitulo}</h2>
                        
                        </div>
                        
    
                    </div>
            
    
                </div>
            
            </div>`

    }
    if (producto.pretamano == 2 && producto.pretipo != 2){

        noticias = document.createElement("div")
        noticias.classList.add("col-sm-12")
        noticias.classList.add("col-md-6")
        noticias.classList.add("col-lg-6")
        noticias.classList.add("d-flex")
        noticias.classList.add("justify-content-center")
        noticias.innerHTML = `<div class="mb-3 justify-content-center" style="cursor:pointer; width:100%;">
                <div class="card-contact px-3" style="background-image: url(${producto.preimg}); height:400px;">
            
                    
                
                    <div>
            
                        <div class="company text-truncate font-small-2  ml-1 " style="text-align:left; margin-top:20%">
        
                            <button class="btn bg-main-event text-white tag" style="cursor: default; padding: 3px !important; font-size: 15px;">${producto.precatego}</button>
                        </div>
                        <div class="title ml-1" style="text-align:left;">
    
                            <h2 class="card-title text-bold-500 white" style="font-size: 2 rem;text-shadow: 1px 2px 2px black!important;">${producto.pretitulo}</h2>
                        
                        </div>
                        
    
                        <div class="title ml-1" style="height: 100px !important; text-align:left; ">
    
                            <p class="font-medium-2 card-title text-bold-300 white" style="text-shadow: 1px 2px 2px black!important;">${producto.prebajada}</p>
                        </div>
                    </div>
            
    
                </div>
            
            </div>`

    }
    if (producto.pretamano == 3 && producto.pretipo != 2){

        noticias = document.createElement("div")
        noticias.classList.add("col-sm-12")
        noticias.classList.add("col-md-9")
        noticias.classList.add("col-lg-9")
        noticias.classList.add("d-flex")
        noticias.classList.add("justify-content-center")
        noticias.innerHTML = `<div class="mb-3 justify-content-center" style="cursor:pointer; width:100%;">
                <div class="card-contact px-3" style="height:400px;">
                    <div class="row" style="margin-bottom:10px">
                        <div class="col-md-4">
                            <div class="company text-truncate font-small-2  ml-1" style="text-align:left; margin-top:2%">
                
                                <button class="btn bg-main-event text-white tag" style="cursor: default; padding: 3px !important;">${producto.precatego}</button>
                            </div>

                            <div class="title ml-1" style="text-align:left;">
            
                                    <h2 class="card-title text-bold-500 titlevent" style="font-size: 1.7rem;">${producto.pretitulo}</h2>
                                
                            </div>
                            <div class="title ml-1 " style="height: 100px !important; text-align:left;">
            
                                    <p class="font-medium-2 card-title text-bold-300 mb-2">${producto.prebajada}</p>
                            </div>
                        </div>
                        <div class="col-md-8 " style="margin-top:10px;">
                            <div>
                    
                                <div class="img-fluid imagentarjeta" style="background-image: url(${producto.preimg}); height:500px;"> </div>
                            </div>
                        </div>
                    </div>
                
                </div>
            
            </div>`

    }
    if ((producto.pretamano == 1 || producto.pretamano == '') && producto.pretipo == 2 ){
        noticias = document.createElement("div")
        noticias.classList.add("col-sm-12")
        noticias.classList.add("col-md-3")
        noticias.classList.add("col-lg-3")
        noticias.classList.add("d-flex")
        noticias.classList.add("justify-content-center")
       

        noticias.innerHTML = `<div class="mb-3 justify-content-center" style="cursor:pointer; width:100%;">
                <div class="card-contact px-3" style="background-image: url(${producto.preimg}); height:400px; background-size:cover;border: 4px solid #b3f5ab;">
  
                </div>
            
            </div>`

    }
    if (producto.pretamano == 2 && producto.pretipo == 2){

        noticias = document.createElement("div")
        noticias.classList.add("col-sm-12")
        noticias.classList.add("col-md-6")
        noticias.classList.add("col-lg-6")
        noticias.classList.add("d-flex")
        noticias.classList.add("justify-content-center")
        noticias.innerHTML = `<div class="mb-3 justify-content-center" style="cursor:pointer; width:100%;">
                <div class="card-contact px-3" style="background-image: url(${producto.preimg}); height:400px;background-size:cover;border: 4px solid #b3f5ab;">
            
            
    
                </div>
            
            </div>`

    }
    if (producto.pretamano == 3 && producto.pretipo == 2){

        noticias = document.createElement("div")
        noticias.classList.add("col-sm-12")
        noticias.classList.add("col-md-9")
        noticias.classList.add("col-lg-9")
        noticias.classList.add("d-flex")
        noticias.classList.add("justify-content-center")
        noticias.innerHTML = `<div class="mb-3 justify-content-center" style="cursor:pointer; width:100%;">
                <div class="card-contact px-3" style="background-image: url(${producto.preimg}); height:400px;background-size:cover;border: 4px solid #b3f5ab;">
                    
                    
            
                </div>
            
            </div>`

    }
    
    if (noticias != ''){
        document.querySelector("#noticias").appendChild( noticias )
    }
    


    noticias.onclick = (evento) => {

        let elemento = evento.target.innerHTML

        if (elemento == producto.precatego){

            let filtronombre = 0;
            if(elemento=='Exposiciones'){
                filtronombre=1;
            }
            else if(elemento=='Ferias'){
                filtronombre=2 ;
            }
            else if(elemento=='Rondas'){
                filtronombre=3 ;
            }
            var data = {
				"filtronombre": filtronombre
			};

		
			$('#DataBrowser').load('brw.php', data);

			$('#notification-sidebar').removeClass('open');
        }else{

            if (producto.pretipo !=2){
                let reg = producto.prereg;
                let replaceletters = producto.pretitulo;
                let replaceSpace = replaceletters.normalize("NFD").replace(/[\u0300-\u036f]/g, "")
                var result = replaceSpace.replace(/ /g, "-");
                
               
               // let data = reg+'||'+result;
                let data = reg+'||'+result;
    
                //$('#DataView').addClass('modal-xs');
                //$('#DataView').removeClass('modal-lg');
                //$('#DataBrowser').load('nota.php',data);
                //$('#DataMaestro').css('display','none');
                //$('#DataBrowser').css('display','');
                window.location='../nota/bsq?ID='+data;
    
            }else{
    
                window.open(producto.preurl, '_blank');
            }

        }
        
    }

    
    }