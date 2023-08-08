function Eliminar(){

    document.querySelector("#horarios").innerHTML='';

}
function Mostrar(producto){

	let horas = '';
    let datacolor='';
    let datajackcolor='';
    let horadisabled='';
    if(producto.dispExists==0){
        datacolor= 'danger'	;
        datajackcolor='danger'	;
        horadisabled='disabled checked';
    }else if(producto.dispExists==2){
        datacolor= 'black'	;
        datajackcolor='black'	;
        horadisabled='disabled checked';
    }else if(producto.dispExists==3){ //Tengo reuniones en horario
        datacolor= 'black'	;
        datajackcolor='black'	;
        horadisabled='disabled checked';
    }
    else if(producto.dispExists==4){ //Tengo reuniones en horario
        datacolor= 'danger'	;
        datajackcolor='danger'	;
        horadisabled='disabled checked';
    }else if(producto.dispExists==5){ //No tengo mesas flotantes libres
        datacolor= 'black';
        datajackcolor='black'	;
        horadisabled='disabled checked';
    }else{
        datacolor= 'success'	;
        datajackcolor='success'	;
        horadisabled='';
        
    }
    if(producto.reuExists==0){
        horadisabled='disabled checked';
    }else{
        //horadisabled='';
    }
    if(producto.horadisabled==1){
        horadisabled='disabled checked';
    }
    
        horas = document.createElement("div")
        horas.classList.add("mr-1")
        

        horas.innerHTML = `<div class="container-disponibilidad d-block">

            <input type="checkbox" id="coordtime${producto.horaid}" name="coordtime" class="switchery d-none"
                data-color="${datacolor}" data-jack-color="${datajackcolor}" data-fecha="${producto.fechabd}"
                data-hora="${producto.horabd}" ${horadisabled} onclick='handleClick(this);'/>

            <label class="claselabel" for="coordtime${producto.horaid}" style="background:${datacolor}">${producto.hora}</label>
               
        </div>`

    
    
    if (horas != ''){
        document.querySelector("#horarios").appendChild( horas );
    }
    }