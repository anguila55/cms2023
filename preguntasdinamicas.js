

function Mostrarpregunta(pregunta){

    let preguntaelemento = '';

    preguntaelemento = document.createElement("div");
    
   if(pregunta.tipopregunta == 0){ // es pregunta libre

    preguntaelemento.innerHTML = `

<div class="form-group row">
        <div class="col-md-12">
            <input type="text" id="${pregunta.nombre}" placeholder="${pregunta.textopregunta}"
                class="form-control">
        </div>
</div>`;


   }else{ // es pregunta opciones

    preguntaelemento = document.createElement("div");

    preguntaelemento.classList.add("form-group");
    preguntaelemento.classList.add("row");

    let divcontenedor = '';
    divcontenedor = document.createElement("div");
    divcontenedor.classList.add("col-md-12");
    preguntaelemento.appendChild(divcontenedor);

    var select = document.createElement("select");
    select.name = pregunta.nombre;
    select.id = pregunta.nombre;
    select.classList.add("form-control");
    

    var values = pregunta.opcionespregunta.split(',');

    var optionbase = document.createElement("option");
    
    optionbase.value = null;
    let optionText = document.createTextNode(pregunta.textopregunta);
    optionbase.appendChild(optionText);
    
   
    select.appendChild(optionbase);

    for (const val of values)
    {
        var option = document.createElement("option");
        option.value = val;
        option.text = val;
        select.appendChild(option);
    }

    select.setAttribute("placeholder", pregunta.textopregunta);  
    select.selectedIndex = 0;

    divcontenedor.appendChild(select);

{/* <div class="form-group row {mostrartipo}">
									<div class="col-md-12">
										<select id="tipo" name="tipo" class="form-control" placeholder="{textotipo}">
											<option value="" >{Idioma_Seleccione} {textotipo}</option>
											<option value=0>{tipodeparticipacion1}</option>
											<option value=1>{tipodeparticipacion2}</option>
											<option value=2>{tipodeparticipacion3}</option>
										</select>
									</div>
								</div> */}


   } 


   if (preguntaelemento != ''){
    document.querySelector("#preguntasdinamicascontainer").appendChild( preguntaelemento );
}

}


