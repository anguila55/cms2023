function addPointsGaming(val,exp){
    var data = {"tipo":val,
                "valor":exp};
    $.ajax({
    type: "POST",
    url: '../gaming/grbpuntos.php',
    data: data
    }).done(function(rsp) {
        data = $.parseJSON(rsp);			
            
    });
}