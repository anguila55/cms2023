function loadCategorias(reg){
    //Sectores seleccionados
    var select = 'secselect_'+reg;
    var checkBox = document.getElementById(select);
    
    if (checkBox.checked == true){

    }else{
        
        var subselect = 'secsubselect_'+reg;
        var num = document.querySelectorAll(`[data-foo="secsubselect_${reg}"]`);
        num.forEach(function(nombre, i) {  
            nombre.checked=false;
        })
    }
    
}