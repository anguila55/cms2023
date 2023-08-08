<?php

/**
 * ---Diccionario de idiomas---
 * 
 * Function content $key and $value;
 * Funciones($string) return string 
 */

//-----------------------------------------------------------------------------------------------------
function getIdiomasDB() {
  return ['Reunion solicitada' => 'Reunion solicitada ',
          'Reunion confirmada' => 'Reunion confirmada',
          'Reunion cancelada'  =>'Reunion cancelada',
          'Reunion confirmada con cambio de Horario' => 'Reunion confirmada con cambio de Horario'];
}
function Message(){
    return['Guardado correctamente!'=>'Guardado correctamente!',
           'Perfil eliminado!'=> 'Perfil eliminado!',
           'Error al eliminar el perfil!'=>'Error al eliminar el perfil!',
           'Perfil activado!' =>'Perfil activado!',
           'Error al activar el perfil!'=>'Error al activar el perfil!',
           'Perfil liberado!' => 'Perfil liberado!',
           'Error al liberar el perfil!' => 'Error al liberar el perfil!',
           'Login iniciado!' => 'Login iniciado!',
           'Permiso actualizado!' => 'Permiso actualizado!',
           'Error al actualizar el permiso!' => 'Error al actualizar el permiso!',
           'Sector eliminado!' => 'Sector eliminado!',
           'Error al eliminar el sector!' => 'Error al eliminar el sector!',
           'Subsector eliminado!' => 'Subsector eliminado!',
           'Error al eliminar el subsector!' => 'Error al eliminar el subsector!',
           'Categoria eliminado!' => 'Categoria eliminada!'
        ];
}

//-----------------------------------------------------------------------------------------------------

function DDIdioma($tmpl){

    $tmpl->setVariable('mesatipo','Tipo de Mesa'); 
    $tmpl->setVariable('textopopinicio1','Para todas las actividades de este evento es importante que elijas el horario del país donde vas a vivirlo.');
    $tmpl->setVariable('textopopinicio2','Para que otras personas sepan de ti, completa la descripción de tu empresa.');
    $tmpl->setVariable('textopopinicio3',' Selecciona tu disponibilidad de Horarios, cuéntale a los demás tus espacios disponibles.');
    $tmpl->setVariable('mesatipo','Tipo de Mesa'); 
    $tmpl->setVariable('mesatipo1','Mesa Fija - asignada a usuario'); 
    $tmpl->setVariable('mesatipo2','Mesa Flotante'); 
    $tmpl->setVariable('guardadocorrectamente','Guardado Correctamente!'); 
    $tmpl->setVariable('paso','Paso'); 
    $tmpl->setVariable('finalizar','Finalizar'); 
    $tmpl->setVariable('cargando','Cargando'); 
    $tmpl->setVariable('duraciontexto','Duración'); 
    $tmpl->setVariable('minutostexto','minutos'); 
    $tmpl->setVariable('comentarioregistracion','Comentario de Registración'); 
    $tmpl->setVariable('tipodeparticipacion','Tipo de Reuniones'); 
    $tmpl->setVariable('tipodeparticipacion1','Solo Virtuales'); 
    $tmpl->setVariable('tipodeparticipacion2','Solo Presenciales'); 
    $tmpl->setVariable('tipodeparticipacion3','Presenciales y Virtuales'); 
    $tmpl->setVariable('admregistro','Formulario de registro'); 
    $tmpl->setVariable('postulaciones','Postulaciones');
    $tmpl->setVariable('creadesafio','Crea tu desafío');
    $tmpl->setVariable('misdesafios','Mis Desafíos');
    $tmpl->setVariable('destacadocartel','Destacado');
    $tmpl->setVariable('postularmenombre','Postularme');

    $tmpl->setVariable('compartir','COMPARTIR');
    $tmpl->setVariable('compartechat','Comparte tus datos en la ventana de CHAT y ve todas las tarjetas compartidas en ASISTENTES.');
    $tmpl->setVariable('charlas','Charlas');
    $tmpl->setVariable('vivo','VIVO');
    $tmpl->setVariable('agendarreunion','Agendar Reunión');
    $tmpl->setVariable('sobrenosotros','Sobre Nosotros');
    $tmpl->setVariable('standprevios','Stand Anterior');
    $tmpl->setVariable('standsiguiente','Stand Siguiente');
    $tmpl->setVariable('dimensiones','Tamaño recomendado');
    $tmpl->setVariable('caracteres','caracteres');
    $tmpl->setVariable('noreuniones','No tienes reuniones agendadas o pendientes, por favor, dirígete al directorio de asistentes para solicitarle una reunión a una contraparte.');
    $tmpl->setVariable('acepteterminos','Aceptar los términos y condiciones para continuar');
    $tmpl->setVariable('completeelcampo','Complete el campo ');
    $tmpl->setVariable('ingresarvivo','Ingresar al vivo');
    $tmpl->setVariable('Idioma_Subsector','Subsector');
    $tmpl->setVariable('Idioma_Subsectores','Subsectores');
    $tmpl->setVariable('Idioma_Descripcion',' Descripción');
    $tmpl->setVariable('Idioma_Empresa',' Empresa');
    $tmpl->setVariable('Idioma_Seccion','Sector');
    $tmpl->setVariable('Idioma_MaestroSec','Maestro de Sectores');
    $tmpl->setVariable('Idioma_MaestroSubSec','Maestro de Subsectores');
    $tmpl->setVariable('Idioma_MaestroSubCategorias','Maestro de Subcategorias');
    $tmpl->setVariable('Idioma_MaestroIdioma','Maestro de Idioma');
    $tmpl->setVariable('Idioma_Traducciones','Traducción');
	$tmpl->setVariable('Idioma_GuardarSwal','GUARDAR');
	$tmpl->setVariable('Idioma_Confirmar','¿Confirma guardar cambios?');
	$tmpl->setVariable('Idioma_BotonConf','Confirmar');
	$tmpl->setVariable('Idioma_BotonCan','Cancelar');
	$tmpl->setVariable('Idioma_SelecIdiom','Seleccione Idioma');
	$tmpl->setVariable('Idioma_Fltnom','Falta el nombre');
    $tmpl->setVariable('Idioma_FltCorreo','Falta el correo');
	$tmpl->setVariable('Idioma_FltApe','Falta el apellido');
	$tmpl->setVariable('Idioma_FltComp','Falta la compañia');
    $tmpl->setVariable('Idioma_FltPai','Falta seleccionar el pais ');
    $tmpl->setVariable('Idioma_FltZh','Falta seleccionar zona horaria');
    $tmpl->setVariable('Idioma_FltSelecComp','Falta seleccionar la disponibilidad');
    $tmpl->setVariable('Idioma_Configuracion','Configuracion' );
    $tmpl->setVariable('Idioma_Perfiles','Perfiles' );
    $tmpl->setVariable('Idioma_Productos','Productos y Servicios' );
    $tmpl->setVariable('Idioma_Sectores','Sectores' );
    $tmpl->setVariable('Idioma_Categorias','Categorias' );
    $tmpl->setVariable('Idioma_SubC','Subcategorias' );
    $tmpl->setVariable('Idioma_Exportar','Exportar' );
    $tmpl->setVariable('Idioma_Noticias','Prensa' );
    $tmpl->setVariable('Idioma_Desafio','Desafio' );
    $tmpl->setVariable('Idioma_Agenda','Agenda' );
    $tmpl->setVariable('Idioma_Mensajeria','Mensajeria' );
    $tmpl->setVariable('Idioma_ExpositoresApp','Expositores Aplicacion' );
    $tmpl->setVariable('Idioma_Directorio', 'Directorio' );
    $tmpl->setVariable('Idioma_Buscar','Buscar' );
    $tmpl->setVariable('Idioma_Sponsor','Micrositio' );
    $tmpl->setVariable('Idioma_Buscartodos','Ver Todos' );
    $tmpl->setVariable('Idioma_BuscartodosFiltro',' Eliminar filtros');
    $tmpl->setVariable('Idioma_Buscartodas','Ver Todas' );
    $tmpl->setVariable('Idioma_Recomendados','Recomendados' );
    $tmpl->setVariable('Idioma_Reuniones','Reuniones' );
    $tmpl->setVariable('Idioma_ReunionesConf','Reuniones Confirmadas/Total' );
    $tmpl->setVariable('reunion2',' Mis Reuniones');
    $tmpl->setVariable('Idioma_ReunionesEnviadas','Enviadas' );
    $tmpl->setVariable('Idioma_ReunionesRecibidas','Recibidas' );
    $tmpl->setVariable('Idioma_ReunionesConfirmadas','Confirmadas' );
    $tmpl->setVariable('Idioma_ReunionesCanceladas','Canceladas' );
    $tmpl->setVariable('Idioma_Calendario', 'Calendario' );
    $tmpl->setVariable('Idioma_Actividades', 'Actividades' );
    $tmpl->setVariable('Idioma_AgendaActvidades','Agenda de Actividades' );
    $tmpl->setVariable('Idioma_CalendarioAc','Calendario de Actividades' );
    $tmpl->setVariable('Idioma_Mesas','Sala' );
    $tmpl->setVariable('Idioma_VerPerf','Ver Perfil' );
	$tmpl->setVariable('Idioma_Reunion','Reunión' );
    $tmpl->setVariable('Idioma_Coordinar','Aceptar' );
    $tmpl->setVariable('Idioma_Cancelar','Cancelar' );
    $tmpl->setVariable('Idioma_miPerfil','Mi perfil' );
    $tmpl->setVariable('Idioma_Salir','Salir' );
    $tmpl->setVariable('Idioma_fav','Favoritos' );
    $tmpl->setVariable('Idioma_Disponibilidad','Disponibilidad' );
    $tmpl->setVariable('Idioma_Filtros','Filtros' );
    $tmpl->setVariable('Idioma_PLbtodos','Todos' );
    $tmpl->setVariable('Idioma_PLbverSolofav','Ver solo favoritos' );
	$tmpl->setVariable('Idioma_PLbactivos','Activos' );
    $tmpl->setVariable('Idioma_PLbeliminados','Eliminados' );
	$tmpl->setVariable('Idioma_PLbnoliberados','No Liberados' );
	$tmpl->setVariable('Idioma_PLbcorreonoconf','Correo no confirmado' );
	$tmpl->setVariable('Idioma_CBtipo','Tipo' );
    $tmpl->setVariable('Idioma_MSTPerfiles','Maestro de Perfiles');
    $tmpl->setVariable('Idioma_MSTDatosPersonales','Datos personales');
    $tmpl->setVariable('Idioma_Nombre','Nombre');
    $tmpl->setVariable('Idioma_Nombrecontrol','Controlador');
    $tmpl->setVariable('Idioma_Apellido','Apellido');
    $tmpl->setVariable('Idioma_Compania','Compañia');
    $tmpl->setVariable('Idioma_Cargo','Cargo');
    $tmpl->setVariable('Idioma_Idioma','Idioma');
    $tmpl->setVariable('Idioma_Seleccione','Seleccione');
    $tmpl->setVariable('Idioma_DescripcionEmpresa','Descripcion de la compañia');
    $tmpl->setVariable('Idioma_Correo','Correo');
    $tmpl->setVariable('Idioma_Telefono','Teléfono');
    $tmpl->setVariable('Idioma_SitioWeb','Sitio web');
    $tmpl->setVariable('Idioma_Contacto','Contacto');
    $tmpl->setVariable('Idioma_Domicilio','Domicilio');
    $tmpl->setVariable('Idioma_Direccion','Dirección');
    $tmpl->setVariable('Idioma_Ciudad','Ciudad');
    $tmpl->setVariable('Idioma_Estado','Estado');
    $tmpl->setVariable('Idioma_CodPostal','Código postal');
    $tmpl->setVariable('Idioma_Pais','País');
    $tmpl->setVariable('Idioma_DatosDeAc','Datos de acceso web');
    $tmpl->setVariable('Idioma_Usuario','Usuario ');
    $tmpl->setVariable('Idioma_Contrasena','Contraseña ');
    $tmpl->setVariable('Idioma_Guardar','Guardar');
    $tmpl->setVariable('Idioma_Clasificar','Sectores');
    $tmpl->setVariable('Idioma_ClasificarVentas','Oferta');
    $tmpl->setVariable('Idioma_ClasificarCompras','Demanda');
    $tmpl->setVariable('Idioma_ClasificarVentasDescri','Los campos resaltados coinciden con tu demanda');
    $tmpl->setVariable('Idioma_ClasificarComprasDescri','Los campos resaltados coinciden con tu oferta');
    $tmpl->setVariable('Idioma_ClasificarIntereses','Intereses');
    $tmpl->setVariable('Idioma_ClasificarInteresesDescri','Los campos resaltados coinciden con tus intereses.');
    $tmpl->setVariable('Idioma_Rubros','Rubro');
	$tmpl->setVariable('Idioma_Cerrar','Cerrar');
    $tmpl->setVariable('Idioma_OrdenarPor','Ordenar Por');
    $tmpl->setVariable('Idioma_Eliminar','ELIMINAR');
    $tmpl->setVariable('Idioma_ConfEliminar','¿Confirma eliminar el perfil?');
    $tmpl->setVariable('Idioma_ConfEliminarSec','¿Esta seguro que desea eliminar?');
    $tmpl->setVariable('Idioma_ConfEliminarSubSec','¿Confirma eliminar el Subsector?');
    $tmpl->setVariable('Idioma_ConfEliminarCat','¿Confirma eliminar la Categoría?');
    $tmpl->setVariable('Idioma_Activar','ACTIVAR');
    $tmpl->setVariable('Idioma_ActivarPerf','¿Confirma reactivar el perfil?');
    $tmpl->setVariable('Idioma_LiberarAccs','LIBERAR ACCESO');
    $tmpl->setVariable('Idioma_ConfLibPerf','¿Confirma liberar el perfil?');
    $tmpl->setVariable('Idioma_Permisos','PERMISOS');
    $tmpl->setVariable('Idioma_ConfPerFil','¿Confirma cambiar el permiso del perfil?');
    $tmpl->setVariable('Idioma_ConfPerFiles','¿Confirma cambiar el permiso a todos los perfiles?');
    $tmpl->setVariable('Idioma_IngfPerFil','INGRESO PERFIL');
    $tmpl->setVariable('Idioma_ConfIngPerf','¿Confirma ingresar como el Perfil seleccionado?');
	$tmpl->setVariable('Idioma_ZonaHoraria','Zona Horaria');
	$tmpl->setVariable('Idioma_Encuesta','Encuestas');
	$tmpl->setVariable('Idioma_MaestroEnc','Maestro de Encuestas');
	$tmpl->setVariable('Idioma_MaestroEncPreg','Maestro de Preguntas');
	$tmpl->setVariable('Idioma_ReuUrl','Link de Reuniones');
    $tmpl->setVariable('Idioma_Speakers','Maestro de Oradores');
    $tmpl->setVariable('Idioma_Speakers1','Oradores');
    $tmpl->setVariable('Idioma_Orden','Orden');
    $tmpl->setVariable('Idioma_Imagen','Imagen');
	$tmpl->setVariable('Idioma_Parametros','Parametros');
    $tmpl->setVariable('Idioma_MaestroParametros','Maestro de Parametros');
    $tmpl->setVariable('Idioma_Observacion','Observacion');
    $tmpl->setVariable('Aceptar_Reprogramar','Aceptar/Editar');
    $tmpl->setVariable('Ingresar_Reunion','Ingresar a Reunión');
    $tmpl->setVariable('Reunion_Finalizada','Finalizada');
    //-- NUEVAS

//--LOGIN
$tmpl->setVariable('sincuenta',' ¿No tienes una cuenta?');
$tmpl->setVariable('sincontrasena','¿Has olvidado tu contraseña?');
$tmpl->setVariable('inscribite',' Inscríbete');
$tmpl->setVariable('recuperar',' Recuperar');
$tmpl->setVariable('iniciar',' Registrarse');

//-- MENU
$tmpl->setVariable('menu',' MENU');
$tmpl->setVariable('cronograma','PROGRAMA');
$tmpl->setVariable('cronograma2','Programa');
$tmpl->setVariable('speakers','ORADORES');
$tmpl->setVariable('asistentes','Networking');
$tmpl->setVariable('asistentesbanner','PARTICIPANTES');
$tmpl->setVariable('asistentes2','Participantes');
$tmpl->setVariable('buscar',' BUSCAR');
$tmpl->setVariable('reunion',' Reuniones');
$tmpl->setVariable('reunion',' Mis Reuniones');
$tmpl->setVariable('agenda',' Mi Agenda');
$tmpl->setVariable('mensajes',' MENSAJES');
$tmpl->setVariable('prensa',' PRENSA');
$tmpl->setVariable('ayuda',' MESA DE AYUDA');
$tmpl->setVariable('virtual',' RECORRIDO VIRTUAL');
$tmpl->setVariable('accesosrapidos','ACCESOS RAPIDOS');
$tmpl->setVariable('hallcomercial','EXPOSITORES');
$tmpl->setVariable('mapanavbar','MAPA');
$tmpl->setVariable('expositores','EXPOSITORES');
$tmpl->setVariable('cocreate','CO-CREATE');

$tmpl->setVariable('novedades','INFO');
$tmpl->setVariable('ofertas','OFERTAS');
$tmpl->setVariable('comunidad','COMUNIDAD');
$tmpl->setVariable('comunidad2','LOUNGE');
$tmpl->setVariable('mesasredondas','WORKSHOPS');
$tmpl->setVariable('mesasredondas2','Workshops');


//-- DESAFIOS
$tmpl->setVariable('infodesafios',' Información Desafios');
$tmpl->setVariable('areadesafios',' Area de Desafio');
$tmpl->setVariable('desafiodesafios',' Desafio');
$tmpl->setVariable('buscamosdesafios',' Que buscamos?');

//-- REGISTRO
$tmpl->setVariable('nombme',' Nombre');
$tmpl->setVariable('apellido',' Apellido');
$tmpl->setVariable('institucio',' Institución/Empresa');
$tmpl->setVariable('celular',' Telefono Celular');
$tmpl->setVariable('dni',' DNI/Pasaporte');
$tmpl->setVariable('profesion',' Profesión');
$tmpl->setVariable('domicilio',' Domicilio');
$tmpl->setVariable('Localidad',' Localidad');
$tmpl->setVariable('Provincia',' Provincia');
$tmpl->setVariable('tipoperfil','Seleccionar tipo de perfil');
$tmpl->setVariable('seleccionaintereses','Seleccioná tus intereses en:');
$tmpl->setVariable('pais',' País de residencia');
$tmpl->setVariable('emailcoinciden','No coinciden los emails');
$tmpl->setVariable('cpostal',' Código Postal');
$tmpl->setVariable('email',' Email-Usuario');
$tmpl->setVariable('confirmeemail','Confirme');
$tmpl->setVariable('contrasena',' Contraseña');
$tmpl->setVariable('atras',' Atrás');
$tmpl->setVariable('siguiente',' Siguiente');
$tmpl->setVariable('paginacionanterior',' ANTERIOR');
$tmpl->setVariable('paginacionsiguiente',' SIGUIENTE');
$tmpl->setVariable('registro',' REGISTRO');
$tmpl->setVariable('registrate',' Registrarse');
$tmpl->setVariable('acepto1',' Acepto recibir información del organizador del evento sobre actividades de la institución.');
$tmpl->setVariable('acepto2',' Acepto recibir información de las empresas que participan en el evento.');
$tmpl->setVariable('terminos',' Terminos y condiciones de uso. (obligatorio) Descargá los términos y condiciones de uso aquí.');

$tmpl->setVariable('dia1',' Lunes');
$tmpl->setVariable('dia2',' Martes');
$tmpl->setVariable('dia3',' Miercoles');
$tmpl->setVariable('dia4',' Jueves');
$tmpl->setVariable('dia5',' Viernes');

$tmpl->setVariable('dia',' Dia');
$tmpl->setVariable('hora',' Hora');
$tmpl->setVariable('titulo',' Titulo');
$tmpl->setVariable('conferencia',' Conferencia');
$tmpl->setVariable('ver-mas',' Ver mas');
$tmpl->setVariable('proximas-actividades',' Próximas Actividades');
$tmpl->setVariable('agenda-actividades',' Agenda de Actividades');
$tmpl->setVariable('proximas-reuniones',' Próximas Reuniones');
$tmpl->setVariable('sala1',' Sala de Conferencias');

$tmpl->setVariable('iniciochat',' Escriba su mensaje aquí');
$tmpl->setVariable('iniciochaterror',' Escriba su mensaje');
$tmpl->setVariable('iniciotitulo',' Iniciar una conversación con ');
$tmpl->setVariable('iniciofrom','de');
$tmpl->setVariable('iniciotituloreunion',' Solicitar una reunión a ');
$tmpl->setVariable('iniciotituloreunion2',' Solicitud de reunión de ');

//-- MICROSITIO
$tmpl->setVariable('somos',' Quiénes somos');
$tmpl->setVariable('ofrecemos',' Qué ofrecemos');
$tmpl->setVariable('reunion',' Reunión');
$tmpl->setVariable('representantes',' Representantes');
$tmpl->setVariable('chat',' Chat');

$tmpl->setVariable('contacto','Información');

// Cronograma
$tmpl->setVariable('Idioma_Dia1','Martes 18');
$tmpl->setVariable('Idioma_Dia2','Miercoles 19');
$tmpl->setVariable('Idioma_Dia3','Jueves 20');
$tmpl->setVariable('Idioma_Dia4','Viernes 21');
$tmpl->setVariable('Idioma_Dia5','Lunes 24');
$tmpl->setVariable('Idioma_Dia6','Martes 25');
$tmpl->setVariable('Idioma_Dia','Dia');
$tmpl->setVariable('Idioma_Dia_fecha','Fecha');
$tmpl->setVariable('Idioma_Dia_horario','Horario');
$tmpl->setVariable('Idioma_Dia_horariozona','Zona horaria');
$tmpl->setVariable('Idioma_Dia_horarioreu','Acepte el horario o proponga un cambio');

/// Reuniones
$tmpl->setVariable('reuniones_confirmadas','CONFIRMADAS');
$tmpl->setVariable('reuniones_todas','TODAS LAS REUNIONES');
$tmpl->setVariable('reuniones_enviadas','REUNIONES ENVIADAS');
$tmpl->setVariable('reuniones_enviadasyrecibidas','PENDIENTES');
$tmpl->setVariable('reuniones_matchautomatico','Matching automático');
$tmpl->setVariable('reuniones_recibidas','REUNIONES RECIBIDAS');
$tmpl->setVariable('reuniones_canceladas','CANCELADAS');
$tmpl->setVariable('Idioma_Conferencia','Iniciando conferencia con');
$tmpl->setVariable('Idioma_ProductosComun','Productos en común');
$tmpl->setVariable('Idioma_HorarioNoDisponible','Horario no disponible');
$tmpl->setVariable('Idioma_HorarioOcupado','Horario ocupado con reuniones');
$tmpl->setVariable('Idioma_HorarioDisponible','Horario posible de reunión');


 // NUEVAS

$tmpl->setVariable('Idioma_Productos','Productos y Servicios' ); //--Menù ADMIN esta variable ya existe
$tmpl->setVariable('Idioma_Mensajeria','Mensajeria' ); //--Menù ADMIN esta variable ya existe
$tmpl->setVariable('Idioma_ExpositoresApp','Expositores Aplicacion' ); //--Menù ADMIN esta variable ya existe
$tmpl->setVariable('por_pais','Por país'); //--Filtro Asistentes
$tmpl->setVariable('Chat_inicio','¡Hola! me gustaria conectar contigo'); //--Chat_inicio
$tmpl->setVariable('agregar_agenda','Desea agregar su evento a la agenda?'); //--Pop up Agenda
$tmpl->setVariable('pornombreperfil','Por nombre de perfil'); //--Filtros asistentes
$tmpl->setVariable('porempresa','Por empresa'); //--Filtros asistentes
$tmpl->setVariable('porsectores','Seleccione sectores'); //--Filtros asistentes
$tmpl->setVariable('iniciarconversa','Iniciar conversación con [NOMBRE] de [EMPRESA]'); //--Chat pop-up
$tmpl->setVariable('horariopropio','Horario propio no disponible'); //--Solicitud de reuniones
$tmpl->setVariable('horarioocupado','Horario ocupado con reuniones'); //--Solicitud de reuniones
$tmpl->setVariable('horariosugerido','Horario sugerido para reunión'); //--Solicitud de reuniones
$tmpl->setVariable('dia','Día'); //--Solicitud de reuniones
$tmpl->setVariable('tr_sincon','Reunión sin confirmar'); //--Tarjeta de reuniones Recibidas/Enviadas
$tmpl->setVariable('idioma_fecha','Fecha:'); //--Tarjeta de reuniones Recibidas/Enviadas
$tmpl->setVariable('','DD/MM/AAAA a las HH:MM'); //--Tarjeta de reuniones Recibidas/Enviadas
$tmpl->setVariable('chat_tarjeta','Para iniciar una conversación a diríjase a asistentes'); //--CHAT
$tmpl->setVariable('chat_tarjetaasiste','IR A ASISTENTES'); //--CHAT
$tmpl->setVariable('somos','Quiénes somos'); //--Micrositios
$tmpl->setVariable('ofrecemos','Qué ofrecemos'); //--Micrositios
$tmpl->setVariable('dato_conta','Datos de contacto'); //--Micrositios
$tmpl->setVariable('idioma_direccion','Dirección:'); //--Micrositios
$tmpl->setVariable('idioma_telefono','Teléfono:'); //--Micrositios
$tmpl->setVariable('idioma_email','Email:'); //--Micrositios
$tmpl->setVariable('idioma_download','Descargar más información'); //--Micrositios
$tmpl->setVariable('idiomo_videos','Videos'); //--Micrositios
$tmpl->setVariable('idioma_imagenes','Imágenes'); //--Micrositios
$tmpl->setVariable('vermas','Ver más'); //--Tarjeta de novedades
$tmpl->setVariable('reunionconfirmada','Reunión confirmada para el'); //--Tarjeta de reunión confirmada
$tmpl->setVariable('reunionconfirmada1','a las'); //--Tarjeta de reunión confirmada
$tmpl->setVariable('reunionconfirmada2','Mesa N°'); //--Tarjeta de reunión confirmada
$tmpl->setVariable('reunionconfirmada3','Fecha'); //--Tarjeta de reunión confirmada
$tmpl->setVariable('destacados','Productos o servicios destacados'); //--Mi perfil
$tmpl->setVariable('producto1','Producto 1:'); //--Mi perfil
$tmpl->setVariable('producto2','Producto 2:'); //--Mi perfil
$tmpl->setVariable('producto3','Producto 3:'); //--Mi perfil
$tmpl->setVariable('producto4','Producto 4:'); //--Mi perfil
$tmpl->setVariable('producto5','Producto 5:'); //--Mi perfil
$tmpl->setVariable('inicio_chat','Hola, me gustaria comenzar una conversación contigo...'); //--Chat pop-up
$tmpl->setVariable('ampliada1','Descripción'); //--Botón "mas info" en tarjeta de asistentes
$tmpl->setVariable('ampliada2','Mis Intereses'); //--Botón "mas info" en tarjeta de asistentes
$tmpl->setVariable('ampliada','Productos'); //--Botón "mas info" en tarjeta de asistentes
$tmpl->setVariable('streaming_preguntas','PREGUNTAS'); //--Sala Streaming
$tmpl->setVariable('streaming_nuevas','Nueva'); //--Sala Streaming
$tmpl->setVariable('streaming_escriba','Escriba su pregunta aquí'); //--Sala Streaming
$tmpl->setVariable('streaming_send','Enviar'); //--Sala Streaming
$tmpl->setVariable('permmasivos_send','Enviar Selección'); //--Sala Streaming
$tmpl->setVariable('permmasivos_sendall','Enviar a Todos / Filtrados'); //--Sala Streaming
$tmpl->setVariable('streaming_escriba','Cancelar'); //--Sala Streaming
$tmpl->setVariable('expositores1','Vista previa'); //--miperfil_expositores
$tmpl->setVariable('expositores2','Datos de la empresa'); //--miperfil_expositores
$tmpl->setVariable('expositores3','Nombre:'); //--miperfil_expositores
$tmpl->setVariable('expositores4','Categoría:'); //--miperfil_expositores
$tmpl->setVariable('expositores5','Datos de contacto'); //--miperfil_expositores
$tmpl->setVariable('expositores6','DIRECCION:'); //--miperfil_expositores
$tmpl->setVariable('expositores7','TELEFONO:'); //--miperfil_expositores
$tmpl->setVariable('expositores8','MAIL:'); //--miperfil_expositores
$tmpl->setVariable('expositores9','CARGO:'); //--miperfil_expositores
$tmpl->setVariable('expositores10','WEB:'); //--miperfil_expositores
$tmpl->setVariable('expositores11','LOGO DE EMPRESA - NO MODIFICAR:'); //--miperfil_expositores
$tmpl->setVariable('expositores12','Seleccionar archivo'); //--miperfil_expositores
$tmpl->setVariable('expositores13','No se eligió archivo'); //--miperfil_expositores
$tmpl->setVariable('expositores14','Sobre la empresa'); //--miperfil_expositores
$tmpl->setVariable('expositores15','Productos'); //--miperfil_expositores
$tmpl->setVariable('expositores16','Eliminar'); //--miperfil_expositores
$tmpl->setVariable('expositores17','Cuadros de textos'); //--miperfil_expositores
$tmpl->setVariable('expositores18','Videos'); //--miperfil_expositores
$tmpl->setVariable('expositores19','Imagenes'); //--miperfil_expositores
$tmpl->setVariable('expositores20','Posicion:'); //--miperfil_expositores
$tmpl->setVariable('expositores21','Perfil Relacionado:'); //--miperfil_expositores
$tmpl->setVariable('expositores22','Agregar'); //--miperfil_expositores
$tmpl->setVariable('expositores23','Guardar'); //--miperfil_expositores
$tmpl->setVariable('expositores24','Cancelar'); //--miperfil_expositores

$tmpl->setVariable('call1','Entrar en reunión');
$tmpl->setVariable('call2','Para invitar a la reunion a otro asistente del evento complete su correo electrónico');
$tmpl->setVariable('call3','Correo invitado');
$tmpl->setVariable('call4','Nombre invitado');
$tmpl->setVariable('call5','Apellido invitado');
$tmpl->setVariable('call6','Empresa invitado');
$tmpl->setVariable('call7','INVITAR');
$tmpl->setVariable('callinvitar','Invitar participante');

$tmpl->setVariable('favoritos','Favoritos');
$tmpl->setVariable('filtros','FILTROS');
$tmpl->setVariable('addtoagenda','Agregar a Agenda');
$tmpl->setVariable('lugar','Lugar');
$tmpl->setVariable('horainicio','Hora de inicio de reuniones');
$tmpl->setVariable('horafin','Hora de última reunión');
$tmpl->setVariable('nuevo','Nuevo');
$tmpl->setVariable('maestroagenda','Mail Personalizado');
$tmpl->setVariable('titulomail','Nombre de la campaña');
$tmpl->setVariable('preguntas','Preguntas');
$tmpl->setVariable('preguntaspermitir','Permitir preguntas');
$tmpl->setVariable('preguntasnopermitir','No permitir preguntas');
$tmpl->setVariable('preguntasvisualizar','Visualizar preguntas');
$tmpl->setVariable('maestronoticias','Maestro de noticias');
$tmpl->setVariable('tiposdeperfil','Tipos de Perfil');
$tmpl->setVariable('tiposdepermiso','Nivel de Permisos');
$tmpl->setVariable('clase','Clase');
$tmpl->setVariable('clases','Clases');
$tmpl->setVariable('cupones','Cupones');
$tmpl->setVariable('conversaciones','Conversaciones');
$tmpl->setVariable('importar','Importar');
$tmpl->setVariable('scanqr','QR Escaneados');
$tmpl->setVariable('sponsors2','Expositores');
$tmpl->setVariable('mensajes2',' Mensajes');
$tmpl->setVariable('comunidad2',' Comunidad');
$tmpl->setVariable('novedades2',' Novedades');
$tmpl->setVariable('seleccionaopcion',' Seleccione una opcion...');
$tmpl->setVariable('usuarioincorrecto',' *Usuario o Contraseña incorrectos');
$tmpl->setVariable('iniciohome',' Inicio');
$tmpl->setVariable('clasesdeperfil','Clases de Perfil');
$tmpl->setVariable('controldeingresos','Control de Ingresos');
$tmpl->setVariable('subir',' Subir');
$tmpl->setVariable('subirpost',' Subir Post');
$tmpl->setVariable('subirimagen',' Subir Imagen');
$tmpl->setVariable('subirarchivo',' Subir Archivo');
$tmpl->setVariable('entradasstands',' Entradas a Stands');
$tmpl->setVariable('entradassalas',' Entradas a Salas');
$tmpl->setVariable('publicada',' Publicada');
$tmpl->setVariable('actualizar',' Actualizar');
$tmpl->setVariable('perfil1',' Perfil');
$tmpl->setVariable('conectado',' Ingresó');
$tmpl->setVariable('conectadosi',' Si');
$tmpl->setVariable('conectadono',' No');
$tmpl->setVariable('permisoaccesos',' Permiso de acceso a disponibilidad');
$tmpl->setVariable('permisoreuniones',' Permiso para solicitar reuniones');
$tmpl->setVariable('permisomensajes',' Permiso para enviar chats');
$tmpl->setVariable('permisoliberar',' Aprobar perfil de usuario');
$tmpl->setVariable('permisomails',' Confirmar correo de usuario');
$tmpl->setVariable('enviargafete',' Enviar Gafete');
$tmpl->setVariable('revocaraccesos',' Revocar permiso de Acceso a disponibilidad');
$tmpl->setVariable('revocarreuniones',' Revocar permiso para solicitar reuniones');
$tmpl->setVariable('revocarmensajes',' Revocar permiso para enviar chats');
$tmpl->setVariable('perfilesaceptadas',' Perfiles sin Reuniones Aceptadas');

$tmpl->setVariable('compartir_datos','Compartir datos');
$tmpl->setVariable('comparti_contacto','Hola! Te he compartido mi contacto. En ASISTENTES puedes ver la lista de contactos compartidos y enviarla a tu mail .');
$tmpl->setVariable('compartidos','Datos Compartidos');
$tmpl->setVariable('enviar',' Enviar');
$tmpl->setVariable('premios','PREMIOS');
$tmpl->setVariable('condiciones','CONDICIONES');
$tmpl->setVariable('mispuntos','MIS PUNTOS');

//---- MENSAJES

$tmpl->setVariable('chats_activos','CHATS ACTIVOS');
$tmpl->setVariable('ir_a_asistentes',' IR A ASISTENTES');
$tmpl->setVariable('escribir_chat','Escribe tu mensaje');

$tmpl->setVariable('ondemand','OnDemand');
$tmpl->setVariable('pitch','Pitch');
$tmpl->setVariable('networkingroom','Networking Room');
$tmpl->setVariable('hashtagall','Todos');
$tmpl->setVariable('entrar','Ingresar');
$tmpl->setVariable('chequeo','Chequeo imagen/sonido reunión');
$tmpl->setVariable('chequeo0','Empezar Prueba');
$tmpl->setVariable('chequeo5','Frenar Prueba');
$tmpl->setVariable('chequeo1','Precaución: Para evitar acoples en el sistema de audio, por favor utilice auriculares.');
$tmpl->setVariable('chequeo2','Al presionar el botón de "Empezar Prueba" deberá permitir el acceso del navegador a su cámara y micrófono.');
$tmpl->setVariable('chequeo3','Una vez que lo haya hecho verá su imagen en la pantalla. Al hablar deberá escuchar un eco, lo que indica que su cámara y micrófono han sido configurados exitosamente.');
$tmpl->setVariable('chequeo4','Si no puede verse en pantalla o no escucha el eco, revise la configuración de la cámara y micrófono en su ordenador. De otro modo, contacte con el organizador del evento.');

// QR
$tmpl->setVariable('accesosdirectos','Botones Principales Home');
$tmpl->setVariable('accesosdirectos1','Mostrar');
$tmpl->setVariable('leerqr','Leer QR');
$tmpl->setVariable('mostrarqr','Mostrar QR');
$tmpl->setVariable('miqr','Mi QR');
$tmpl->setVariable('escanea','Escaneá y contactáme');
$tmpl->setVariable('miqractividad','QR');
$tmpl->setVariable('escaneaactividad','Escaneá y ve las opciones');
$tmpl->setVariable('Idioma_QrSala','Ingresar a la sala');
$tmpl->setVariable('Idioma_QrPrograma','Ver Programa');
$tmpl->setVariable('Idioma_QrEncuesta','Completar Encuesta');
$tmpl->setVariable('Idioma_QrDatos','Intercambiar Datos');
$tmpl->setVariable('Idioma_QrReunion','Solicitar Reunion');
$tmpl->setVariable('Idioma_QrChatear','Chatear');
$tmpl->setVariable('Idioma_QrBrochure','Descargar Brochure');
$tmpl->setVariable('Idioma_QrOfertas','Ver Ofertas');
$tmpl->setVariable('Idioma_QrReunion2','Solicitar Reunion');
$tmpl->setVariable('Idioma_QrChatear2','Chatear');
$tmpl->setVariable('Idioma_QrIngresar','Ingresar');
$tmpl->setVariable('Idioma_QrCancelar','Cancelar');

$tmpl->setVariable('categoria1','CATEGORIA ORO');
$tmpl->setVariable('categoria2','CATEGORIA SILVER');
$tmpl->setVariable('categoria3','CATEGORIA BRONZE');
$tmpl->setVariable('bienvenidachat','Para iniciar una conversación dirijase a Networking');

$tmpl->setVariable('compartiloredes','Compartilo en redes e invita a tus amigos');
$tmpl->setVariable('descripciondisponibilidad','Seleccione los horarios');
$tmpl->setVariable('descripciondisponibilidad1','que desea bloquear');
$tmpl->setVariable('descripciondisponibilidad2','de su agenda personal:');
$tmpl->setVariable('descripcionreuniones','Seleccione los horarios que desee bloquear en las agendas de los asistentes:');
$tmpl->setVariable('descripcionproductos','Para publicar un producto o servicio en su perfil de asistente, cargue una imagen que lo identifique y luego ingrese un link o una URL (por ejemplo, el ítem en su web o un documento en la nube)');



//////// Coordinar reuniones///////////
$tmpl->setVariable('reunionesselectipo','Tipo de reunión');
$tmpl->setVariable('reunionesvirtual','Virtual');
$tmpl->setVariable('reunionespresencial','Presencial');
$tmpl->setVariable('reunionesrojo','No disponible la contraparte');
$tmpl->setVariable('reunionesnegro','No disponible');
///////Nuevas variables/////////////////////
$tmpl->setVariable('textocerrarpopup','Cerrar y no volver a mostrar');
$tmpl->setVariable('textonomostrarpopup','No se mostrara mas!');
$tmpl->setVariable('nohayactividades','No hay actividades a la fecha');
$tmpl->setVariable('textousuariologin','Correo electrónico');
$tmpl->setVariable('textopasswordlogin','Contraseña');
$tmpl->setVariable('verifiquemailregistro','Revisa tu email');
$tmpl->setVariable('enviomailregistro','Se ha enviado un correo a su cuenta');
$tmpl->setVariable('backendpanel','Panel de configuración');
$tmpl->setVariable('backendusuarios','Usuarios');
$tmpl->setVariable('backendconfigevento','Configuración del evento');
$tmpl->setVariable('backendmesaayuda','Mesa de ayuda');
$tmpl->setVariable('backendconfigmesaayuda','Configuración de Mesa de ayuda');
$tmpl->setVariable('backendcalreuniones','Configuración de reuniones');
$tmpl->setVariable('backendtipperfil','Tipo de perfil');
$tmpl->setVariable('backendsectores','Sectores');
$tmpl->setVariable('backendzonahor','Zona horaria');
$tmpl->setVariable('backendconfmesas','Configuración de mesas');
$tmpl->setVariable('backendconfregistro','Configuración de registro');
$tmpl->setVariable('backendexpo','Expositores');
$tmpl->setVariable('backendmensajeria','Mensajería');
$tmpl->setVariable('backendcontenido','Contenido');
$tmpl->setVariable('backendprensa','Prensa');
$tmpl->setVariable('backendpitch','Pitch');
$tmpl->setVariable('backendondemand','On demand');
$tmpl->setVariable('backendadminreun','Administración de Reuniones');
$tmpl->setVariable('backendcontreuniones','Control de Reuniones');
$tmpl->setVariable('backendmetricas','Métricas');
$tmpl->setVariable('backendexportar','Exportar datos');
$tmpl->setVariable('backendimportar','Importar datos');
$tmpl->setVariable('backendanaliticas','Analíticas');
$tmpl->setVariable('backendadminimagenes','Administración de imágenes');
$tmpl->setVariable('backendbanners','Banners');
$tmpl->setVariable('backendpopup','Pop-ups');
$tmpl->setVariable('backendmapa','Mapa');
$tmpl->setVariable('backendagenda','Agenda');
$tmpl->setVariable('backendprograma','Programa');
$tmpl->setVariable('backendoradores','Oradores');
$tmpl->setVariable('backendworkshops','Workshops');
$tmpl->setVariable('backendnetworking','Salas de networking');
$tmpl->setVariable('backendadminmapa','Administrar mapa');
$tmpl->setVariable('backendencuestas','Encuestas');
$tmpl->setVariable('backendencuestasgrales','Encuestas generales');
$tmpl->setVariable('backendencuestasreu','Encuestas de reuniones');
$tmpl->setVariable('backendgaming','Gaming');
$tmpl->setVariable('backendadmingaming','Administrador de gaming');
$tmpl->setVariable('backendconting','Control de ingresos');
$tmpl->setVariable('ayudausuario','Asistencia al usuario');
$tmpl->setVariable('ayudausuarioperfil','Seleccione Perfil de Modal Ayuda');
$tmpl->setVariable('ayudawhatsapp','Whatsapp');
$tmpl->setVariable('ayudacorreo','Correo');
$tmpl->setVariable('ayudafaq','Preguntas frecuentes');
$tmpl->setVariable('ayudatienefaq','¿Tiene preguntas frecuentes?');
$tmpl->setVariable('backendcodigo','Código');
$tmpl->setVariable('zonahorariacodigopais','Código de país');
$tmpl->setVariable('zonahorariapais','País');
$tmpl->setVariable('zonahorariapaisingles','País en inglés');
$tmpl->setVariable('zonahorariapaisregion','Región de país');
$tmpl->setVariable('zonahorariatimereg','Horario por región');
$tmpl->setVariable('backendacciones','Acciones');
$tmpl->setVariable('mesasnombre','Nombre de mesa');
$tmpl->setVariable('mesausuariocodigo','Código del usuario');
$tmpl->setVariable('mesausuario','Usuario de mesa');
$tmpl->setVariable('mesausuarioselect','Seleccione usuario de mesa');
$tmpl->setVariable('backendcategoria','Categoría');
$tmpl->setVariable('backprensatitulo','Título');
$tmpl->setVariable('backprensabajada','Bajada');
$tmpl->setVariable('backprensadescripcion','Descripción');
$tmpl->setVariable('backprensafuente','Fuente');
$tmpl->setVariable('backprensaimagen','Imagen');
$tmpl->setVariable('backprensatipo','Tipo');
$tmpl->setVariable('backprensatamano','Tamaño');
$tmpl->setVariable('backprensaperfil','Perfil relacionado');
$tmpl->setVariable('backprensnota','Nota');
$tmpl->setVariable('backprensapublicidad','Publicidad');
$tmpl->setVariable('backprensagrande','Grande');
$tmpl->setVariable('backprensamediano','Mediano');
$tmpl->setVariable('backprensachico','Pequeño');
$tmpl->setVariable('backpitchintro','Intro');
$tmpl->setVariable('backpitchweb','Sito web');
$tmpl->setVariable('backpitchlogo','Logo');
$tmpl->setVariable('backpitchidealformato','Ideal formato cuadrado');
$tmpl->setVariable('backpitchidealrectangular','Ideal formato rectangular');
$tmpl->setVariable('backpitchbrochure','Folleto');
$tmpl->setVariable('backpitchvideo','Link del video');
$tmpl->setVariable('backpitchcontacto','Perfil de contacto');
$tmpl->setVariable('backondemandtitulo','Título del video');
$tmpl->setVariable('backondemandpdf','PDF');
$tmpl->setVariable('backondemandhome','Visibilidad en el home');
$tmpl->setVariable('backconfreusol','Solicitante');
$tmpl->setVariable('backconfreucont','Contraparte');
$tmpl->setVariable('backconfreunoti','Enviar notificación');
$tmpl->setVariable('backconfreumatchmodal','Se generarán automáticamente reuniones entre todos los perfiles que compartan intereses. Desea continuar?');
$tmpl->setVariable('backconfreumatchmodalconf','Reuniones Generadas');
$tmpl->setVariable('backconfreumatchmodalerr','No se pudieron generar reuniones');
$tmpl->setVariable('backconfreuhorario','Mantener Horario y Guardar');
$tmpl->setVariable('backconfreuhorarioedit','Editar Horario y Guardar');
$tmpl->setVariable('backconfreuselectdate','Seleccione dia y hora de la reunión');
$tmpl->setVariable('backconfcontingeliminar','Eliminar Todas');
$tmpl->setVariable('backconfcontingcopy','Copiado al portapapeles');
$tmpl->setVariable('backconfcontingvivo','Conectados en vivo');
$tmpl->setVariable('backcadminbanners','Administrar Banners');
$tmpl->setVariable('backnuevotipoyclase','Nuevo Tipo y Clase');
$tmpl->setVariable('backtipo','Tipo');
$tmpl->setVariable('backclase','Clase');
$tmpl->setVariable('backvisibilidad','Visibilidad');
$tmpl->setVariable('backvisibilidadtipos','Tipo y Clases a ver');
$tmpl->setVariable('backcreartipoyclase','Crear/Editar Tipo y Clase');
$tmpl->setVariable('backseleccionetipo','Seleccione o cree un tipo');
$tmpl->setVariable('backnuevotipo','Nuevo Tipo');
$tmpl->setVariable('backplaceholdertipo','Ingrese nombre del tipo de perfil que desea crear');
$tmpl->setVariable('backplaceholderclase','Ingrese nombre de la clase que desea crear');
$tmpl->setVariable('backpermisoschat','Permisos Chat');
$tmpl->setVariable('backpermisosreunion','Permisos Reunion');
$tmpl->setVariable('backpermisoliberado','Liberado el');
$tmpl->setVariable('backconfregistronombre','Nombre del evento');
$tmpl->setVariable('backconfregistromail','Mail de contacto login');
$tmpl->setVariable('backconfregistroinicio','Inicio del Evento');
$tmpl->setVariable('backconfregistrofin','Fin del Evento');
$tmpl->setVariable('backconfregistrocolor','Color Principal del evento');
$tmpl->setVariable('backconfregistroaccesolibre','Ingreso al evento');
$tmpl->setVariable('backconfregistroaccesoliberado','Sin autorización del Organizador');
$tmpl->setVariable('backconfregistroaccesosinliberar','Con autorización del Organizador');
$tmpl->setVariable('backconfregistroocultar','Ocultar Registro');
$tmpl->setVariable('backconfregistroconfirmar','Texto de Confirmar Cuenta');
$tmpl->setVariable('backconfregistroliberar','Texto de pendiente de liberar por Organizador');
$tmpl->setVariable('backconfreunionesbloqueo','Bloquear franjas horarias');
$tmpl->setVariable('backconfreunionestipo','Tipo de reuniones');
$tmpl->setVariable('backconfreunionestipoevento','Tipo de evento');
$tmpl->setVariable('backconfreunionesduracion','Duración de las reuniones en minutos');
$tmpl->setVariable('backconfreunionesnetworking','Networking');
$tmpl->setVariable('backconfreunioneshibrido','Híbrido');
$tmpl->setVariable('backconfreunionesdigital','Digital');
$tmpl->setVariable('backconfreunionespresencial','Presencial');
$tmpl->setVariable('backconfreunionesinicioevento','Inicio del evento');
$tmpl->setVariable('backconfreunionesduracionevento','Duración del evento en dias');
$tmpl->setVariable('backcalendarioreuniones','Calendario de reuniones');
$tmpl->setVariable('backcalendariomesas','Calendario de mesas');
$tmpl->setVariable('backcalendariomesassubtitulo','Seleccione mesas para ver la agenda en formato de calendario');
$tmpl->setVariable('backcalendarioreunionessubtitulo','Seleccione usuarios para ver sus agendas de reuniones en formato de calendario');
$tmpl->setVariable('backcalendarioreunionesfiltrar','Mostrar');
$tmpl->setVariable('Idioma_ConfirmarReunionPendiente','¿Desea confirmar la reunión?');
$tmpl->setVariable('backconfreunionescompartir','Compartir Datos');
$tmpl->setVariable('backconfreunionescompartirsi','Visible');
$tmpl->setVariable('backconfreunionescompartirno','No visible');
$tmpl->setVariable('salasinstreaming','Esta sala aún no cuenta con una transmisión disponible');



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
