<?php

/**
 * ---Diccionario de idiomas---
 * 
 * Function content $key and $value;
 * Funciones($string) return string 
 */

//-----------------------------------------------------------------------------------------------------
function getIdiomasDB() {
    return ['Reunion solicitada' => 'Reunião solicitada',
            'Reunion confirmada' => 'Reunião confirmada',
            'Reunion cancelada'  =>'Reunião cancelada',
            'Reunion confirmada con cambio de Horario ' => 'Reunião confirmada com mudança de horário'];
}
function Message(){
    return['Guardado correctamente!' => 'Salvo corretamente',
           'Perfil eliminado!' => 'Perfil excluído',
           'Error al eliminar el perfil!' => 'Erro na eliminação de perfil',
           'Perfil activado!' =>'Perfil ativado!',
           'Error al activar el perfil!'=>'Erro na ativação do seu perfil',
           'Perfil liberado!' => 'Perfil Liberado!',
           'Error al liberar el perfil!' => 'Erro ao liberar seu perfil',
           'Login iniciado!' => 'Login Iniciado',
           'Permiso actualizado!' => 'Permissão atualizada',
           'Error al actualizar el permiso!' => 'Erro ao atualização sua permissão',
           'Sector eliminado!' => 'Setor eliminado',
           'Error al eliminar el sector!' => 'Erro ao eliminar o setor',
           'Subsector eliminado!' => 'Subsetor eliminado',
           'Error al eliminar el subsector!' => 'Erro ao eliminar o subsetor!',
           'Categoria eliminado!' => 'Categoria eliminada!'
        ];
}

//-----------------------------------------------------------------------------------------------------

/**
 * @param $tmpl
 */
function DDIdioma($tmpl){
    $tmpl->setVariable('cambiar_contrasena','Alterar Perfil / Senha');
    $tmpl->setVariable('textopopinicio1','Para todas as atividades deste evento é importante que você escolha o horário do país onde irá vivê-lo.');
    $tmpl->setVariable('textopopinicio2','Para que outras pessoas saibam sobre você, preencha a descrição da sua empresa.');
    $tmpl->setVariable('textopopinicio3','Selecione sua disponibilidade de Horários, informe a outros sobre seus espaços disponíveis.');
    $tmpl->setVariable('guardadocorrectamente','Salvo corretamente!'); 

    $tmpl->setVariable('mesatipo','Tipo de Mesa'); 
    $tmpl->setVariable('mesatipo1','Tabela Fixa - atribuída ao usuário'); 
    $tmpl->setVariable('mesatipo2','Tabela flutuante');
    $tmpl->setVariable('paso','Paso'); 
    $tmpl->setVariable('finalizar','Finalizar'); 
    $tmpl->setVariable('cargando','Cargando');
    $tmpl->setVariable('duraciontexto','Duração'); 
    $tmpl->setVariable('minutostexto','minutos'); 
    $tmpl->setVariable('comentarioregistracion','Comentario de Registración'); 
    $tmpl->setVariable('tipodeparticipacion','Tipo de Reuniones'); 
    $tmpl->setVariable('tipodeparticipacion1','Solo Virtuales'); 
    $tmpl->setVariable('tipodeparticipacion2','Solo Presenciales'); 
    $tmpl->setVariable('tipodeparticipacion3','Presenciales y Virtuales'); 
    $tmpl->setVariable('admregistro','Admin Registro'); 
    $tmpl->setVariable('postulaciones','Postulaciones');
    $tmpl->setVariable('creadesafio','Crea tu desafío');
    $tmpl->setVariable('misdesafios','Mis Desafíos');
    $tmpl->setVariable('destacadocartel','Destacado');
    $tmpl->setVariable('postularmenombre','Postularme');
    $tmpl->setVariable('compartir','COMPARTIR');
    $tmpl->setVariable('compartechat','Compartilhe seus dados na janela CHAT e veja todos os cartões compartilhados em ASSISTENTES.');
    $tmpl->setVariable('charlas','Conversas');
    $tmpl->setVariable('vivo','VIVO');
    $tmpl->setVariable('agendarreunion','Agendar Reunión');
    $tmpl->setVariable('sobrenosotros','Sobre Nosotros');
    $tmpl->setVariable('standprevios','Stand Anterior');
    $tmpl->setVariable('standsiguiente','Stand Siguiente');
    $tmpl->setVariable('dimensiones','Dimensões sugeridas');
    $tmpl->setVariable('caracteres','caracteres');
    $tmpl->setVariable('noreuniones','Você não tem reuniões agendadas ou pendentes, acesse o diretório de participantes para solicitar uma reunião com uma contraparte.');
    $tmpl->setVariable('ingresarvivo','Entrar ao vivo');
$tmpl->setVariable('ofertademanda','Oferta / Demanda');
$tmpl->setVariable('pornombre','Pelo nome do perfil');
$tmpl->setVariable('porcompania','Pela empresa');
$tmpl->setVariable('seleccionesectores','Selecionar setores');
$tmpl->setVariable('seleccionesubsectores','Selecionar subsetores');
$tmpl->setVariable('Idioma_Subsector','Subsector');
$tmpl->setVariable('Idioma_Subsectores',' Subsetores');
$tmpl->setVariable('Idioma_Descripcion',' Descrição');
$tmpl->setVariable('Idioma_Empresa',' Empresa');
$tmpl->setVariable('Idioma_Seccion',' Sector');
$tmpl->setVariable('Idioma_MaestroSec',' Maestro de Sectores');
$tmpl->setVariable('Idioma_MaestroSubSec',' Maestro de Subsectores');
$tmpl->setVariable('Idioma_MaestroIdioma',' Maestro de Idioma');
$tmpl->setVariable('Idioma_MaestroSubCategorias','Maestro de Subcategorias');
$tmpl->setVariable('Idioma_Traducciones',' Traduções');
$tmpl->setVariable('Idioma_GuardarSwal',' Salvar');
$tmpl->setVariable('Idioma_Confirmar',' Confirma Salvar alterações?');
$tmpl->setVariable('Idioma_BotonConf',' Confirmar');
$tmpl->setVariable('Idioma_BotonCan',' Cancelar');
$tmpl->setVariable('Idioma_SelecIdiom',' Solecione um idioma');
$tmpl->setVariable('Idioma_Fltnom',' Falta o nome');
$tmpl->setVariable('Idioma_FltCorreo','Falta o E-mail');
$tmpl->setVariable('Idioma_FltApe',' Falta o Sobrenome');
$tmpl->setVariable('Idioma_FltComp',' Falta a Empresa');
$tmpl->setVariable('Idioma_FltPai','Falta seleccionar el pais ');
$tmpl->setVariable('Idioma_FltZh','Falta seleccionar zona horaria');
$tmpl->setVariable('Idioma_FltSelecComp',' Falta selecionar a disponibilidade');
$tmpl->setVariable('Idioma_Configuracion',' Configuração');
$tmpl->setVariable('Idioma_Perfiles',' Perfis');
$tmpl->setVariable('Idioma_Productos',' Produtos ou Serviços');
$tmpl->setVariable('Idioma_Sectores',' Setores');
$tmpl->setVariable('Idioma_Categorias',' Categorias');
$tmpl->setVariable('Idioma_SubC',' Subcategorias');
$tmpl->setVariable('Idioma_Exportar',' Exportar');
$tmpl->setVariable('Idioma_Noticias',' Notícias');
$tmpl->setVariable('Idioma_Desafio','Desafio' );
$tmpl->setVariable('Idioma_Agenda',' Agenda B2B');
$tmpl->setVariable('Idioma_Mensajeria',' Mensagem');
$tmpl->setVariable('Idioma_ExpositoresApp',' App Expositores');
$tmpl->setVariable('Idioma_Directorio',' Diretório');
$tmpl->setVariable('Idioma_Buscar',' Pesquisar');
$tmpl->setVariable('Idioma_Sponsor','Micrositio' );
$tmpl->setVariable('Idioma_Buscartodos',' Tudo');
$tmpl->setVariable('Idioma_BuscartodosFiltro',' Eliminar filtros');
$tmpl->setVariable('Idioma_Buscartodas',' Tudo');
$tmpl->setVariable('Idioma_Recomendados',' Recomendado');
$tmpl->setVariable('Idioma_Reuniones','Reuniões');
$tmpl->setVariable('Idioma_ReunionesConf','Meetings Confirmed/Total' );
$tmpl->setVariable('reunion2','Minhas Reuniões');
$tmpl->setVariable('Idioma_ReunionesEnviadas',' Reuniões Enviadas');
$tmpl->setVariable('Idioma_ReunionesRecibidas',' Reuniões Recebidas');
$tmpl->setVariable('Idioma_ReunionesConfirmadas',' Reuniões Confirmadas');
$tmpl->setVariable('Idioma_ReunionesCanceladas',' Reuniões Canceladas');
$tmpl->setVariable('Idioma_Calendario',' Calendário');
$tmpl->setVariable('Idioma_Actividades',' Atividades');
$tmpl->setVariable('Idioma_AgendaActvidades',' Agenda de atividades');
$tmpl->setVariable('Idioma_CalendarioAc',' Calendário de Atividades');
$tmpl->setVariable('Idioma_Mesas',' Sala');
$tmpl->setVariable('Idioma_VerPerf',' Perfil');
$tmpl->setVariable('Idioma_Reunion',' Reunião');
$tmpl->setVariable('Idioma_Coordinar',' Aceitar Reunião');
$tmpl->setVariable('Idioma_Cancelar',' Fechar');
$tmpl->setVariable('Idioma_miPerfil',' O meu perfil');
$tmpl->setVariable('Idioma_Salir',' Sair');
$tmpl->setVariable('Idioma_fav',' Favoritos');
$tmpl->setVariable('Idioma_Disponibilidad',' Disponibilidade');
$tmpl->setVariable('Idioma_Filtros',' Filtros');
$tmpl->setVariable('Idioma_PLbtodos','Todos');
$tmpl->setVariable('Idioma_PLbverSolofav',' Ver apenas favoritos');
$tmpl->setVariable('Idioma_PLbactivos',' Ativos');
$tmpl->setVariable('Idioma_PLbeliminados',' Eliminados');
$tmpl->setVariable('Idioma_PLbnoliberados',' Não LIberados');
$tmpl->setVariable('Idioma_PLbcorreonoconf',' E-mail não confirmado ');
$tmpl->setVariable('Idioma_CBtipo',' Tipo');
$tmpl->setVariable('Idioma_MSTPerfiles',' Master Perfil');
$tmpl->setVariable('Idioma_MSTDatosPersonales',' Dados pessoais');
$tmpl->setVariable('Idioma_Nombre',' Nome');
$tmpl->setVariable('Idioma_Nombrecontrol','Controlador');
$tmpl->setVariable('Idioma_Apellido',' Sobrenome');
$tmpl->setVariable('Idioma_Compania',' Empresa');
$tmpl->setVariable('Idioma_Cargo',' Cargo');
$tmpl->setVariable('Idioma_Idioma',' Idioma');
$tmpl->setVariable('Idioma_Seleccione',' Selecione');
$tmpl->setVariable('Idioma_DescripcionEmpresa',' Descrição da empresa');
$tmpl->setVariable('Idioma_Correo',' E-mail');
$tmpl->setVariable('Idioma_Telefono',' Telefone');
$tmpl->setVariable('Idioma_SitioWeb',' Endereço de web');
$tmpl->setVariable('Idioma_Contacto',' Contato');
$tmpl->setVariable('Idioma_Domicilio',' Endereço');
$tmpl->setVariable('Idioma_Direccion',' Localização');
$tmpl->setVariable('Idioma_Ciudad',' Cidade');
$tmpl->setVariable('Idioma_Estado',' Estado');
$tmpl->setVariable('Idioma_CodPostal',' Código postal');
$tmpl->setVariable('Idioma_Pais',' País');
$tmpl->setVariable('Idioma_DatosDeAc',' Dados de acceso web');
$tmpl->setVariable('Idioma_Usuario',' Usuário ');
$tmpl->setVariable('Idioma_Contrasena',' Senha');
$tmpl->setVariable('Idioma_Guardar',' Guardar');
$tmpl->setVariable('Idioma_Clasificar',' Interesses');
$tmpl->setVariable('Idioma_ClasificarVentas','Oferta');
$tmpl->setVariable('Idioma_ClasificarCompras','Demanda');
$tmpl->setVariable('Idioma_ClasificarVentasDescri','Los campos resaltados coinciden con tu demanda');
$tmpl->setVariable('Idioma_ClasificarComprasDescri','Los campos resaltados coinciden con tu oferta');
$tmpl->setVariable('Idioma_ClasificarIntereses','Intereses');
$tmpl->setVariable('Idioma_ClasificarInteresesDescri','Los campos resaltados coinciden con tus intereses.');
$tmpl->setVariable('Idioma_Rubros',' Categoria ');
$tmpl->setVariable('Idioma_Cerrar',' Finalizar');
$tmpl->setVariable('Idioma_OrdenarPor',' Ordenar Por');
$tmpl->setVariable('Idioma_Eliminar',' ELIMINAR');
$tmpl->setVariable('Idioma_ConfEliminar',' Confirma eliminar o perfil?');
$tmpl->setVariable('Idioma_ConfEliminarSec',' Confirma eliminar o setor?');
$tmpl->setVariable('Idioma_ConfEliminarSubSec',' Confirma eliminar o subsetor?');
$tmpl->setVariable('Idioma_ConfEliminarCat',' Confirma eliminar a categoria?');
$tmpl->setVariable('Idioma_Activar',' Ativar');
$tmpl->setVariable('Idioma_ActivarPerf',' Confirma a reativação do perfil?');
$tmpl->setVariable('Idioma_LiberarAccs',' Liberar acesso');
$tmpl->setVariable('Idioma_ConfLibPerf',' Confirma liberação de perfil?');
$tmpl->setVariable('Idioma_Permisos',' Permissões');
$tmpl->setVariable('Idioma_ConfPerFil',' Você confirma para alterar a permissão do perfil?');
$tmpl->setVariable('Idioma_ConfPerFiles',' Você confirma para alterar a permissão para todos os perfis?');
$tmpl->setVariable('Idioma_IngfPerFil',' Entrada do Perfil');
$tmpl->setVariable('Idioma_ConfIngPerf',' Confirma entrar com o perfil selecionado?');
$tmpl->setVariable('Idioma_ZonaHoraria',' Fuso Horário');
$tmpl->setVariable('Idioma_Encuesta',' Polls');
$tmpl->setVariable('Idioma_MaestroEnc',' Maestro de Enquetes');
$tmpl->setVariable('Idioma_MaestroEncPreg',' Maestro de Perguntas');
$tmpl->setVariable('Idioma_ReuUrl',' Link de Reuniões');
$tmpl->setVariable('Idioma_Speakers',' Maestro de Oradores');
$tmpl->setVariable('Idioma_Speakers1',' Oradores');
$tmpl->setVariable('Idioma_Orden',' Ordem');
$tmpl->setVariable('Idioma_Imagen',' Imagem');
$tmpl->setVariable('Idioma_Parametros',' Parâmetros');
$tmpl->setVariable('Idioma_MaestroParametros',' Parameter Master');
$tmpl->setVariable('Idioma_Observacion','Observacion');
$tmpl->setVariable('Aceptar_Reprogramar','Aceitar/Editar');
$tmpl->setVariable('Ingresar_Reunion','Ingresar a Reuniões');
$tmpl->setVariable('Reunion_Finalizada','Fechada');


 

//--LOGIN
$tmpl->setVariable('sincuenta',' Create account');
$tmpl->setVariable('sincontrasena',' Forgot your password');
$tmpl->setVariable('inscribite',' Sign up');
$tmpl->setVariable('recuperar',' Recover');
$tmpl->setVariable('iniciar',' Sign in');

//-- MENU
$tmpl->setVariable('menu','Menu');
$tmpl->setVariable('cronograma','PROGRAMA');
$tmpl->setVariable('cronograma2','Programa');
$tmpl->setVariable('speakers','Speakers');
$tmpl->setVariable('asistentes','Participantes');
$tmpl->setVariable('asistentesbanner','PARTICIPANTES');
$tmpl->setVariable('asistentes2','Participantes');
$tmpl->setVariable('buscar','Pesquisar');
$tmpl->setVariable('reunion','Meetings');
$tmpl->setVariable('reunion2','As minhas reuniões');
$tmpl->setVariable('agenda','A minha agenda');
$tmpl->setVariable('mensajes','Messages');
$tmpl->setVariable('prensa','Noticias');
$tmpl->setVariable('ayuda','Help Desk');
$tmpl->setVariable('virtual',' Virtual Tour');
$tmpl->setVariable('accesosrapidos','QUICK ACCESS');
$tmpl->setVariable('hallcomercial','EXPOSITORES');
$tmpl->setVariable('mapanavbar','MAPA');
$tmpl->setVariable('expositores','Sponsors');
$tmpl->setVariable('cocreate','CO-CREATE');
$tmpl->setVariable('novedades','Notícias');
$tmpl->setVariable('ofertas','OFERTAS');
$tmpl->setVariable('comunidad','COMUNITY');
$tmpl->setVariable('comunidad2','LOUNGE');
$tmpl->setVariable('mesasredondas','WORKSHOPS');

//-- DESAFIOS
$tmpl->setVariable('infodesafios',' Información Desafios');
$tmpl->setVariable('areadesafios',' Area de Desafio');
$tmpl->setVariable('desafiodesafios',' Desafio');
$tmpl->setVariable('buscamosdesafios',' Que buscamos?');

//-- REGISTRO
$tmpl->setVariable('nombme',' Nome');
$tmpl->setVariable('apellido',' Sobrenome');
$tmpl->setVariable('institucio',' Instituição / Empresa');
$tmpl->setVariable('celular',' Celular');
$tmpl->setVariable('dni',' RG / Passaporte');
$tmpl->setVariable('profesion',' Profissão');
$tmpl->setVariable('domicilio',' Casa');
$tmpl->setVariable('Localidad',' Cidade');
$tmpl->setVariable('Provincia',' Província');
$tmpl->setVariable('pais',' Selecione o pais');
$tmpl->setVariable('cpostal',' Código postal');
$tmpl->setVariable('email',' Email (usuário)');
$tmpl->setVariable('confirmeemail','Confirmar');
$tmpl->setVariable('contrasena',' Senha');
$tmpl->setVariable('atras',' Voltar');
$tmpl->setVariable('siguiente',' Próximo');
$tmpl->setVariable('paginacionanterior',' ANTERIOR');
$tmpl->setVariable('paginacionsiguiente',' PRÓXIMO');
$tmpl->setVariable('registro',' Registro');
$tmpl->setVariable('registrate',' Cadastre-se');
$tmpl->setVariable('acepto1',' Aceito receber informações do organizador do evento.');
$tmpl->setVariable('acepto2',' Aceito Receber Informações das Empresas Participantes do Evento.');
$tmpl->setVariable('terminos',' Termos e Condições de Uso. (Obrigatório) Baixe os Termos e Condições de Uso aqui.');
$tmpl->setVariable('tipoperfil','Tipo de participate');

$tmpl->setVariable('dia1',' Segunda-feira');
$tmpl->setVariable('dia2',' terça-feira');
$tmpl->setVariable('dia3',' quarta-feira');
$tmpl->setVariable('dia4',' quinta-feira');
$tmpl->setVariable('dia5',' sexta-feira');

$tmpl->setVariable('dia',' Dia');
$tmpl->setVariable('hora',' Hora');
$tmpl->setVariable('titulo',' Titulo');
$tmpl->setVariable('conferencia',' Conferência');
$tmpl->setVariable('ver-mas',' Ver Mais');
$tmpl->setVariable('proximas-actividades',' Próximas atividades');
$tmpl->setVariable('agenda-actividades',' Agenda de Atividades');
$tmpl->setVariable('sala1',' Sala de Conferências ');
$tmpl->setVariable('idioma_proximas','Próximas Reuniões');
$tmpl->setVariable('contraparte','Contraparte');
$tmpl->setVariable('estado','Estado');
$tmpl->setVariable('acciones','Ações');
$tmpl->setVariable('idioma_pending','Reuniões Pendentes');

$tmpl->setVariable('iniciochat',' Escreva sua mensagem aqui');
$tmpl->setVariable('iniciochaterror',' Escreva sua mensagem');
$tmpl->setVariable('iniciotitulo',' Iniciar conversa com');
$tmpl->setVariable('iniciotitulo','de');
$tmpl->setVariable('iniciotituloreunion',' Solicitar reuniões a ');
$tmpl->setVariable('iniciotituloreunion',' Pedido de reunião de ');
    
//-- MICROSITIO
$tmpl->setVariable('somos','Quem nós somos');
$tmpl->setVariable('ofrecemos','O que nós oferecemos');
$tmpl->setVariable('reunion','Reunião');
$tmpl->setVariable('representantes','Representantes');
$tmpl->setVariable('chat','Chats');
$tmpl->setVariable('contacto','Informações');

/// Cronograma
$tmpl->setVariable('Idioma_Dia1','Segunda-feira24');
$tmpl->setVariable('Idioma_Dia2','Terça-feira 25');
$tmpl->setVariable('Idioma_Dia3','Quarta-feira 18');
$tmpl->setVariable('Idioma_Dia4','Quinta-feira 19');
$tmpl->setVariable('Idioma_Dia5','Sexta-feira 20');
$tmpl->setVariable('Idioma_Dia6','Sabado 21');
$tmpl->setVariable('Idioma_Dia','Dia');
$tmpl->setVariable('Idioma_Dia_fecha','Data');
$tmpl->setVariable('Idioma_Dia_horario','Horario');
$tmpl->setVariable('Idioma_Dia_horariozona','Zona horaria');
$tmpl->setVariable('Idioma_Dia_horarioreu','Aceite a programação ou proponha uma mudança');


/// Reuniones
$tmpl->setVariable('reuniones_todas','TODAS AS REUNIÕES');
$tmpl->setVariable('reuniones_enviadasyrecibidas','PENDENTES');
$tmpl->setVariable('reuniones_matchautomatico','Matching automático');
$tmpl->setVariable('reuniones_confirmadas','CONFIRMADAS');
$tmpl->setVariable('reuniones_enviadas','REUNIÕES ENVIADAS');
$tmpl->setVariable('reuniones_recibidas','REUNIÕES RECEBIDAS');
$tmpl->setVariable('reuniones_canceladas','CANCELADAS');
$tmpl->setVariable('Idioma_Conferencia','Iniciando Conferência con');
$tmpl->setVariable('Idioma_ProductosComun','Produtos em comum');
$tmpl->setVariable('Idioma_HorarioNoDisponible','Horário não disponível');
$tmpl->setVariable('Idioma_HorarioOcupado','Agenda lotada com reuniões');
$tmpl->setVariable('Idioma_HorarioDisponible','Possível hora de reunião');
$tmpl->setVariable('descripciondisponibilidad','Seleccione los horarios');
$tmpl->setVariable('descripciondisponibilidad1','que desea bloquear');
$tmpl->setVariable('descripciondisponibilidad2','de su agenda personal:');
$tmpl->setVariable('descripcionreuniones','Seleccione los horarios que desee bloquear en las agendas de los asistentes:');
$tmpl->setVariable('descripcionproductos','Para publicar un producto o servicio en su perfil de asistente, cargue una imagen que lo identifique y luego ingrese un link o una URL (por ejemplo, el ítem en su web o un documento en la nube)');


// NUEVAS

$tmpl->setVariable('Idioma_Mensajeria','Mensagens'); //--Menù ADMIN
$tmpl->setVariable('Idioma_ExpositoresApp','App de expositores'); //--Menù ADMIN
$tmpl->setVariable('por_pais','Por país'); //--Filtro Asistentes
$tmpl->setVariable('Chat_inicio','Oi! Eu gostaria de me conectar com você'); //--Chat_inicio
$tmpl->setVariable('agregar_agenda','Você quer adicionar um evento à agenda?'); //--Pop up Agenda
$tmpl->setVariable('pornombreperfil','Por nome de perfil'); //--Filtros asistentes
$tmpl->setVariable('porempresa','Por empresa'); //--Filtros asistentes
$tmpl->setVariable('porsectores','Selecione os setores'); //--Filtros asistentes
$tmpl->setVariable('Chat pop-up','Iniciar conversa com [NOME] da [EMPRESA]'); //--Chat pop-up
$tmpl->setVariable('Solicitud de reuniones','Horário indisponível'); //--Solicitud de reuniones
$tmpl->setVariable('Solicitud de reuniones','Bloco de agenda com reuniões'); //--Solicitud de reuniones
$tmpl->setVariable('Solicitud de reuniones','Horário de reunião sugerido'); //--Solicitud de reuniones
$tmpl->setVariable('Solicitud de reuniones','DIA'); //--Solicitud de reuniones
$tmpl->setVariable('Tarjeta de reuniones Recibidas/Enviadas','Reunião não confirmada'); //--Tarjeta de reuniones Recibidas/Enviadas
$tmpl->setVariable('Tarjeta de reuniones Recibidas/Enviadas','Encontro:'); //--Tarjeta de reuniones Recibidas/Enviadas
$tmpl->setVariable('Tarjeta de reuniones Recibidas/Enviadas','DD / MM / AAAA em HH: MM'); //--Tarjeta de reuniones Recibidas/Enviadas
$tmpl->setVariable('chat_tarjeta','Para iniciar uma conversa vá para Participantes'); //--CHAT
$tmpl->setVariable('chat_tarjetaasiste','ACESSE OS PARTICIPANTES'); //--CHAT
$tmpl->setVariable('somos','Quem nós somos'); //--Micrositios
$tmpl->setVariable('ofrecemos','O que nós oferecemos'); //--Micrositios
$tmpl->setVariable('dato_conta','Informações de Contato'); //--Micrositios
$tmpl->setVariable('idioma_direccion','Endereço:'); //--Micrositios
$tmpl->setVariable('idioma_telefono','Telefone:'); //--Micrositios
$tmpl->setVariable('idioma_email','O email:'); //--Micrositios
$tmpl->setVariable('idioma_download','Baixe mais informações'); //--Micrositios
$tmpl->setVariable('idiomo_videos','Vídeos'); //--Micrositios
$tmpl->setVariable('idioma_imagenes','Imagens'); //--Micrositios
$tmpl->setVariable('vermas','Mais informações'); //--Tarjeta de novedades
$tmpl->setVariable('reunionconfirmada','Reunião confirmada para'); //--Tarjeta de reunión confirmada
$tmpl->setVariable('reunionconfirmada1','no'); //--Tarjeta de reunión confirmada
$tmpl->setVariable('reunionconfirmada2','Tabela N °'); //--Tarjeta de reunión confirmada
$tmpl->setVariable('reunionconfirmada3','Encontro'); //--Tarjeta de reunión confirmada
$tmpl->setVariable('destacados','Principais produtos ou serviços'); //--Mi perfil
$tmpl->setVariable('producto1','Produto ou serviço 1:'); //--Mi perfil
$tmpl->setVariable('producto2','Produto ou serviço 2:'); //--Mi perfil
$tmpl->setVariable('producto3','Produto ou serviço 3:'); //--Mi perfil
$tmpl->setVariable('producto4','Produto ou serviço 4:'); //--Mi perfil
$tmpl->setVariable('producto5','Produto ou serviço 5:'); //--Mi perfil
$tmpl->setVariable('inicio_chat','Olá, gostaria de começar uma conversa com você ...'); //--Chat pop-up
$tmpl->setVariable('ampliada1','Descrição'); //--Botón "mas info" en tarjeta de asistentes
$tmpl->setVariable('ampliada2',' Meus Interesses'); //--Botón "mas info" en tarjeta de asistentes
$tmpl->setVariable('ampliada','Produtos ou serviços'); //--Botón "mas info" en tarjeta de asistentes
$tmpl->setVariable('streaming_preguntas','Q&A'); //--Sala Streaming
$tmpl->setVariable('streaming_nuevas','Novo'); //--Sala Streaming
$tmpl->setVariable('streaming_escriba','Escreva uma pergunta ou comentário aqui'); //--Sala Streaming
$tmpl->setVariable('streaming_send','Mandar'); //--Sala Streaming
$tmpl->setVariable('streaming_escriba','Cancelar'); //--Sala Streaming
$tmpl->setVariable('expositores1','Antevisão'); //--miperfil_expositores
$tmpl->setVariable('expositores2','Informações da Empresa'); //--miperfil_expositores
$tmpl->setVariable('expositores3','Nome:'); //--miperfil_expositores
$tmpl->setVariable('expositores4','Categoria:'); //--miperfil_expositores
$tmpl->setVariable('expositores5','Informações de Contato'); //--miperfil_expositores
$tmpl->setVariable('expositores6','Endereço:'); //--miperfil_expositores
$tmpl->setVariable('expositores7','Telefone:'); //--miperfil_expositores
$tmpl->setVariable('expositores8','O email:'); //--miperfil_expositores
$tmpl->setVariable('expositores9','Posição:'); //--miperfil_expositores
$tmpl->setVariable('expositores10','Local na rede Internet:'); //--miperfil_expositores
$tmpl->setVariable('expositores11','Logotipo da empresa:'); //--miperfil_expositores
$tmpl->setVariable('expositores12','Selecione o arquivo'); //--miperfil_expositores
$tmpl->setVariable('expositores13','Nenhum arquivo selecionado'); //--miperfil_expositores
$tmpl->setVariable('expositores14','Sobre a empresa'); //--miperfil_expositores
$tmpl->setVariable('expositores15','Produtos'); //--miperfil_expositores
$tmpl->setVariable('expositores16','Eliminar'); //--miperfil_expositores
$tmpl->setVariable('expositores17','Cartões de texto'); //--miperfil_expositores
$tmpl->setVariable('expositores18','Vídeos'); //--miperfil_expositores
$tmpl->setVariable('expositores19','Imagens'); //--miperfil_expositores
$tmpl->setVariable('expositores20','Posição:'); //--miperfil_expositores
$tmpl->setVariable('expositores21','Perfil vinculado:'); //--miperfil_expositores
$tmpl->setVariable('expositores22','Adicionar'); //--miperfil_expositores
$tmpl->setVariable('expositores23','Salve'); //--miperfil_expositores
$tmpl->setVariable('expositores24','Cancelar'); //--miperfil_expositores

$tmpl->setVariable('call1','Entrar na reunião');
$tmpl->setVariable('call2','Para convidar outro participante do evento para a reunião, preencha seu e-mail');
$tmpl->setVariable('call3','Email de convidado');
$tmpl->setVariable('call4','Nome do convidado');
$tmpl->setVariable('call5','Sobrenome do convidado');
$tmpl->setVariable('call6','Empresa convidada');
$tmpl->setVariable('call7','CONVIDAR');
$tmpl->setVariable('callinvitar','Invitar participante');

$tmpl->setVariable('favoritos','Favoritos');
$tmpl->setVariable('filtros','Filtros');
$tmpl->setVariable('addtoagenda','Adicionar à Agenda');
$tmpl->setVariable('lugar','Lugar');
$tmpl->setVariable('horainicio','Tempo de início');
$tmpl->setVariable('horafin','Fim do tempo');
$tmpl->setVariable('nuevo','Nuevo');
$tmpl->setVariable('maestroagenda','Mails personalizados');
$tmpl->setVariable('titulomail','Nombre de la campaña');
$tmpl->setVariable('preguntas','Questões');
$tmpl->setVariable('preguntaspermitir','Permita Questões');
$tmpl->setVariable('preguntasnopermitir','Não permita Questões');
$tmpl->setVariable('preguntasvisualizar','Ver perguntas');
$tmpl->setVariable('maestronoticias','Maestro de Noticias');
$tmpl->setVariable('tiposdeperfil','Tipos de Perfil');
$tmpl->setVariable('tiposdepermiso','Nivel de Permisos');
$tmpl->setVariable('clase','Clase');
$tmpl->setVariable('clases','Clases');
$tmpl->setVariable('cupones','Cupones');
$tmpl->setVariable('conversaciones','Conversas');
$tmpl->setVariable('importar','Importar');
$tmpl->setVariable('scanqr','QR Escaneados');
$tmpl->setVariable('sponsors2','Expositores');
$tmpl->setVariable('mensajes2',' Mensajes');
$tmpl->setVariable('comunidad2',' Comunidad');
$tmpl->setVariable('novedades2',' Novedades');
$tmpl->setVariable('seleccionaopcion',' Seleccione una opcion...');
$tmpl->setVariable('usuarioincorrecto',' *Usuário ou senha incorretos');
$tmpl->setVariable('iniciohome',' INÍCIO');
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
$tmpl->setVariable('conectado',' Conectado');
$tmpl->setVariable('conectadosi',' Si');
$tmpl->setVariable('conectadono',' No');
$tmpl->setVariable('permisoaccesos',' Permissão de acesso para disponibilidade');
$tmpl->setVariable('permisoreuniones',' Permissão para solicitar reuniões');
$tmpl->setVariable('permisomensajes',' Permissão para enviar mensagens');
$tmpl->setVariable('permisoliberar',' Permissão para liberar perfiles');
$tmpl->setVariable('permisomails',' Permissão para confimar correo');
$tmpl->setVariable('enviargafete',' Enviar Gafete');
$tmpl->setVariable('revocaraccesos',' Revogar permissão de acesso à disponibilidade');
$tmpl->setVariable('revocarreuniones',' Revogar permissão para solicitar reuniões');
$tmpl->setVariable('revocarmensajes',' Revogar permissão para enviar mensagens');
$tmpl->setVariable('perfilesaceptadas',' Perfis sem reuniões aceitas');

$tmpl->setVariable('compartir_datos','Compartir datos');
$tmpl->setVariable('comparti_contacto','Hola! Te he compartido mi contacto. En ASISTENTES puedes ver la lista de contactos compartidos y enviarla a tu mail .');
$tmpl->setVariable('compartidos','Datos Compartidos');
$tmpl->setVariable('enviar',' Enviar');
$tmpl->setVariable('premios','PREMIOS');
$tmpl->setVariable('condiciones','CONDICIONES');
$tmpl->setVariable('mispuntos','MIS PUNTOS');

//---- MENSAJES

$tmpl->setVariable('chats_activos',' CHATS ACTIVOS');
$tmpl->setVariable('ir_a_asistentes',' IR A PARTICIPANTES');
$tmpl->setVariable('escribir_chat','Escribe tu mensaje');

$tmpl->setVariable('ondemand','OnDemand');
$tmpl->setVariable('pitch','Pitch');
$tmpl->setVariable('networkingroom','Networking Room');
$tmpl->setVariable('hashtagall','Todos');
$tmpl->setVariable('entrar','Entrar');
$tmpl->setVariable('chequeo','Verificação de imagem e som');
$tmpl->setVariable('chequeo0','Iniciar Teste');
$tmpl->setVariable('chequeo5','Parar o teste');
$tmpl->setVariable('chequeo1','Aviso: Se você não estiver usando fones de ouvido, pressionar "Iniciar teste" pode causar feedback.');
$tmpl->setVariable('chequeo2','Depois de iniciar o teste, você deve permitir que seu navegador acesse sua câmera e microfone.');
$tmpl->setVariable('chequeo3','Você verá seu reflexo na tela e ouvirá um eco ao falar. Isso indica que seu hardware foi configurado com sucesso.');
$tmpl->setVariable('chequeo4','Se você não consegue se ver na tela ou não ouve um eco, verifique a configuração de hardware do seu computador. Se nada disso funcionar, entre em contato com o organizador do evento.');

// QR
$tmpl->setVariable('accesosdirectos','Botones Principales Home');
$tmpl->setVariable('accesosdirectos1','Mostrar');
$tmpl->setVariable('leerqr','Leer QR');
$tmpl->setVariable('mostrarqr','Mostrar QR');
$tmpl->setVariable('miqr','Meu QR');
$tmpl->setVariable('escanea','Digitalize e entre em contato comigo');
$tmpl->setVariable('miqractividad','QR');
$tmpl->setVariable('escaneaactividad','Escaneie e veja as opções');
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

$tmpl->setVariable('categoria1','GOLD CATEGORY');
$tmpl->setVariable('categoria2','SILVER CATEGORY');
$tmpl->setVariable('categoria3','BRONZE CATEGORY');
$tmpl->setVariable('bienvenidachat','Para iniciar una conversación dirijase a Networking');

$tmpl->setVariable('compartiloredes','Compartilo en redes e invita a tus amigos');


//////// Coordinar reuniones///////////
$tmpl->setVariable('reunionesselectipo','Tipo de reunión');
$tmpl->setVariable('reunionesvirtual','Virtual');
$tmpl->setVariable('reunionespresencial','Presencial');
$tmpl->setVariable('reunionesrojo','No disponible la contraparte.');
$tmpl->setVariable('reunionesnegro','No disponible');
///////Nuevas variables/////////////////////
$tmpl->setVariable('textocerrarpopup','Cerrar y no volver a mostrar');
$tmpl->setVariable('textonomostrarpopup','No se mostrara mas!');
$tmpl->setVariable('nohayactividades','Não há atividades neste dia');
$tmpl->setVariable('textousuariologin','Correio eletrônico');
$tmpl->setVariable('textopasswordlogin','Contraseña');
$tmpl->setVariable('acepteterminos','Acepte los términos y condiciones');
$tmpl->setVariable('completeelcampo','Complete el campo ');
$tmpl->setVariable('verifiquemailregistro','Revisa tu email');
$tmpl->setVariable('enviomailregistro','Se ha enviado un correo a su cuenta');
$tmpl->setVariable('backendpanel','Panel de configuración');
$tmpl->setVariable('backendusuarios','Usuarios');
$tmpl->setVariable('backendconfigevento','Configuración del evento');
$tmpl->setVariable('backendmesaayuda','Mesa de ayuda');
$tmpl->setVariable('backendconfigmesaayuda','Configuración de Mesa de ayuda');
$tmpl->setVariable('backendcalreuniones','Calendario de reuniones');
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
$tmpl->setVariable('backprensachico','Pequeno');
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
$tmpl->setVariable('backconfreumatchmodal','Se generarán automáticamente reuniones entre todos los perfiles que compartan intereses. Desea continuar?');
$tmpl->setVariable('backconfreumatchmodalconf','Reuniones Generadas');
$tmpl->setVariable('backconfreumatchmodalerr','No se pudieron generar reuniones');
$tmpl->setVariable('backconfreuhorario','Mantener Horario y Guardar');
$tmpl->setVariable('backconfreuhorarioedit','Editar Horario y Guardar');
$tmpl->setVariable('backconfreuselectdate','Seleccione dia y hora de la reunión');
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
$tmpl->setVariable('backconfregistroocultar','Ocultar Registro');
$tmpl->setVariable('backconfregistrocolor','Color Principal del evento');
$tmpl->setVariable('backconfregistroaccesolibre','Ingreso al evento');
$tmpl->setVariable('backconfregistroaccesoliberado','Sin autorización del Organizador');
$tmpl->setVariable('backconfregistroaccesosinliberar','Con autorización del Organizador');
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
$tmpl->setVariable('backcalendariomesas','Calendario de Mesas');
$tmpl->setVariable('backcalendarioreunionessubtitulo','Seleccione mesa para ver sus agendas en formato de calendario');
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