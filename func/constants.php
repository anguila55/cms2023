<?

$conn = sql_conectar(); //Apertura de Conexion

$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'SisCorreoLinkAppStore'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$evefch = trim($row['ZDESCRI']);	
}

$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'SisCorreoLinkPlayStore'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$evefchfin = trim($row['ZDESCRI']);
	
}

$query3 = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoEvento'";
$Table3 = sql_query($query3, $conn);
for ($i = 0; $i < $Table3->Rows_Count; $i++) {
	$row = $Table3->Rows[$i];
	$tipoevento = trim($row['ZVALUE']);
	if ($tipoevento == 'false') {
		$valorevento = 0;
	}else if ($tipoevento == 'fix') {
		$valorevento = 1; 
	}else{
		$valorevento = 2;
	}
}

$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'SisCorreoLinkExternalReuniones'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$nombreevento = trim($row['ZDESCRI']);	
}
$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'MenuNoticias'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$mailcontacto = trim($row['ZDESCRI']);	
}
$evefch = substr($evefch, 8, 2).'/'.substr($evefch,5,2).'/'.substr($evefch,0,4);
$evefchfin = substr($evefchfin, 8, 2).'/'.substr($evefchfin,5,2).'/'.substr($evefchfin,0,4);
sql_close($conn);
define("NAME_TITLE",$nombreevento);
define("LOGIN_PERIOD",$evefch.' - '.$evefchfin);
define("MAIL_NAME_APP",$nombreevento);
define("SEND_MAIL_LOGIN",$mailcontacto);
define("SEND_MAIL_USUARIO",'no-reply@btoolbox.com');
define("SEND_MAIL_REGISTRO",'https://live.eventtia.com/es/ilf-2023/Registro');
define("SEND_MAIL_RECUPERAR",'https://cmseventos.com/password/reset');
define("URL_WEB",'https://'.$_SERVER['HTTP_HOST'].'/');
define("MINUTOS_PREVIOS_REUNION",'10');	//Minutos previos a la reunion para enviar el mail de aviso
define("SUBJECT_RECUPERAR",'Nueva Contraseña Rondas Btbox');
define("SUBJECT_CONFIRMAR",'Confirma tu cuenta Rondas Btbox');
define("SEND_MAIL_REPORTES",'fernando.pereyra@onlife.com.ar');
define("SUBJECT_ACEPTOREUNION",'Reunión Aceptada');
define("SUBJECT_CANCELADA",'Reunión Cancelada');
define("SUBJETC_ENVIARREUNION",'Solicitud de Reunión');
define("SUBJETC_INVITARREUNION",'Invitación a Reunión');
define("SUBJETC_PERMISOREUNION",'Permiso a Solicitar Reuniones');
define("SUBJECT_CHAT",'Permiso a Solicitar Reuniones');
define("SUBJECT_LIBPERFIL",'Bienvenido a BtBox');
define("SHARED_CONTACT",'Datos Compartidos');
define("TIPO_DE_PERFIL_POR_DEFECTO",66);
define("TIPO_DE_CLASE_POR_DEFECTO",59);
define("PAIS_POR_DEFECTO",11);
define("PAIS2_POR_DEFECTO",11);
define("TIPO_POR_DEFECTO",$valorevento);
define("PAGINACION_CANTIDAD",18);


$tipostand = 0;  // 0 es demo, 1 es stand micrositio largo, 2 es stand estilo cms
$chatuser = 18204;

$pernombre_controlingresos = 'Soporte';
$perapelli_controlingresos = 'Soporte Btbox';
$percodigo_controlingresos = 18204 ;
$arraysuperadmin=[18175,18177];
$arraysuperadmin1="'18175','18177'";
#CMS API
define("CMS_USER", 'cms@api.com');
define("CMS_PASS", 'cms123456');
define("CMS_VALIDATE", 'validate/ilf-2023');


$jsonperfiles = array("Nombre"=>1, "Apellido"=>1, "Id"=>1, "Empresa" =>1, "Cargo"=>1, "Idioma" =>1, "Industria" =>0, "ZonaHoraria" =>1,"DescripcionEmpresa" =>1,"Correo"=>1, "Telefono"=>1, "Web"=>1, "Linkedin" =>1, "Facebook"=>1, "Twitter" =>1, "Instagram" =>1, "Direccion" =>1,"Ciudad" =>1,"Estado"=>1, "CodigoPostal"=>1, "Pais"=>1 );

json_encode($jsonperfiles);


