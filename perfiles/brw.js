function Eliminar(reg,fechalabelstring){
    var parentDiv = document.querySelector("#horarios");
    if (parentDiv.querySelector(`#labelhorarios${fechalabelstring}`)==null){
        horas = document.createElement("div");
        horas.classList.add("col-12");
        horas.innerHTML = `<label id="labelhorarios${fechalabelstring}" class="mt-1 fw-700" for="timesheetinput3">${reg}</label>`
        
        document.querySelector("#horarios").append( horas );
    }
}
function Mostrar(producto){

	let horas = '';
    let datacolor='';
    let datajackcolor='';
    let horachecked='';
    let horadisabled='';
    var parentDiv = document.querySelector("#horarios");
   
        datacolor= 'white'	;
        datajackcolor='success'	;
    if(producto.horachecked==1){
        horachecked='checked';
    }else{
        horachecked='';
        }

        if(producto.horadisabled==1){
            horadisabled='disabled';
        }else{
            horadisabled='';
            }
        
        
  
       if (parentDiv.querySelector(`#distime${producto.horaids}`)==null){

       
        horas = document.createElement("div")
        horas.classList.add("mr-1")
        

        horas.innerHTML = `<div class="container-disponibilidad containerdisp d-block">

            <input type="checkbox" id="distime${producto.horaids}" name="distime" class="switchery d-none"
                data-color="${datacolor}" data-jack-color="${datajackcolor}" data-fecha="${producto.fechabd}"
                data-hora="${producto.horabd}" ${horachecked} ${horadisabled} />

                <label class="claselabel" for="distime${producto.horaids}">${producto.hora}</label>
        </div>`

    
    
        if (horas != '' ){
            document.querySelector("#horarios").append( horas );
        }
    }
    
    }