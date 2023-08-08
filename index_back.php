<?
if (!isset($_SESSION))  session_start();
// include($_SERVER["DOCUMENT_ROOT"] . '/cms/func/zglobals.php'); //DEV
include($_SERVER["DOCUMENT_ROOT"] . '/func/zglobals.php'); //PRD
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/constants.php';


$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('index.html');

//Diccionario de idiomas
//var_dump(DDIdioma($tmpl));

$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$perusuacc = (isset($_POST['perusuacc'])) ? trim($_POST['perusuacc']) : '';
$perpasacc = (isset($_POST['perpasacc'])) ? trim($_POST['perpasacc']) : '';

$conn = sql_conectar(); //Apertura de Conexion

$pathimagenes = '../perimg/';
$pathimg = 'avimg';
$errmsg 	= '';

if ($percodigo == '') {
	if ($perusuacc == '' || $perpasacc == '') {
		header('Location: login');
		exit;
	} else {

		$perpasacc = md5('BenVido' . $perpasacc . 'PassAcceso' . $perusuacc);
		$perpasacc = 'B#SD' . md5(substr($perpasacc, 1, 10) . 'BenVidO' . substr($perpasacc, 5, 8)) . 'E##$F';

		$query = " 	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCORREO,P.PERUSUACC,P.PERPASACC,P.ESTCODIGO,P.PERADMIN,P.PERAVATAR,
								P.PERUSADIS,P.PERUSAREU,P.PERUSAMSG,P.PERTIPO,P.PERCLASE,P.PERIDIOMA,P.PERCOMPAN,
								T.TIMREG,T.TIMDESCRI,T.TIMOFFSET
						FROM PER_MAEST P
						LEFT OUTER JOIN TIM_ZONE T ON T.TIMREG=P.TIMREG
						WHERE P.PERUSUACC='$perusuacc' AND P.PERPASACC='$perpasacc' ";

		$Table = sql_query($query, $conn);
		if ($Table->Rows_Count > 0) {
			$row = $Table->Rows[0];
			$percodigo = trim($row['PERCODIGO']);
			$estcodigo = trim($row['ESTCODIGO']);

			if ($estcodigo == 9) { //Correo sin verificar
				$_SESSION[GLBAPPPORT . 'PERCODIGO'] = '';
				$_SESSION[GLBAPPPORT . 'PERNOMBRE'] = '';
				$_SESSION[GLBAPPPORT . 'PERAPELLI'] = '';
				$_SESSION[GLBAPPPORT . 'PERCORREO'] = '';
				$_SESSION[GLBAPPPORT . 'PERCOMPAN'] = '';
				$_SESSION[GLBAPPPORT . 'PERUSUACC'] = '';
				$_SESSION[GLBAPPPORT . 'PERPASACC'] = '';
				$_SESSION[GLBAPPPORT . 'PERADMIN'] = '';
				$_SESSION[GLBAPPPORT . 'PERAVATAR'] = '';
				$_SESSION[GLBAPPPORT . 'PERUSADIS'] = '';
				$_SESSION[GLBAPPPORT . 'PERUSAREU'] = '';
				$_SESSION[GLBAPPPORT . 'PERUSAMSG'] = '';
				$_SESSION[GLBAPPPORT . 'PERTIPO'] = '';
				$_SESSION[GLBAPPPORT . 'PERCLASE'] = '';
				$_SESSION[GLBAPPPORT . 'TIMREG'] = '';
				$_SESSION[GLBAPPPORT . 'TIMDESCRI'] = '';
				$_SESSION[GLBAPPPORT . 'TIMOFFSET'] = '';

				header('Location: registermail');
				exit;
			} else if ($estcodigo == 8) { //Perfil sin liberar
				$_SESSION[GLBAPPPORT . 'PERCODIGO'] = '';
				$_SESSION[GLBAPPPORT . 'PERNOMBRE'] = '';
				$_SESSION[GLBAPPPORT . 'PERAPELLI'] = '';
				$_SESSION[GLBAPPPORT . 'PERCORREO'] = '';
				$_SESSION[GLBAPPPORT . 'PERCOMPAN'] = '';
				$_SESSION[GLBAPPPORT . 'PERUSUACC'] = '';
				$_SESSION[GLBAPPPORT . 'PERPASACC'] = '';
				$_SESSION[GLBAPPPORT . 'PERADMIN'] = '';
				$_SESSION[GLBAPPPORT . 'PERAVATAR'] = '';
				$_SESSION[GLBAPPPORT . 'PERUSADIS'] = '';
				$_SESSION[GLBAPPPORT . 'PERUSAREU'] = '';
				$_SESSION[GLBAPPPORT . 'PERUSAMSG'] = '';
				$_SESSION[GLBAPPPORT . 'PERTIPO'] = '';
				$_SESSION[GLBAPPPORT . 'PERCLASE'] = '';
				$_SESSION[GLBAPPPORT . 'TIMREG'] = '';
				$_SESSION[GLBAPPPORT . 'TIMDESCRI'] = '';
				$_SESSION[GLBAPPPORT . 'TIMOFFSET'] = '';

				header('Location: registerwait');
				exit;
			} else { //Perfil OK

				$_SESSION[GLBAPPPORT . 'PERCODIGO'] 	= $percodigo;
				$_SESSION[GLBAPPPORT . 'PERNOMBRE'] 	= trim($row['PERNOMBRE']);
				$_SESSION[GLBAPPPORT . 'PERAPELLI'] 	= trim($row['PERAPELLI']);
				$_SESSION[GLBAPPPORT . 'PERCORREO'] 	= strtoupper(trim($row['PERCORREO']));
				$_SESSION[GLBAPPPORT . 'PERCOMPAN'] 	= trim($row['PERCOMPAN']);;
				$_SESSION[GLBAPPPORT . 'PERUSUACC'] 	= strtoupper(trim($row['PERUSUACC']));
				$_SESSION[GLBAPPPORT . 'PERPASACC'] 	= trim($row['PERPASACC']);
				$_SESSION[GLBAPPPORT . 'PERADMIN'] 	= trim($row['PERADMIN']);
				$_SESSION[GLBAPPPORT . 'PERUSADIS'] 	= trim($row['PERUSADIS']);
				$_SESSION[GLBAPPPORT . 'PERUSAREU'] 	= trim($row['PERUSAREU']);
				$_SESSION[GLBAPPPORT . 'PERUSAMSG'] 	= trim($row['PERUSAMSG']);
				$_SESSION[GLBAPPPORT . 'PERTIPO'] 	= trim($row['PERTIPO']);
				$_SESSION[GLBAPPPORT . 'PERCLASE'] 	= trim($row['PERCLASE']);
				$_SESSION[GLBAPPPORT . 'PERIDIOMA']  = trim($row['PERIDIOMA']);
				$_SESSION[GLBAPPPORT . 'TIMREG'] 		= trim($row['TIMREG']);;
				$_SESSION[GLBAPPPORT . 'TIMDESCRI'] 	= trim($row['TIMDESCRI']);;
				$_SESSION[GLBAPPPORT . 'TIMOFFSET'] 	= trim($row['TIMOFFSET']);;

				if (trim($row['PERAVATAR']) != '') {
					$_SESSION[GLBAPPPORT . 'PERAVATAR'] =  $pathimagenes . $percodigo . '/' . trim($row['PERAVATAR']);
				} else {
					$_SESSION[GLBAPPPORT . 'PERAVATAR'] =  '../app-assets/img/avatar.png';
				}

				//Busco los parametro de clasificacion
				$query = "	SELECT 1
								FROM SEC_MAEST
								WHERE ESTCODIGO<>3 ";
				$Table = sql_query($query, $conn);
				if ($Table->Rows_Count > 0) {
					$_SESSION[GLBAPPPORT . 'SECTORES'] = 1;
				} else {
					$_SESSION[GLBAPPPORT . 'SECTORES'] = 0;
				}

				$query = "	SELECT 1
								FROM SEC_SUB
								WHERE ESTCODIGO<>3 ";
				$Table = sql_query($query, $conn);
				if ($Table->Rows_Count > 0) {
					$_SESSION[GLBAPPPORT . 'SUBSECTORES'] = 1;
				} else {
					$_SESSION[GLBAPPPORT . 'SUBSECTORES'] = 0;
				}

				$query = "	SELECT 1
								FROM CAT_MAEST
								WHERE ESTCODIGO<>3 ";
				$Table = sql_query($query, $conn);
				if ($Table->Rows_Count > 0) {
					$_SESSION[GLBAPPPORT . 'CATEGORIAS'] = 1;
				} else {
					$_SESSION[GLBAPPPORT . 'CATEGORIAS'] = 0;
				}

				$query = "	SELECT 1
								FROM CAT_SUB
								WHERE ESTCODIGO<>3 ";
				$Table = sql_query($query, $conn);
				if ($Table->Rows_Count > 0) {
					$_SESSION[GLBAPPPORT . 'SUBCATEGORIAS'] = 1;
				} else {
					$_SESSION[GLBAPPPORT . 'SUBCATEGORIAS'] = 0;
				}
			}
		} else {

			header('Location: loginerror');

			exit;
		}
	}
}
//Se coloca aqui para que tome el idioma
require_once GLBRutaFUNC . '/idioma.php'; //Idioma
DDIdioma($tmpl);

//Parametros del Sistema
//Busco los parametros de configuracion
$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF ";
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$_SESSION['PARAMETROS'][trim($row['ZPARAM'])] = trim($row['ZVALUE']);
}

//Habilito las opciones del Menu
if (json_decode($_SESSION['PARAMETROS']['MenuActividades']) == false) {
	$tmpl->setVariable('ParamMenuActividades', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuAgenda']) == false) {
	$tmpl->setVariable('ParamMenuAgenda', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuMensajes']) == false) {
	$tmpl->setVariable('ParamMenuMensajes', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuNoticias']) == false) {
	$tmpl->setVariable('ParamMenuNoticias', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuExportar']) == false) {
	$tmpl->setVariable('ParamMenuExportar', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuEncuesta']) == false) {
	$tmpl->setVariable('ParamMenuEncuesta', 'display:none;');
}

//--------------------------------------------------------------------------------------------------------------
$percodigo 			= (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre 			= (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';
$perapelli 			= (isset($_SESSION[GLBAPPPORT . 'PERAPELLI'])) ? trim($_SESSION[GLBAPPPORT . 'PERAPELLI']) : '';
$perusuacc 			= (isset($_SESSION[GLBAPPPORT . 'PERUSUACC'])) ? trim($_SESSION[GLBAPPPORT . 'PERUSUACC']) : '';
$perpasacc 			= (isset($_SESSION[GLBAPPPORT . 'PERCORREO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCORREO']) : '';
$peradmin 			= (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';
$peravatar 			= (isset($_SESSION[GLBAPPPORT . 'PERAVATAR'])) ? trim($_SESSION[GLBAPPPORT . 'PERAVATAR']) : '';
$btnsectores 		= (isset($_SESSION[GLBAPPPORT . 'SECTORES'])) ? trim($_SESSION[GLBAPPPORT . 'SECTORES']) : '';
$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT . 'SUBSECTORES'])) ? trim($_SESSION[GLBAPPPORT . 'SUBSECTORES']) : '';
$btncategorias 		= (isset($_SESSION[GLBAPPPORT . 'CATEGORIAS'])) ? trim($_SESSION[GLBAPPPORT . 'CATEGORIAS']) : '';
$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT . 'SUBCATEGORIAS'])) ? trim($_SESSION[GLBAPPPORT . 'SUBCATEGORIAS']) : '';
$pertipo 			= (isset($_SESSION[GLBAPPPORT . 'PERTIPO'])) ? trim($_SESSION[GLBAPPPORT . 'PERTIPO']) : '';
$perclase 			= (isset($_SESSION[GLBAPPPORT . 'PERCLASE'])) ? trim($_SESSION[GLBAPPPORT . 'PERCLASE']) : '';

$tmpl->setVariable('percodnotif', $percodigo);
$tmpl->setVariable('pernombre', $pernombre);
$tmpl->setVariable('perapelli', $perapelli);
$tmpl->setVariable('perusuacc', $perusuacc);
$tmpl->setVariable('perpasacc', $perpasacc);
$tmpl->setVariable('peravatar', str_replace('../', '', $peravatar));

//Nombre del Evento
// if (isset($_SESSION['PARAMETROS'])) {

// 	$tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);
// }

$tmpl->setVariable('SisNombreEvento', NAME_TITLE );


if ($peradmin != 1) $tmpl->setVariable('viewadmin', 'none');
if ($btnsectores != 1) $tmpl->setVariable('btnsectores', 'display:;');
if ($btnsubsectores != 1) $tmpl->setVariable('btnsubsectores', 'display:;');
if ($btncategorias != 1) $tmpl->setVariable('btncategorias', 'display:none;');
if ($btnsubcategorias != 1) $tmpl->setVariable('btnsubcategorias', 'display:none;');
//--------------------------------------------------------------------------------------------------------------
//SOLO LOS PERFILES ADMINISRTADOR, SPONSORS, podran ingresar
//if ($peradmin!=1){
//	if($pertipo!=1 && $pertipo!=3){
//		header('Location: msgprox');
//	}
//}


//--------------------------------------------------------------------------------------------------------------
//Verifico si el perfil tiene un ticket de acceso contratado
$query = "	SELECT TIKSALDO
			FROM PER_TICK
			WHERE PERCODIGO=$percodigo ";
$Table = sql_query($query, $conn);
if($Table->Rows_Count>0){
	//Posee un ticket, se verifica el saldo
	$row = $Table->Rows[0];
	$tiksaldo = trim($row['TIKSALDO']);
	
}else{
	//NO tiene ticket comprado, redirecciono a comprar un ticket
	header('Location: comptick');
}

//--------------------------------------------------------------------------------------------------------------
//Reuniones solicitadas y pendientes
$query = "	SELECT COUNT(*) AS CANTIDAD
			FROM REU_CABE R
			LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODSOL
			WHERE R.PERCODSOL=$percodigo AND R.REUESTADO=1 ";
$Table = sql_query($query, $conn);
$row = $Table->Rows[0];
$cantEnviados = trim($row['CANTIDAD']);
if ($cantEnviados == 0)	$cantEnviados = '';

//Reuniones recibidas y pendientes
$query = "	SELECT COUNT(*) AS CANTIDAD
			FROM REU_CABE R
			LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODSOL
			WHERE  R.PERCODDST=$percodigo AND R.REUESTADO=1  ";
$Table = sql_query($query, $conn);
$row = $Table->Rows[0];
$cantRecibidos = trim($row['CANTIDAD']);
if ($cantRecibidos == 0)	$cantRecibidos = '';

$tmpl->setVariable('cantEnviados', $cantEnviados);
$tmpl->setVariable('cantRecibidos', $cantRecibidos);

$colors = array('#0BE000', '#E0D500', '#E02C00', '#0068E0', '#00E0D8', '#DD00E0');

//Busco las notificaciones
$query = "	SELECT  N.NOTREG, N.NOTFCHREG,N.NOTTITULO,P.PERCODIGO, P.PERNOMBRE, P.PERAPELLI,P.PERAVATAR, P.PERCOMPAN,N.NOTCODIGO, R.REUESTADO,R.REUFECHA,R.REUHORA
			FROM NOT_CABE N
			LEFT OUTER JOIN REU_CABE R ON R.REUREG = N.REUREG 
			LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO = N.PERCODORI
			WHERE N.PERCODDST=$percodigo AND R.REUESTADO = 2
			ORDER BY R.REUFECHA ASC ";

$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$notreg    	= trim($row['NOTREG']);
	$notfchreg 	= BDConvFch($row['NOTFCHREG']);
	$nottitulo	= DDStrIdioma($row['NOTTITULO']);
	$pernombre 	= trim($row['PERNOMBRE']);
	$perapelli 	= trim($row['PERAPELLI']);
	$percompan 	= trim($row['PERCOMPAN']);
	$notcodigo 	= trim($row['NOTCODIGO']);
	$reufecha 	= trim($row['REUFECHA']);
	$reuhora 	= trim($row['REUHORA']);
	$percodigo 	= trim($row['PERCODIGO']);
	$reufecha	= substr($reufecha, 5, 2) . '/' . substr($reufecha, 8, 2) . '/' . substr($reufecha, 0, 4);
	$vaux = explode(' ', $notfchreg);
	$notfchreg = $vaux[0];
	$notfchhor = $vaux[1];

	if (trim($row['PERAVATAR']) != '') {
		$peravatar = $pathimagenes . $percodigo . '/' . trim($row['PERAVATAR']);
	} else {
		$peravatar =  '../assets-nuevodisenio/img/avatar.png';
	}
	$tmpl->setCurrentBlock('notificaciones');
	$tmpl->setVariable('nottitulo', $nottitulo);
	$tmpl->setVariable('notfchreg', $notfchreg);
	$tmpl->setVariable('notfchhor', $notfchhor);
	$tmpl->setVariable('percompan', $percompan);
	$tmpl->setVariable('notcodigo', $notcodigo);
	$tmpl->setVariable('reufecha', $reufecha);
	$tmpl->setVariable('reuhora', $reuhora);
	$tmpl->setVariable('peravatar', str_replace('../', '', $peravatar));


	$tmpl->parse('notificaciones');
}

//Traigo todas las noticias
$query = "	SELECT AVIREG, AVITITULO, AVIDESCRIP, AVIIMAGEN, AVIURL 
			FROM AVI_MAEST
			WHERE ESTCODIGO=1
			ORDER BY AVIREG DESC";
$Table = sql_query($query, $conn);
$countnoticias = -1;

$active = 'active';
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$avireg 	= trim($row['AVIREG']);
	$avititulo 	= trim($row['AVITITULO']);
	$avidescrip = trim($row['AVIDESCRIP']);
	$aviurl     = trim($row['AVIURL']);
	$aviimagen  = trim($row['AVIIMAGEN']);

	if ($countnoticias == -1) {
		$tmpl->setCurrentBlock('notise');
		$countnoticias++;
	}
	if ($countnoticias == 4) {
		//echo('<p style="font-size:200px">Hola mundo</p>');
		$tmpl->parse('notise');
		$tmpl->setCurrentBlock('notise');
		$countnoticias = 0;
	}
	$tmpl->setCurrentBlock('noticias');
	$tmpl->setVariable('avireg', $avireg);
	$tmpl->setVariable('avititulo', $avititulo);
	$tmpl->setVariable('avidescrip', $avidescrip);
	$tmpl->setVariable('active', $active);
	$tmpl->setvariable('aviurl', $aviurl);
	$tmpl->setVariable('aviimagen', $pathimg . '/' . $avireg . '/' . $aviimagen);
	$tmpl->parse('noticias');

	$active = "";

	$countnoticias++;
}


//Verifico si hay encuestas activas, y si el usuario tiene encuestas sin contestar
if (isset($_SESSION['success'])) {
	if ($_SESSION['success'] == true) {
		$query = "	SELECT EC.ENCREG
						FROM ENC_CABE EC
						WHERE EC.ESTCODIGO=1 AND EC.ENCPUBLIC='S' ";
		$Table = sql_query($query, $conn);
		if ($Table->Rows_Count > 0) {
			//Busco las reuniones que ya pasaron y estan confirmadas, y las mismas estan sin respuesta
			$query = "	SELECT R.REUREG
							FROM REU_CABE R
							WHERE(R.PERCODDST=$percodigo OR R.PERCODSOL=$percodigo) AND R.REUESTADO=2 
									AND R.REUFECHA<=CURRENT_DATE AND R.REUHORA<CURRENT_TIME 
									AND NOT EXISTS(	SELECT 1 
													FROM ENC_RESP E 
													WHERE E.REUREG=R.REUREG AND E.PERCODIGO=$percodigo) ";

			$Table = sql_query($query, $conn);
			if ($Table->Rows_Count > 0) {
				$tmpl->setVariable('modal', 'true');
			} else {
				$tmpl->setVariable('modal', 'false');
			}
		} else { //Sin encuestas
			$tmpl->setVariable('modal', 'false'); //No muestro el cartel
		}

		$_SESSION['success'] = false;
	}
} else {
	$tmpl->setVariable('modal', 'false'); //No muestro el cartel
	$_SESSION['success'] = false;
}

$tmpl->parse('notise');



/* ------------------------ SECTION MUESTRO SPEAKERS ------------------------ */

$query = "	SELECT SPKREG, SPKNOMBRE, SPKPOS, SPKDESCRI, SPKCARGO,ESTCODIGO,SPKIMG,SPKEMPRES
			FROM SPK_MAEST
			WHERE ESTCODIGO=1
			ORDER BY SPKPOS ASC";
$Table = sql_query($query, $conn);
$countSpk = 0;
$rowmain = [];
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];

	array_push($rowmain, $row);

	$spkreg 	= trim($row['SPKREG']);
	$spktitulo 	= trim($row['SPKNOMBRE']);
	$spkdescri  = trim($row['SPKDESCRI']);
	$spkpos     = trim($row['SPKPOS']);
	$spkcargo   = trim($row['SPKCARGO']);
	$spkimg     = trim($row['SPKIMG']);
	$spkempres  = trim($row['SPKEMPRES']);
	
	$tmpl->setCurrentBlock('spk');
	$tmpl->setVariable('spkreg'		, $spkreg);
	$tmpl->setVariable('spktitulo'	, $spktitulo);
	$tmpl->setVariable('spkdescri'	, $spkdescri);
	$tmpl->setvariable('spkpos'		, $spkpos);
	$tmpl->setvariable('spkcargo'	, $spkcargo);
	$tmpl->setvariable('spkimg'		, $spkimg);
	$tmpl->setvariable('spkempres'	, $spkempres);
	$tmpl->parse('spk');
}





/* ----------------------- SECTION MOSTRAR ACTIVIADES ----------------------- */
//--------------------------------------------------------------------------------------------------------------
//Busco los parametros de configuracion
$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF WHERE ZPARAM CONTAINING 'SisEvento' ";
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$params[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
}

$diasini = $params['SisEventoDiaInicio']; 		 			//Evento - Dia de Inicio
$diasdur = intval($params['SisEventoDuracionDias']); 	 	//Evento - Cantidad de Dias de duracion
$horaini = $params['SisEventoHorario']; 		 			//Evento - Horario de Inicio y Fin
$horaint = intval($params['SisEventoHorarioIntervalo']); 	//Evento - Intervalo de tiempo (min)

$tmpl->setVariable('horaint', $horaint);

$inicio = substr($diasini, 6, 4) . '-' . substr($diasini, 3, 2) . '-' . substr($diasini, 0, 2); //Formato calendario (yyyy-mm-dd)

$diaInicio = substr($inicio, 8, 9);



$hoy 	= date('m/d/Y');
$ahora 	= date('H:i');

//Modifico hoy para ver las actividaes
// $hoy = 08;



$coloReunion	= '#967ADC';
$colorBloqueado	= '#FFAD8F';
$linkantesala 	= 'link';
$salas = ['Plenaria 1','Plenaria 2','Taller CQ 1','Taller CQ 2','Taller CQ 3','Taller CQ 4','Taller CQ 5','Taller CQ 6'];

for ($j = 0; $j < count($salas); $j++) {


			$sala = $salas[$j];

			//Proximas conferencias
			$query = "SELECT FIRST 1 AGEREG, AGEFCH, AGEHORINI, AGELUGAR
						FROM AGE_MAEST
						WHERE   ESTCODIGO <> 3 AND AGELUGAR = '$sala' AND (AGEFCH>'$hoy' OR (AGEFCH='$hoy' AND AGEHORFIN>(CAST('$ahora' AS TIME))))
						ORDER BY AGEFCH, AGEHORINI";

			$Table = sql_query($query, $conn);
			for ($i = 0; $i < $Table->Rows_Count; $i++) {
				$row = $Table->Rows[$i];
				$agereg 	= trim($row['AGEREG']);
				$linksala= '../antesala/bsq.php?T=';
				$linkantesala.=$j;

				$tmpl->setVariable($linkantesala, $linksala.$agereg);
				$linkantesala 	= 'link';

				
			}
		}

$percodigo 			= (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';

$imgAvatarNull = 'assets-nuevodisenio/img/avatar.png';


$query = "	SELECT R.REUREG,R.REUHORA,R.REUFECHA,R.PERCODSOL,R.PERCODDST,P.PERCODIGO,P.PERNOMBRE,P.PERAVATAR, P.PERAPELLI, A.AGETITULO,R.REUFECHA
			FROM REU_CABE R
			INNER JOIN PER_MAEST P  ON P.PERCODIGO = R.PERCODDST    
			INNER JOIN AGE_MAEST A ON A.AGEREG =  R.AGEREG
			WHERE   R.PERCODSOL = $percodigo AND (R.REUFECHA>CURRENT_DATE OR( R.REUFECHA=CURRENT_DATE AND R.REUHORA>=CURRENT_TIME))
			ORDER BY  R.REUFECHA, R.REUHORA";

$Table = sql_query($query, $conn);
if ($Table->Rows_Count != -1) {
	for ($i = 0; $i < $Table->Rows_Count; $i++) {

		$row = $Table->Rows[$i];
		$codper =  $row['PERCODIGO'];
		$percodsol =  $row['PERCODSOL'];
		$percoddst =  $row['PERCODDST'];
		$nombre =  $row['PERNOMBRE'];
		$apellido =  $row['PERAPELLI'];
		$reuhora =  $row['REUHORA'];
		$reufecha =  BDConvFch($row['REUFECHA']);
		$percodigo =  $row['PERCODIGO'];
		$avatar =  $row['PERAVATAR'];
		$agetitulo =  $row['AGETITULO'];
		$tmpl->setCurrentBlock('personal-calendar');

		//2020-06-29

		$mes  =  substr($reufecha, 5, 6);
		$tmpl->setVariable('horareunion', substr($reuhora, 0, 5));
		$tmpl->setVariable('reufecha', $reufecha);


		if ($percodsol == $percoddst) {
			$tmpl->setVariable('avatar', 'assets-nuevodisenio/img/login/mic2.jpg');
			$tmpl->setVariable('nombre', $agetitulo);
		} else {
			if ($avatar != '') {

				$tmpl->setVariable('avatar', 'perimg/' . $codper . '/' . $avatar);
			} else {
				$tmpl->setVariable('avatar', $imgAvatarNull);
			}

			$tmpl->setVariable('nombre', $nombre);
			$tmpl->setVariable('apellido', $apellido);
		}


		$tmpl->parse('personal-calendar');
	}
}

//Chequeo catidad de imagenes
$micrositio  = "SELECT EXPREG,PERCODIGO  FROM EXP_PER WHERE PERCODIGO = $percodigo";
$TblMicrositio = sql_query($micrositio, $conn);

if($TblMicrositio->Rows_Count != -1){
	$RowCount		= $TblMicrositio->Rows[0];
	$expreg 		= trim($RowCount['EXPREG']);
	$tmpl->setVariable('micrositio', 'sponsor/bsq?id='.$expreg);

}else{
	$tmpl->setVariable('micrositio', 'stand/bsq');

}

$micrositioOld  = "SELECT EXPREG,PERCODIGO  FROM EXP_MAEST WHERE PERCODIGO = $percodigo";
$TblMicrositioOld = sql_query($micrositioOld, $conn);

if($TblMicrositioOld->Rows_Count != -1){
	$RowCount		= $TblMicrositioOld->Rows[0];
	$expreg 		= trim($RowCount['EXPREG']);
	$tmpl->setVariable('micrositio', 'sponsor/bsq?id='.$expreg);

}

$tmpl->show();
