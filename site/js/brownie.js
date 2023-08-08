$(document).ready(function(){
    add_row_SpeakerRole();
    eliminarRowNewSpeakerRole();
});


function add_row_SpeakerRole(){
    $('a.addRowSpeakerRole').click(function(e){   
        e.preventDefault();  
        key = $('tr.row-complet:last').attr('data-key');    
        if (key == undefined) {
            keyNuevo = 0;
        } else {
            var keyNuevo = ++key;
        }   
        Id = $(this).attr('data-Id');
        if (Id == undefined) {
            Id = 0;
        }   
        $('table.add_multi_speaker_role').append($('<tr class=\"list row-complet complet-row-key-'+ keyNuevo+'\" data-key=\"'+ keyNuevo+'\">').load(APP_BASE + 'brw/speaker_roles/add_row/' + Id + '/' + keyNuevo,  function() {eliminarRowNewSpeakerRole();}  ));
        return;
    }); 
}

function eliminarRowNewSpeakerRole(){
    $('a.deleteRowNewSpeakerRole').click(function(){
        key = $(this).attr('data-key');    
        $('tr.complet-row-key-'+key).fadeOut();   
        $('tr.complet-row-key-'+key).remove();    
        return false;
    }); 
}