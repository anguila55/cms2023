<?php

/**
 * ---Diccionario de idiomas---
 * 
 * Function content $key and $value;
 * Funciones($string) return string 
 */

//-----------------------------------------------------------------------------------------------------
function getIdiomasDB() {
    return ['Reunion solicitada' => 'Meeting requested ',
            'Reunion confirmada' => 'Meeting confirmed',
            'Reunion cancelada'  =>'Meeting canceled',
            'Reunion confirmada con cambio de Horario ' => 'Meeting confirmed with change of Schedule'];
}
function Message(){
    return['Guardado correctamente!' => 'Saved correctly',
           'Perfil eliminado!' => 'Profile removed!',
           'Error al eliminar el perfil!' => 'Error deleting the profile!',
           'Perfil activado!' =>'Profile activated!',
           'Error al activar el perfil!'=>'Error activating the profile!',
           'Perfil liberado!' => 'Profile approved!',
           'Error al liberar el perfil!' => 'Profile approval failed!',
           'Login iniciado!' => 'Login started!',
           'Permiso actualizado!' => 'Permit updated!',
           'Error al actualizar el permiso!' => 'Error updating the permission!',
           'Sector eliminado!' => 'Sector eliminated!',
           'Error al eliminar el sector!' => 'Error removing the sector!',
           'Subsector eliminado!' => 'Subsector removed!',
           'Error al eliminar el subsector!' => 'Error removing the subsector!',
           'Categoria eliminado!' => 'Category eliminated!'
        ];
}

//-----------------------------------------------------------------------------------------------------

/**
 * @param $tmpl
 */
function DDIdioma($tmpl){
    $tmpl->setVariable('cambiar_contrasena','Edit Profile / Password');
    $tmpl->setVariable('textopopinicio1','For all the activities of this event it is important that you choose the time of the country where you are going to live it.');
    $tmpl->setVariable('textopopinicio2','To let other people know about you, fill in your company description.');
    $tmpl->setVariable('textopopinicio3','Select your availability of Schedules, tell others about your available spaces.');
    $tmpl->setVariable('guardadocorrectamente','Saved correctly!');
    
    $tmpl->setVariable('mesatipo','Table Type'); 
    $tmpl->setVariable('mesatipo1','Fixed table - assigned to user'); 
    $tmpl->setVariable('mesatipo2','Floating table');

    $tmpl->setVariable('paso','Step'); 
    $tmpl->setVariable('finalizar','Finish'); 
    $tmpl->setVariable('cargando','Loading');
    $tmpl->setVariable('duraciontexto','Duration'); 
    $tmpl->setVariable('minutostexto','minutes'); 
    $tmpl->setVariable('comentarioregistracion','Registration comment'); 
    $tmpl->setVariable('tipodeparticipacion','Meeting type'); 
    $tmpl->setVariable('tipodeparticipacion1','Virtually only'); 
    $tmpl->setVariable('tipodeparticipacion2','Face-to-face only'); 
    $tmpl->setVariable('tipodeparticipacion3','Either'); 
    $tmpl->setVariable('admregistro','Registry Admin'); 
    $tmpl->setVariable('postulaciones','Postulations');
    $tmpl->setVariable('creadesafio','Create challenge');
    $tmpl->setVariable('misdesafios','My challenges');
    $tmpl->setVariable('destacadocartel','Important');
    $tmpl->setVariable('postularmenombre','Apply');
    $tmpl->setVariable('compartir','SHARE');
    $tmpl->setVariable('compartechat','Share your details in the CHAT window and view all shared cards in ASSISTANTS.');
    $tmpl->setVariable('charlas','Talks');
    $tmpl->setVariable('vivo','LIVE');
    $tmpl->setVariable('agendarreunion','Request Meeting');
    $tmpl->setVariable('sobrenosotros','About Us');
    $tmpl->setVariable('standprevios','Previous Stand');
    $tmpl->setVariable('standsiguiente','Next Stand');
    $tmpl->setVariable('dimensiones','Suggested Dimensions');
    $tmpl->setVariable('caracteres','characters');
    $tmpl->setVariable('noreuniones','You do not have scheduled or pending meetings, please go to the directory of attendees to request a meeting with a counterpart.');
    $tmpl->setVariable('acepteterminos','Accept Terms and Conditions to continue');
    $tmpl->setVariable('completeelcampo','Fill the required field ');
$tmpl->setVariable('ingresarvivo','Enter live');
$tmpl->setVariable('Idioma_Subsector','Subsector');
$tmpl->setVariable('Idioma_Subsectores',' Subsectors');
$tmpl->setVariable('Idioma_Descripcion',' Description');
$tmpl->setVariable('Idioma_Empresa',' Company');
$tmpl->setVariable('Idioma_Seccion',' Sector');
$tmpl->setVariable('Idioma_MaestroSec',' Sectors Master');
$tmpl->setVariable('Idioma_MaestroSubSec',' Subsector Master');
$tmpl->setVariable('Idioma_MaestroIdioma',' Language Master');
$tmpl->setVariable('Idioma_MaestroSubCategorias','Subcategories Master');
$tmpl->setVariable('Idioma_Traducciones',' Translation');
$tmpl->setVariable('Idioma_GuardarSwal',' SAVE');
$tmpl->setVariable('Idioma_Confirmar',' Do you want to save changes?');
$tmpl->setVariable('Idioma_BotonConf',' Save');
$tmpl->setVariable('Idioma_BotonCan',' Cancel');
$tmpl->setVariable('Idioma_SelecIdiom',' Select a language');
$tmpl->setVariable('Idioma_Fltnom',' Name is missing');
$tmpl->setVariable('Idioma_FltCorreo','E-mail is missing');
$tmpl->setVariable('Idioma_FltApe',' Last name is missing');
$tmpl->setVariable('Idioma_FltComp',' Company is missing');
$tmpl->setVariable('Idioma_FltPai','Country is missing');
$tmpl->setVariable('Idioma_FltZh','TimeZone is missing');
$tmpl->setVariable('Idioma_FltSelecComp',' Select availability');
$tmpl->setVariable('Idioma_Configuracion',' Settings');
$tmpl->setVariable('Idioma_Perfiles',' Profiles');
$tmpl->setVariable('Idioma_Productos',' Products & Services');
$tmpl->setVariable('Idioma_Sectores',' Sectors');
$tmpl->setVariable('Idioma_Categorias',' Categories');
$tmpl->setVariable('Idioma_SubC',' Subcategories');
$tmpl->setVariable('Idioma_Exportar',' Export');
$tmpl->setVariable('Idioma_Noticias',' Press');
$tmpl->setVariable('Idioma_Desafio','Challenge' );
$tmpl->setVariable('Idioma_Agenda',' Agenda');
$tmpl->setVariable('Idioma_Mensajeria',' Messages');
$tmpl->setVariable('Idioma_ExpositoresApp',' Exhibitors app');
$tmpl->setVariable('Idioma_Directorio',' Directory');
$tmpl->setVariable('Idioma_Buscar',' Search');
$tmpl->setVariable('Idioma_Sponsor','Micrositio' );
$tmpl->setVariable('Idioma_Buscartodos',' All');
$tmpl->setVariable('Idioma_BuscartodosFiltro',' Delete filters');
$tmpl->setVariable('Idioma_Buscartodas',' See All');
$tmpl->setVariable('Idioma_Recomendados',' Recommended');
$tmpl->setVariable('Idioma_Reuniones',' Meetings');
$tmpl->setVariable('Idioma_ReunionesConf','Meetings Confirmed/Total' );
$tmpl->setVariable('Idioma_ReunionesEnviadas',' Sent Meetings');
$tmpl->setVariable('Idioma_ReunionesRecibidas',' Meetings Received');
$tmpl->setVariable('Idioma_ReunionesConfirmadas',' Confirmed Meetings');
$tmpl->setVariable('Idioma_ReunionesCanceladas',' Canceled Meetings');
$tmpl->setVariable('Idioma_Calendario',' Calendar');
$tmpl->setVariable('Idioma_Actividades',' Activities');
$tmpl->setVariable('Idioma_AgendaActvidades',' Activity schedule');
$tmpl->setVariable('Idioma_CalendarioAc',' Activities Calendar');
$tmpl->setVariable('Idioma_Mesas',' Room');
$tmpl->setVariable('Idioma_VerPerf',' Profile');
$tmpl->setVariable('Idioma_Reunion',' Meeting');
$tmpl->setVariable('Idioma_Coordinar',' Accept Meeting');
$tmpl->setVariable('Idioma_Cancelar',' Cancel');
$tmpl->setVariable('Idioma_miPerfil',' My profile');
$tmpl->setVariable('Idioma_Salir',' Log out');
$tmpl->setVariable('Idioma_fav',' Favorites');
$tmpl->setVariable('Idioma_Disponibilidad',' Availability');
$tmpl->setVariable('Idioma_Filtros',' Filters');
$tmpl->setVariable('Idioma_PLbtodos','All');
$tmpl->setVariable('Idioma_PLbverSolofav',' View favorites only');
$tmpl->setVariable('Idioma_PLbactivos',' Actives');
$tmpl->setVariable('Idioma_PLbeliminados',' Eliminated');
$tmpl->setVariable('Idioma_PLbnoliberados',' Not approved');
$tmpl->setVariable('Idioma_PLbcorreonoconf',' Unconfirmed email');
$tmpl->setVariable('Idioma_CBtipo',' Type');
$tmpl->setVariable('Idioma_MSTPerfiles',' Master profile');
$tmpl->setVariable('Idioma_MSTDatosPersonales',' Personal information');
$tmpl->setVariable('Idioma_Nombre',' Name');
$tmpl->setVariable('Idioma_Nombrecontrol','Control');
$tmpl->setVariable('Idioma_Apellido',' Last name');
$tmpl->setVariable('Idioma_Compania',' Company');
$tmpl->setVariable('Idioma_Cargo',' Position');
$tmpl->setVariable('Idioma_Idioma',' Language');
$tmpl->setVariable('Idioma_Seleccione',' Please select');
$tmpl->setVariable('Idioma_DescripcionEmpresa','Company´s description');
$tmpl->setVariable('Idioma_Correo',' Mail');
$tmpl->setVariable('Idioma_Telefono',' Phone number');
$tmpl->setVariable('Idioma_SitioWeb',' Website');
$tmpl->setVariable('Idioma_Contacto',' Contact');
$tmpl->setVariable('Idioma_Domicilio',' Home');
$tmpl->setVariable('Idioma_Direccion',' Address');
$tmpl->setVariable('Idioma_Ciudad',' City');
$tmpl->setVariable('Idioma_Estado',' State');
$tmpl->setVariable('Idioma_CodPostal',' Zip Code');
$tmpl->setVariable('Idioma_Pais',' Country');
$tmpl->setVariable('Idioma_DatosDeAc',' Web access data');
$tmpl->setVariable('Idioma_Usuario',' User');
$tmpl->setVariable('Idioma_Contrasena',' Password');
$tmpl->setVariable('Idioma_Guardar',' Save');
$tmpl->setVariable('Idioma_Clasificar',' Sectors');
$tmpl->setVariable('Idioma_ClasificarVentas','Offer');
$tmpl->setVariable('Idioma_ClasificarCompras','Demand');
$tmpl->setVariable('Idioma_ClasificarVentasDescri','The highlighted fields match your demand');
$tmpl->setVariable('Idioma_ClasificarComprasDescri','The highlighted fields match your offer');
$tmpl->setVariable('Idioma_ClasificarIntereses','Interests');
$tmpl->setVariable('Idioma_ClasificarInteresesDescri','The highlighted match with your interests .');
$tmpl->setVariable('Idioma_Rubros',' Activity');
$tmpl->setVariable('Idioma_Cerrar',' Close');
$tmpl->setVariable('Idioma_OrdenarPor',' Sort by');
$tmpl->setVariable('Idioma_Eliminar','Delete');
$tmpl->setVariable('Idioma_ConfEliminar',' Do you confirm deleting the profile?');
$tmpl->setVariable('Idioma_ConfEliminarSec',' Do you confirm deleting selected?');
$tmpl->setVariable('Idioma_ConfEliminarSubSec',' Do you confirm deleting the subsector?');
$tmpl->setVariable('Idioma_ConfEliminarCat',' Do you confirm deleting the category?');
$tmpl->setVariable('Idioma_Activar',' ACTIVATE');
$tmpl->setVariable('Idioma_ActivarPerf',' Do you want to reactivate the profile?');
$tmpl->setVariable('Idioma_LiberarAccs',' APPROVE ACCESS');
$tmpl->setVariable('Idioma_ConfLibPerf',' Do you wantt to approve the profile?');
$tmpl->setVariable('Idioma_Permisos',' PERMISSIONS');
$tmpl->setVariable('Idioma_ConfPerFil',' Do you confirm to change the profile permission?');
$tmpl->setVariable('Idioma_ConfPerFiles',' Do you confirm to change permission to all profiles?');
$tmpl->setVariable('Idioma_IngfPerFil',' LOG AS PROFILE');
$tmpl->setVariable('Idioma_ConfIngPerf',' Do you confirm logging as the selected profile?');
$tmpl->setVariable('Idioma_ZonaHoraria',' Time zone');
$tmpl->setVariable('Idioma_Encuesta','Surveys');
$tmpl->setVariable('Idioma_MaestroEnc',' Survey Master');
$tmpl->setVariable('Idioma_MaestroEncPreg',' Question Master');
$tmpl->setVariable('Idioma_ReuUrl',' Meeting Link');
$tmpl->setVariable('Idioma_Speakers',' Speakers Master');
$tmpl->setVariable('Idioma_Speakers1',' Speakers');
$tmpl->setVariable('Idioma_Orden',' Order');
$tmpl->setVariable('Idioma_Imagen',' Image');
$tmpl->setVariable('Idioma_Parametros',' Parameters');
$tmpl->setVariable('Idioma_MaestroParametros',' Parameter Master');
$tmpl->setVariable('Idioma_Observacion','Observation');
$tmpl->setVariable('Aceptar_Reprogramar','Accept/Edit');
$tmpl->setVariable('Ingresar_Reunion','Enter meeting');
$tmpl->setVariable('Reunion_Finalizada','Ended');


 

//--LOGIN
$tmpl->setVariable('sincuenta','Don’t have an account?');
$tmpl->setVariable('sincontrasena','Forgot your password?');
$tmpl->setVariable('inscribite',' Sign Up');
$tmpl->setVariable('recuperar',' Reset');
$tmpl->setVariable('iniciar',' Sign Up');
    
//-- MENU
$tmpl->setVariable('menu',' Menu');
$tmpl->setVariable('cronograma',' SCHEDULE');
$tmpl->setVariable('cronograma2','Schedule');
$tmpl->setVariable('speakers',' Speakers');
$tmpl->setVariable('asistentes','Networking');
$tmpl->setVariable('asistentesbanner','NETWORKING');
$tmpl->setVariable('asistentes2','Participants');
$tmpl->setVariable('buscar',' Search ');
$tmpl->setVariable('reunion',' Meetings');
$tmpl->setVariable('reunion2',' My Meetings');
$tmpl->setVariable('agenda',' My Agenda');
$tmpl->setVariable('mensajes',' Messages');
$tmpl->setVariable('prensa',' News');
$tmpl->setVariable('ayuda',' Help Desk');
$tmpl->setVariable('virtual',' Virtual Tour');
$tmpl->setVariable('accesosrapidos','QUICK ACCESS');
$tmpl->setVariable('hallcomercial','SPONSORS');
$tmpl->setVariable('mapanavbar','MAP');
$tmpl->setVariable('expositores','Sponsors');
$tmpl->setVariable('cocreate','CO-CREATE');
$tmpl->setVariable('novedades','INFO');
$tmpl->setVariable('ofertas','OFFERS');
$tmpl->setVariable('comunidad','COMUNITY');
$tmpl->setVariable('comunidad2','LOUNGE');
$tmpl->setVariable('mesasredondas','WORKSHOPS');

//-- DESAFIOS
$tmpl->setVariable('infodesafios',' Información Desafios');
$tmpl->setVariable('areadesafios',' Area de Desafio');
$tmpl->setVariable('desafiodesafios',' Desafio');
$tmpl->setVariable('buscamosdesafios',' Que buscamos?');
    
//-- REGISTRO
$tmpl->setVariable('nombme',' Name');
$tmpl->setVariable('apellido',' Last Name');
$tmpl->setVariable('institucio',' Institution / Company');
$tmpl->setVariable('celular',' Cell Phone');
$tmpl->setVariable('dni',' ID / Passport');
$tmpl->setVariable('profesion',' Profession');
$tmpl->setVariable('domicilio',' Home');
$tmpl->setVariable('Localidad',' City');
$tmpl->setVariable('Provincia',' Province');
$tmpl->setVariable('tipoperfil','Select profile');
$tmpl->setVariable('seleccionaintereses','Select your interests:');
$tmpl->setVariable('emailcoinciden','Emails do not match');
$tmpl->setVariable('pais',' Select Country');
$tmpl->setVariable('cpostal',' Zip Code');
$tmpl->setVariable('email',' Email (User)');
$tmpl->setVariable('confirmeemail','Confirm');
$tmpl->setVariable('contrasena',' Password');
$tmpl->setVariable('atras',' Back');
$tmpl->setVariable('siguiente',' Next');
$tmpl->setVariable('paginacionanterior',' PREVIOUS');
$tmpl->setVariable('paginacionsiguiente',' NEXT');
$tmpl->setVariable('registro',' SIGN UP');
$tmpl->setVariable('registrate',' Sign Up');
$tmpl->setVariable('acepto1',' I Agree To Receive Information about the institution’s activities.');
$tmpl->setVariable('acepto2',' I Agree To Receive Information about the Companies Participating In the Event.');
$tmpl->setVariable('terminos',' Terms And Conditions Of Use. (Required) Download The Terms And Conditions Of Use Here.');
    
$tmpl->setVariable('dia1',' Monday');
$tmpl->setVariable('dia2',' Tuesday');
$tmpl->setVariable('dia3',' Wednesday');
$tmpl->setVariable('dia4',' Thursday');
$tmpl->setVariable('dia5',' Friday');

$tmpl->setVariable('dia',' Day');
$tmpl->setVariable('hora',' Hour');
$tmpl->setVariable('titulo',' Title');
$tmpl->setVariable('conferencia',' Conference');
$tmpl->setVariable('ver-mas',' See More');
$tmpl->setVariable('proximas-actividades',' Next activities');
$tmpl->setVariable('agenda-actividades',' Activities');
$tmpl->setVariable('sala1',' Conference Room ');

$tmpl->setVariable('iniciochat',' Write your message here');
$tmpl->setVariable('iniciochaterror',' Write your message');
$tmpl->setVariable('iniciotitulo',' Start a conversation with ');
$tmpl->setVariable('iniciofrom','from');
$tmpl->setVariable('iniciotituloreunion',' Request meeting to ');
$tmpl->setVariable('iniciotituloreunion2',' Meeting request from ');

//-- MICROSITIO
$tmpl->setVariable('somos',' Who we are');
$tmpl->setVariable('ofrecemos',' What We Offer');
$tmpl->setVariable('reunion',' Meeting');
$tmpl->setVariable('representantes',' Representatives');
$tmpl->setVariable('chat',' Chat');
$tmpl->setVariable('contacto','Information');

$tmpl->setVariable('Idioma_QrBrochure','Download Brochure');

    /// Cronograma
$tmpl->setVariable('Idioma_Dia1','Tuesday 18');
$tmpl->setVariable('Idioma_Dia2','Wednesday 19');
$tmpl->setVariable('Idioma_Dia3','Thursday 20');
$tmpl->setVariable('Idioma_Dia4','Friday 21');
$tmpl->setVariable('Idioma_Dia5','Monday 24');
$tmpl->setVariable('Idioma_Dia6','Tuesday 25');
$tmpl->setVariable('Idioma_Dia','Day');
$tmpl->setVariable('Idioma_Dia_fecha','Date');
$tmpl->setVariable('idioma_fecha','Date:'); //--Tarjeta de reuniones Recibidas/Enviadas
$tmpl->setVariable('Idioma_Dia_horario','Schedule');
$tmpl->setVariable('Idioma_Dia_horariozona','Timezone');
$tmpl->setVariable('Idioma_Dia_horarioreu','Accept current schedule or change it');

/// Reuniones
/// Reuniones

$tmpl->setVariable('reuniones_todas','ALL MEETINGS');
$tmpl->setVariable('reuniones_enviadasyrecibidas','PENDING');
$tmpl->setVariable('reuniones_matchautomatico','Automatic Matching');
$tmpl->setVariable('reuniones_confirmadas','CONFIRMED');
$tmpl->setVariable('reuniones_enviadas','MEETINGS SENT');
$tmpl->setVariable('reuniones_recibidas','MEETINGS RECEIVED');
$tmpl->setVariable('reuniones_canceladas','CANCELED');
$tmpl->setVariable('Idioma_Conferencia','Initiating videoconference with');
$tmpl->setVariable('Idioma_ProductosComun','Products in common');
$tmpl->setVariable('Idioma_HorarioNoDisponible','Unavailable schedule');
$tmpl->setVariable('Idioma_HorarioOcupado','Blocked schedule with meetings');
$tmpl->setVariable('Idioma_HorarioDisponible','Suggested meeting time');

// NUEVAS

$tmpl->setVariable('Idioma_Mensajeria',' Messages'); //--Menù ADMIN
$tmpl->setVariable('Idioma_ExpositoresApp',' Exhibitors app'); //--Menù ADMIN
$tmpl->setVariable('por_pais',' By country'); //--Filtro Asistentes
$tmpl->setVariable('Chat_inicio',' Hi! I`d like to connect with you'); //--Chat_inicio
$tmpl->setVariable('agregar_agenda',' Do you want to add an event to the agenda?'); //--Pop up Agenda
$tmpl->setVariable('pornombreperfil',' By profile name'); //--Filtros asistentes
$tmpl->setVariable('porempresa',' By company'); //--Filtros asistentes
$tmpl->setVariable('porsectores',' Select sectors'); //--Filtros asistentes
$tmpl->setVariable('Chat pop-up',' Start conversation with [NAME] from [COMPANY]'); //--Chat pop-up
$tmpl->setVariable('Solicitud de reuniones',' Unavailable schedule'); //--Solicitud de reuniones
$tmpl->setVariable('Solicitud de reuniones',' Schedule block with meetings'); //--Solicitud de reuniones
$tmpl->setVariable('Solicitud de reuniones',' Suggested meeting time'); //--Solicitud de reuniones
$tmpl->setVariable('Solicitud de reuniones',' DAY'); //--Solicitud de reuniones
$tmpl->setVariable('Tarjeta de reuniones Recibidas/Enviadas',' Unconfirmed meeting'); //--Tarjeta de reuniones Recibidas/Enviadas
$tmpl->setVariable('Tarjeta de reuniones Recibidas/Enviadas',' Date:'); //--Tarjeta de reuniones Recibidas/Enviadas
$tmpl->setVariable('Tarjeta de reuniones Recibidas/Enviadas',' DD/MM/AAAA at HH:MM'); //--Tarjeta de reuniones Recibidas/Enviadas
$tmpl->setVariable('chat_tarjeta',' To start a conversation go to Attendees'); //--CHAT
$tmpl->setVariable('chat_tarjetaasiste',' GO TO ATTENDEES'); //--CHAT
$tmpl->setVariable('somos',' Who we are'); //--Micrositios
$tmpl->setVariable('ofrecemos',' What we offer'); //--Micrositios
$tmpl->setVariable('dato_conta',' Contact information'); //--Micrositios
$tmpl->setVariable('idioma_direccion',' Address:'); //--Micrositios
$tmpl->setVariable('idioma_telefono',' Phone:'); //--Micrositios
$tmpl->setVariable('idioma_email',' Email:'); //--Micrositios
$tmpl->setVariable('idioma_download',' Download more information'); //--Micrositios
$tmpl->setVariable('idiomo_videos',' Videos'); //--Micrositios
$tmpl->setVariable('idioma_imagenes',' Images'); //--Micrositios
$tmpl->setVariable('vermas',' More Info'); //--Tarjeta de novedades
$tmpl->setVariable('reunionconfirmada',' Meeting confirmed for'); //--Tarjeta de reunión confirmada
$tmpl->setVariable('reunionconfirmada1',' at'); //--Tarjeta de reunión confirmada
$tmpl->setVariable('reunionconfirmada2',' Table N°'); //--Tarjeta de reunión confirmada
$tmpl->setVariable('reunionconfirmada3',' Date'); //--Tarjeta de reunión confirmada
$tmpl->setVariable('destacados',' Main products or services'); //--Mi perfil
$tmpl->setVariable('producto1',' Product or service 1:'); //--Mi perfil
$tmpl->setVariable('producto2',' Product or service 2:'); //--Mi perfil
$tmpl->setVariable('producto3',' Product or service 3:'); //--Mi perfil
$tmpl->setVariable('producto4',' Product or service 4:'); //--Mi perfil
$tmpl->setVariable('producto5',' Product or service 5:'); //--Mi perfil
$tmpl->setVariable('inicio_chat',' Hello, I`d like to star a conversation with you...'); //--Chat pop-up
$tmpl->setVariable('ampliada1',' Description'); //--Botón "mas info" en tarjeta de asistentes
$tmpl->setVariable('ampliada2',' My Interests'); //--Botón "mas info" en tarjeta de asistentes
$tmpl->setVariable('ampliada',' Products or services'); //--Botón "mas info" en tarjeta de asistentes
$tmpl->setVariable('streaming_preguntas',' Q&A'); //--Sala Streaming
$tmpl->setVariable('streaming_nuevas',' New'); //--Sala Streaming
$tmpl->setVariable('streaming_escriba',' Write a question or comment here'); //--Sala Streaming
$tmpl->setVariable('streaming_send',' Send'); //--Sala Streaming
$tmpl->setVariable('permmasivos_send','Send Selection'); //--Sala Streaming
$tmpl->setVariable('permmasivos_sendall','Send to All / Filtered'); //--Sala Streaming
$tmpl->setVariable('streaming_escriba',' Cancel'); //--Sala Streaming
$tmpl->setVariable('expositores1',' Preview'); //--miperfil_expositores
$tmpl->setVariable('expositores2',' Company information'); //--miperfil_expositores
$tmpl->setVariable('expositores3',' Name:'); //--miperfil_expositores
$tmpl->setVariable('expositores4',' Category:'); //--miperfil_expositores
$tmpl->setVariable('expositores5',' Contact information'); //--miperfil_expositores
$tmpl->setVariable('expositores6',' Address:'); //--miperfil_expositores
$tmpl->setVariable('expositores7',' Telephone:'); //--miperfil_expositores
$tmpl->setVariable('expositores8',' Email:'); //--miperfil_expositores
$tmpl->setVariable('expositores9',' Position:'); //--miperfil_expositores
$tmpl->setVariable('expositores10',' Website:'); //--miperfil_expositores
$tmpl->setVariable('expositores11',' Company logo:'); //--miperfil_expositores
$tmpl->setVariable('expositores12',' Select file'); //--miperfil_expositores
$tmpl->setVariable('expositores13',' No file selected'); //--miperfil_expositores
$tmpl->setVariable('expositores14',' About the company'); //--miperfil_expositores
$tmpl->setVariable('expositores15',' Products'); //--miperfil_expositores
$tmpl->setVariable('expositores16',' Eliminate'); //--miperfil_expositores
$tmpl->setVariable('expositores17',' Text cards'); //--miperfil_expositores
$tmpl->setVariable('expositores18',' Videos'); //--miperfil_expositores
$tmpl->setVariable('expositores19',' Images'); //--miperfil_expositores
$tmpl->setVariable('expositores20',' Position:'); //--miperfil_expositores
$tmpl->setVariable('expositores21',' Linked profile:'); //--miperfil_expositores
$tmpl->setVariable('expositores22',' Add'); //--miperfil_expositores
$tmpl->setVariable('expositores23',' Save'); //--miperfil_expositores
$tmpl->setVariable('expositores24',' Cancel'); //--miperfil_expositores

$tmpl->setVariable('call1','Enter to meeting');
$tmpl->setVariable('call2','In order to invite another assistant to the meeting, please enter their e-mail');
$tmpl->setVariable('call3','Guest email');
$tmpl->setVariable('call4','Guest name');
$tmpl->setVariable('call5','Guest surname');
$tmpl->setVariable('call6','Guest company');
$tmpl->setVariable('call7','INVITE');
$tmpl->setVariable('callinvitar','Invite participant');

$tmpl->setVariable('favoritos','Favourites');
$tmpl->setVariable('filtros','Filters');
$tmpl->setVariable('addtoagenda','Add to Agenda');
$tmpl->setVariable('lugar','Place');
$tmpl->setVariable('horainicio','Start time');
$tmpl->setVariable('horafin','End time');
$tmpl->setVariable('nuevo','New');
$tmpl->setVariable('maestroagenda','Personalized Mails');
$tmpl->setVariable('titulomail','Nombre de la campaña');
$tmpl->setVariable('preguntas','Questions');
$tmpl->setVariable('preguntaspermitir','Allow questions');
$tmpl->setVariable('preguntasnopermitir','Don’t allow questions');
$tmpl->setVariable('preguntasvisualizar','See questions');
$tmpl->setVariable('maestronoticias','News Master');
$tmpl->setVariable('tiposdeperfil','User Type');
$tmpl->setVariable('tiposdepermiso','Permission levels');
$tmpl->setVariable('clase','Class');
$tmpl->setVariable('clases','Classes');
$tmpl->setVariable('cupones','Cupons');
$tmpl->setVariable('conversaciones','Conversations');
$tmpl->setVariable('importar','Import');
$tmpl->setVariable('scanqr','QR Scanned');
$tmpl->setVariable('sponsors2','Sponsors');
$tmpl->setVariable('mensajes2',' Messages');
$tmpl->setVariable('comunidad2',' Community');
$tmpl->setVariable('novedades2',' News');
$tmpl->setVariable('seleccionaopcion',' Select an option...');
$tmpl->setVariable('usuarioincorrecto',' *User or password are incorrect');
$tmpl->setVariable('iniciohome',' Home');
$tmpl->setVariable('clasesdeperfil','Profile Class');
$tmpl->setVariable('controldeingresos','Registry Control');
$tmpl->setVariable('subir',' Upload');
$tmpl->setVariable('subirpost',' Upload Post');
$tmpl->setVariable('subirimagen',' Upload Image');
$tmpl->setVariable('subirarchivo',' Upload File');
$tmpl->setVariable('entradasstands',' Stands Entry');
$tmpl->setVariable('entradassalas',' Rooms Entry');
$tmpl->setVariable('publicada',' Published');
$tmpl->setVariable('actualizar',' Update');
$tmpl->setVariable('perfil1',' Profile');
$tmpl->setVariable('conectado',' Online');
$tmpl->setVariable('conectadosi',' Yes');
$tmpl->setVariable('conectadono',' No');
$tmpl->setVariable('permisoaccesos',' Access permission to availability');
$tmpl->setVariable('permisoreuniones',' Permission to request meetings');
$tmpl->setVariable('permisomensajes',' Permission to send messages');
$tmpl->setVariable('permisoliberar',' Permission to unlock profiles');
$tmpl->setVariable('permisomails',' Permission to confirm emails');
$tmpl->setVariable('enviargafete',' Send Invitation');
$tmpl->setVariable('revocaraccesos',' Revoke access permission to availability');
$tmpl->setVariable('revocarreuniones',' Revoke permission to request meetings');
$tmpl->setVariable('revocarmensajes',' Revoke permission to send messages');
$tmpl->setVariable('perfilesaceptadas',' Profiles without accepted meetings');

$tmpl->setVariable('compartir_datos','Share data');
$tmpl->setVariable('comparti_contacto','I have shared my contact cards with you. You can find it in Attendees');
$tmpl->setVariable('compartidos','Share Data');
$tmpl->setVariable('enviar','Send');
$tmpl->setVariable('premios','AWARDS');
$tmpl->setVariable('condiciones','TERMS');
$tmpl->setVariable('mispuntos','MY POINTS');

//---- MENSAJES

$tmpl->setVariable('chats_activos',' ACTIVE CHATS');
$tmpl->setVariable('ir_a_asistentes','GO TO ASSISTANTS');
$tmpl->setVariable('escribir_chat','Write your message');

$tmpl->setVariable('ondemand','OnDemand');
$tmpl->setVariable('pitch','Pitch');
$tmpl->setVariable('networkingroom','Networking Room');
$tmpl->setVariable('hashtagall','All');
$tmpl->setVariable('entrar','Enter');
$tmpl->setVariable('chequeo','Image and Sound Check');
$tmpl->setVariable('chequeo0','Start Test');
$tmpl->setVariable('chequeo5','Stop Test');
$tmpl->setVariable('chequeo1','Warning: If you are not using headphones, pressing "Start Test" could cause feedback.');
$tmpl->setVariable('chequeo2','Once you start the test, you must allow your browser to access your camera and microphone.');
$tmpl->setVariable('chequeo3','You will then see your reflection on screen and hear an echo when talking. This indicates that your hardware has been successfully configured.');
$tmpl->setVariable('chequeo4','If you cannot see yourself on screen or do not hear an echo, check your computers hardware configuration. If none of this works, contact the event organizer.');

// QR
$tmpl->setVariable('accesosdirectos','Home Main buttons');
$tmpl->setVariable('accesosdirectos1','Show');
$tmpl->setVariable('leerqr','QR Code Reader');
$tmpl->setVariable('mostrarqr','Show QR');
$tmpl->setVariable('miqr','My QR');
$tmpl->setVariable('escanea','Scan and contact me');
$tmpl->setVariable('miqractividad','QR');
$tmpl->setVariable('escaneaactividad','Scan and see the options');
$tmpl->setVariable('Idioma_QrSala','Go to conference room');
$tmpl->setVariable('Idioma_QrPrograma','Go to schedule');
$tmpl->setVariable('Idioma_QrEncuesta','Fill the survey');
$tmpl->setVariable('Idioma_QrDatos','Exchange profile info');
$tmpl->setVariable('Idioma_QrReunion','Request meeting');
$tmpl->setVariable('Idioma_QrChatear','Chat');
$tmpl->setVariable('Idioma_QrBrochure','Download brochure');
$tmpl->setVariable('Idioma_QrOfertas','See offers');
$tmpl->setVariable('Idioma_QrReunion2','Request meeting');
$tmpl->setVariable('Idioma_QrChatear2','Chat');
$tmpl->setVariable('Idioma_QrIngresar','Enter');
$tmpl->setVariable('Idioma_QrCancelar','Cancel');

$tmpl->setVariable('categoria1','GOLD CATEGORY');
$tmpl->setVariable('categoria2','SILVER CATEGORY');
$tmpl->setVariable('categoria3','BRONZE CATEGORY');
$tmpl->setVariable('bienvenidachat','Start a conversation in Networking section');

$tmpl->setVariable('compartiloredes','Invite your friends, share in social media:');
$tmpl->setVariable('descripciondisponibilidad','Select the times');
$tmpl->setVariable('descripciondisponibilidad1','you want to block');
$tmpl->setVariable('descripciondisponibilidad2','from your personal schedule:');
$tmpl->setVariable('descripcionreuniones','Select the times you want to block in the agendas of the attendees:');
$tmpl->setVariable('descripcionproductos','To post a product or service on your attendee profile, upload an image that identifies you and then enter a link or URL (for example, the item on your website or a document in the cloud)');



//////// Coordinar reuniones///////////
$tmpl->setVariable('reunionesselectipo','Type of meeting');
$tmpl->setVariable('reunionesvirtual','Virtual');
$tmpl->setVariable('reunionespresencial','In the event');
$tmpl->setVariable('reunionesrojo','Counterpart not available');
$tmpl->setVariable('reunionesnegro','Not available');

///////Nuevas variables/////////////////////
$tmpl->setVariable('textocerrarpopup','Don’t show again');
$tmpl->setVariable('textonomostrarpopup','We won’t show you this message again');
$tmpl->setVariable('nohayactividades','There are no upcoming activities');
$tmpl->setVariable('textousuariologin','Email');
$tmpl->setVariable('textopasswordlogin','Password');
$tmpl->setVariable('verifiquemailregistro','Check your email');
$tmpl->setVariable('enviomailregistro','An email was sent to your account');
$tmpl->setVariable('backendpanel','Configuration panel');
$tmpl->setVariable('backendusuarios','Users');
$tmpl->setVariable('backendconfigevento','Event configuration');
$tmpl->setVariable('backendmesaayuda','Helpdesk');
$tmpl->setVariable('backendconfigmesaayuda','Helpdesk configuration');
$tmpl->setVariable('backendcalreuniones','Meeting calendar');
$tmpl->setVariable('backendtipperfil','Profile type');
$tmpl->setVariable('backendsectores','Sectors');
$tmpl->setVariable('backendzonahor','Time zone');
$tmpl->setVariable('backendconfmesas','Tables configuration');
$tmpl->setVariable('backendconfregistro','Sign up settings');
$tmpl->setVariable('backendexpo','Exhibitors');
$tmpl->setVariable('backendmensajeria','Messaging');
$tmpl->setVariable('backendcontenido','Content');
$tmpl->setVariable('backendprensa','Press');
$tmpl->setVariable('backendpitch','Pitch');
$tmpl->setVariable('backendondemand','On demand');
$tmpl->setVariable('backendadminreun','Meeting settings');
$tmpl->setVariable('backendcontreuniones','Meeting control');
$tmpl->setVariable('backendmetricas','Metrics');
$tmpl->setVariable('backendexportar','Export data');
$tmpl->setVariable('backendimportar','Import data');
$tmpl->setVariable('backendanaliticas','Analytics');
$tmpl->setVariable('backendadminimagenes','Image administration');
$tmpl->setVariable('backendbanners','Banners');
$tmpl->setVariable('backendpopup','Pop-ups');
$tmpl->setVariable('backendmapa','Map');
$tmpl->setVariable('backendagenda','Schedule');
$tmpl->setVariable('backendprograma','Program');
$tmpl->setVariable('backendoradores','Speakers');
$tmpl->setVariable('backendworkshops','Workshops');
$tmpl->setVariable('backendnetworking','Networking rooms');
$tmpl->setVariable('backendadminmapa','Map administration');
$tmpl->setVariable('backendencuestas','Surveys');
$tmpl->setVariable('backendencuestasgrales','General surveys');
$tmpl->setVariable('backendencuestasreu','Meeting surveys');
$tmpl->setVariable('backendgaming','Gaming');
$tmpl->setVariable('backendadmingaming','Gaming administration');
$tmpl->setVariable('backendconting','Login control');
$tmpl->setVariable('ayudausuario','User support');
$tmpl->setVariable('ayudausuarioperfil','Select the Helpdesk modal profile');
$tmpl->setVariable('ayudawhatsapp','Whatsapp');
$tmpl->setVariable('ayudacorreo','Mail');
$tmpl->setVariable('ayudafaq','FAQ');
$tmpl->setVariable('ayudatienefaq','Has FAQ?');
$tmpl->setVariable('backendcodigo','Id');
$tmpl->setVariable('zonahorariacodigopais','Country code');
$tmpl->setVariable('zonahorariapais','Country');
$tmpl->setVariable('zonahorariapaisingles','Country in english');
$tmpl->setVariable('zonahorariapaisregion','Country region');
$tmpl->setVariable('zonahorariatimereg','Regional time zone');
$tmpl->setVariable('backendacciones','Actions');
$tmpl->setVariable('mesasnombre','Table name');
$tmpl->setVariable('mesausuariocodigo','User id');
$tmpl->setVariable('mesausuario','Table user');
$tmpl->setVariable('mesausuarioselect','Select table user');
$tmpl->setVariable('backendcategoria','Category');
$tmpl->setVariable('backprensatitulo','Title');
$tmpl->setVariable('backprensabajada','Sub header');
$tmpl->setVariable('backprensadescripcion','Description');
$tmpl->setVariable('backprensafuente','Source');
$tmpl->setVariable('backprensaimagen','Image');
$tmpl->setVariable('backprensatipo','Type');
$tmpl->setVariable('backprensatamano','Size');
$tmpl->setVariable('backprensaperfil','Related profile');
$tmpl->setVariable('backprensnota','Note');
$tmpl->setVariable('backprensapublicidad','Advertise');
$tmpl->setVariable('backprensagrande','Big');
$tmpl->setVariable('backprensamediano','Medium');
$tmpl->setVariable('backprensachico','Small');
$tmpl->setVariable('backpitchintro','Intro');
$tmpl->setVariable('backpitchweb','Website');
$tmpl->setVariable('backpitchlogo','Logo');
$tmpl->setVariable('backpitchidealformato','Ideally in square format');
$tmpl->setVariable('backpitchidealrectangular','Ideally in rectangular format');
$tmpl->setVariable('backpitchbrochure','Brochure');
$tmpl->setVariable('backpitchvideo','Video link');
$tmpl->setVariable('backpitchcontacto','Contact profile');
$tmpl->setVariable('backondemandtitulo','Video title');
$tmpl->setVariable('backondemandpdf','PDF');
$tmpl->setVariable('backondemandhome','Visbility in homepage');
$tmpl->setVariable('backconfreusol','Applicant');
$tmpl->setVariable('backconfreucont','Counterpart');
$tmpl->setVariable('backconfreunoti','Send notification');
$tmpl->setVariable('backconfreumatchmodal','Meetings will be automatically generated between all the profiles that share interests. Do you wish to continue?');
$tmpl->setVariable('backconfreumatchmodalconf','Meetings generated');
$tmpl->setVariable('backconfreumatchmodalerr','We couldn`t generate the meetings');
$tmpl->setVariable('backconfreuhorario','Keep schedule and save');
$tmpl->setVariable('backconfreuhorarioedit','Edit schedule and save');
$tmpl->setVariable('backconfreuselectdate','Select a day and time for the meeting');
$tmpl->setVariable('backconfcontingeliminar','Delete All');
$tmpl->setVariable('backconfcontingcopy','Copied to Clipboard');
$tmpl->setVariable('backconfcontingvivo','Connected live ');
$tmpl->setVariable('backcadminbanners','Banners Administration');
$tmpl->setVariable('backnuevotipoyclase','New Type & Class');
$tmpl->setVariable('backtipo','Type');
$tmpl->setVariable('backclase','Class');
$tmpl->setVariable('backvisibilidad','Visibility');
$tmpl->setVariable('backvisibilidadtipos','Type & Class to see');
$tmpl->setVariable('backcreartipoyclase','Create/Edit Type & Class');
$tmpl->setVariable('backseleccionetipo','Select or Create a Type');
$tmpl->setVariable('backnuevotipo','New Type');
$tmpl->setVariable('backplaceholdertipo','Enter the name of the type of profile you want to create');
$tmpl->setVariable('backplaceholderclase','Enter name of the class you want to create');
$tmpl->setVariable('backpermisoschat','Chat Permission');
$tmpl->setVariable('backpermisosreunion','Meeting Permission');
$tmpl->setVariable('backpermisoliberado','Released on');
$tmpl->setVariable('backconfregistronombre','Name of the Event');
$tmpl->setVariable('backconfregistromail','Email for contact on Login');
$tmpl->setVariable('backconfregistroinicio','Start of Event');
$tmpl->setVariable('backconfregistrofin','End of Event');
$tmpl->setVariable('backconfregistroocultar','Hide Register Button on Login');
$tmpl->setVariable('backconfregistrocolor','Event Main Color');
$tmpl->setVariable('backconfregistroaccesolibre','Access to the event');
$tmpl->setVariable('backconfregistroaccesoliberado','Without authorization');
$tmpl->setVariable('backconfregistroaccesosinliberar','With authorization');
$tmpl->setVariable('backconfregistroconfirmar','Text for User Confirmation');
$tmpl->setVariable('backconfregistroliberar','Text for Released pending on Organizer Approval');
$tmpl->setVariable('backconfreunionesbloqueo','Block time slots');
$tmpl->setVariable('backconfreunionestipo','Type of meetings');
$tmpl->setVariable('backconfreunionestipoevento','Type of event');
$tmpl->setVariable('backconfreunionesduracion','Meeting duration in minutes');
$tmpl->setVariable('backconfreunionesnetworking','Networking');
$tmpl->setVariable('backconfreunioneshibrido','Hybrid');
$tmpl->setVariable('backconfreunionesdigital','Digital');
$tmpl->setVariable('backconfreunionespresencial','Face-to-face');
$tmpl->setVariable('backconfreunionesinicioevento','Start of the event');
$tmpl->setVariable('backconfreunionesduracionevento','Event duration in days');
$tmpl->setVariable('backcalendarioreuniones','Meeting schedule');
$tmpl->setVariable('backcalendariomesas','Tables Schedule');
$tmpl->setVariable('backcalendarioreunionessubtitulo','Select tables to view their agendas in calendar format');
$tmpl->setVariable('backcalendarioreunionessubtitulo','Select users to view their meeting agendas in calendar format');
$tmpl->setVariable('backcalendarioreunionesfiltrar','Show');
$tmpl->setVariable('Idioma_ConfirmarReunionPendiente','Do you want to confirm the meeting?');
$tmpl->setVariable('backconfreunionescompartir','Share Data');
$tmpl->setVariable('backconfreunionescompartirsi','Show');
$tmpl->setVariable('backconfreunionescompartirno','Hide');
$tmpl->setVariable('salasinstreaming','This streaming is not available yet.');

}

//-----------------------------------------------------------------------------------------------------


function DDStrIdioma($string){
    
        $traducciones = getIdiomasDB();
        
         return @$traducciones[$string];
    }
function TrMessage($string){
    $tr = Message();
        
    return $tr[$string];
}