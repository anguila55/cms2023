<?
header('Cache-Control: no cache'); //no cache
//session_cache_limiter('private_no_expire'); // works
require('api/365apivalidate.php');
session_start();
include $_SERVER["DOCUMENT_ROOT"] . '/func/zglobals.php'; //PRD
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma
require_once GLBRutaFUNC . '/constants.php'; //Idioma
require_once GLBRutaAPI  . '/timezone_bot.php';

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('index.html');

//Diccionario de idiomas
//var_dump(DDIdioma($tmpl));

// Agregarmos una variable CaptCha

// Fin Agregarmos una variable CaptCha

$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$perusuacc = (isset($_POST['perusuacc'])) ? trim(SQL_replace($_POST['perusuacc'])) : '';
$perpasacc = (isset($_POST['perpasacc'])) ? trim(SQL_replace($_POST['perpasacc'],true)) : '';
$codigoqr = (isset($_POST['codigoqr'])) ? trim(SQL_replace($_POST['codigoqr'],true)) : '';

$_SESSION['success'] = true;

validateLogin($percodigo);

$conn = sql_conectar(); //Apertura de Conexion

$pathimagenes = '../perimg/';
$pathimg = 'prensaimg';
$errmsg = '';

$redirecc = '';
if (isset($_GET['ID'])) {
    $notaid = trim($_GET['ID']);
    $redirecc = 'nota/bsq?ID=' . $notaid;
}
if ($codigoqr != '') {

    $vaux = explode('||', $codigoqr);
    if ($vaux[0] == 'E') {

        $redirecc = 'sponsor/bsq?ID=' . $vaux[1];
    }
    if ($vaux[0] == 'P') {

        $redirecc = 'directorio/bsq';
    }
    if ($vaux[0] == 'A') {

        $redirecc = 'actividades/bsq';
    }

}
if ($percodigo == '') {
    $perusuacc = eventUsers($perusuacc, $perpasacc);
	if ($perusuacc == '' || $perpasacc == '') {
		header('Location: login');
		exit;
    } else {

        $perpasacc = md5('BenVido' . $perpasacc . 'PassAcceso' . $perusuacc);
        $perpasacc = 'B#SD' . md5(substr($perpasacc, 1, 10) . 'BenVidO' . substr($perpasacc, 5, 8)) . 'E##$F';

        $query = " 	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCORREO,P.PERUSUACC,P.PERPASACC,P.ESTCODIGO,P.PERADMIN,P.PERAVATAR,
								P.PERUSADIS,P.PERUSAREU,P.PERUSAMSG,P.PERTIPO,P.PERCLASE,P.PERIDIOMA,P.PERCOMPAN,P.PERCARGO,P.TIMOFFSET,P.PERINGLOG
						FROM PER_MAEST P
						WHERE P.PERUSUACC='$perusuacc' AND P.ESTCODIGO!=3 ";

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
                $_SESSION[GLBAPPPORT . 'PERCARGO'] = '';
                $_SESSION[GLBAPPPORT . 'PERUSUACC'] = '';
                $_SESSION[GLBAPPPORT . 'PERPASACC'] = '';
                $_SESSION[GLBAPPPORT . 'PERADMIN'] = '';
                $_SESSION[GLBAPPPORT . 'PERAVATAR'] = '';
                $_SESSION[GLBAPPPORT . 'PERUSADIS'] = '';
                $_SESSION[GLBAPPPORT . 'PERUSAREU'] = '';
                $_SESSION[GLBAPPPORT . 'PERUSAMSG'] = '';
                $_SESSION[GLBAPPPORT . 'PERTIPO'] = '';
                $_SESSION[GLBAPPPORT . 'PERCLASE'] = '';
                $_SESSION[GLBAPPPORT . 'TIMOFFSET'] = '';

                header('Location: registermail');
                exit;
            } else if ($estcodigo == 8) { //Perfil sin liberar
                $_SESSION[GLBAPPPORT . 'PERCODIGO'] = '';
                $_SESSION[GLBAPPPORT . 'PERNOMBRE'] = '';
                $_SESSION[GLBAPPPORT . 'PERAPELLI'] = '';
                $_SESSION[GLBAPPPORT . 'PERCORREO'] = '';
                $_SESSION[GLBAPPPORT . 'PERCOMPAN'] = '';
                $_SESSION[GLBAPPPORT . 'PERCARGO'] = '';
                $_SESSION[GLBAPPPORT . 'PERUSUACC'] = '';
                $_SESSION[GLBAPPPORT . 'PERPASACC'] = '';
                $_SESSION[GLBAPPPORT . 'PERADMIN'] = '';
                $_SESSION[GLBAPPPORT . 'PERAVATAR'] = '';
                $_SESSION[GLBAPPPORT . 'PERUSADIS'] = '';
                $_SESSION[GLBAPPPORT . 'PERUSAREU'] = '';
                $_SESSION[GLBAPPPORT . 'PERUSAMSG'] = '';
                $_SESSION[GLBAPPPORT . 'PERTIPO'] = '';
                $_SESSION[GLBAPPPORT . 'PERCLASE'] = '';
                $_SESSION[GLBAPPPORT . 'TIMOFFSET'] = '';

                header('Location: registerwait');
                exit;
            } else { //Perfil OK

                $_SESSION[GLBAPPPORT . 'PERCODIGO'] = $percodigo;
                $_SESSION[GLBAPPPORT . 'PERNOMBRE'] = trim($row['PERNOMBRE']);
                $_SESSION[GLBAPPPORT . 'PERAPELLI'] = trim($row['PERAPELLI']);
                $_SESSION[GLBAPPPORT . 'PERCORREO'] = strtoupper(trim($row['PERCORREO']));
                $_SESSION[GLBAPPPORT . 'PERCOMPAN'] = trim($row['PERCOMPAN']);
                $_SESSION[GLBAPPPORT . 'PERCARGO'] = trim($row['PERCARGO']);
                $_SESSION[GLBAPPPORT . 'PERUSUACC'] = strtoupper(trim($row['PERUSUACC']));
                $_SESSION[GLBAPPPORT . 'PERPASACC'] = trim($row['PERPASACC']);
                $_SESSION[GLBAPPPORT . 'PERADMIN'] = trim($row['PERADMIN']);
                $_SESSION[GLBAPPPORT . 'PERUSADIS'] = trim($row['PERUSADIS']);
                $_SESSION[GLBAPPPORT . 'PERUSAREU'] = trim($row['PERUSAREU']);
                $_SESSION[GLBAPPPORT . 'PERUSAMSG'] = trim($row['PERUSAMSG']);
                $_SESSION[GLBAPPPORT . 'PERTIPO'] = trim($row['PERTIPO']);
                $_SESSION[GLBAPPPORT . 'PERCLASE'] = trim($row['PERCLASE']);
                $_SESSION[GLBAPPPORT . 'PERIDIOMA'] = trim($row['PERIDIOMA']);
                $_SESSION[GLBAPPPORT . 'TIMOFFSET'] = trim($row['TIMOFFSET']);

                $peringlog = trim($row['PERINGLOG']);
                if ($peringlog == '') {
                    $query = "	UPDATE PER_MAEST SET PERINGLOG=CURRENT_TIMESTAMP
								WHERE PERCODIGO=$percodigo ";
                    $err = sql_execute($query, $conn);
                }

                //Marco la fecha de ingreso como estampa de ultimo login
                $query = "	UPDATE PER_MAEST SET PERULTLOG=CURRENT_TIMESTAMP
							WHERE PERCODIGO=$percodigo ";
                $err = sql_execute($query, $conn);

                if ($redirecc != '') {

                    header('Location: ' . $redirecc);
                } else {
                    //header('Location: index');
                }

                init_login($perusuacc, $perpasacc);

                if (trim($row['PERAVATAR']) != '') {
					
					if(strpos(trim($row['PERAVATAR']), "https://") !== false){

						$_SESSION[GLBAPPPORT.'PERAVATAR'] 	=  trim($row['PERAVATAR']);
						
					
					}else{
						$_SESSION[GLBAPPPORT.'PERAVATAR'] 	=  trim($row['PERAVATAR']);
					}
				
				
				//$_SESSION[GLBAPPPORT . 'PERAVATAR'] =  $pathimagenes . $percodigo . '/' . trim($row['PERAVATAR']);
					} else {
						$_SESSION[GLBAPPPORT.'PERAVATAR'] 	=  '../app-assets/img/avatar.png';
						//$_SESSION[GLBAPPPORT . 'PERAVATAR'] =  '../app-assets/img/avatar.png';
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
$peridioma 			= (isset($_SESSION[GLBAPPPORT . 'PERIDIOMA'])) ? trim($_SESSION[GLBAPPPORT . 'PERIDIOMA']) : '';


$tmpl->setVariable('percodnotif', $percodigo);
$tmpl->setVariable('pernombre', $pernombre);
$tmpl->setVariable('perapelli', $perapelli);
$tmpl->setVariable('perusuacc', $perusuacc);
$tmpl->setVariable('perpasacc', $perpasacc);
$tmpl->setVariable('peridioma', $peridioma);
$tmpl->setVariable('peravatar', str_replace('../', '', $peravatar));

//Nombre del Evento
// if (isset($_SESSION['PARAMETROS'])) {

//     $tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);
// }
$tmpl->setVariable('SisNombreEvento', NAME_TITLE);
$tmpl->setVariable('EventoUrl', URL_WEB);

if ($peradmin != 1) {
    $tmpl->setVariable('viewadmin', 'none');
}

if ($btnsectores != 1) {
    $tmpl->setVariable('btnsectores', 'display:;');
}

if ($btnsubsectores != 1) {
    $tmpl->setVariable('btnsubsectores', 'display:;');
}

if ($btncategorias != 1) {
    $tmpl->setVariable('btncategorias', 'display:none;');
}

if ($btnsubcategorias != 1) {
    $tmpl->setVariable('btnsubcategorias', 'display:none;');
}

//Marco el perfil como logueado
//Si es la primera vez que ingreso, inserto la estampa

//--------------------------------------------------------------------------------------------------------------
//SOLO LOS PERFILES ADMINISRTADOR, SPONSORS, podran ingresar
//if ($peradmin!=1){
//    if($pertipo!=1 && $pertipo!=3){
//        header('Location: msgprox');
//    }
//}

if ($peradmin!=1){
$hoyfecha = date('Y-m-d');

    $queryBloqueo = "SELECT PERBLOQ
                    FROM PER_CLASE
                    WHERE PERCLASE=$perclase AND ESTCODIGO=1";

    $TableBloqueo = sql_query($queryBloqueo, $conn);

    if ($TableBloqueo->Rows_Count > 0) {
        $rowBloqueo = $TableBloqueo->Rows[0];
        $perbloq = $rowBloqueo['PERBLOQ'];

        $hoyfecha = new DateTime($hoyfecha);
        $perbloq = new DateTime($perbloq); // Can use date/string just like strtotime.

        if ($perbloq > $hoyfecha) {
            header('Location: msgprox');
        }

    }
}
//--------------------------------------------------------------------------------------------------------------
//Verifico si el perfil tiene un ticket de acceso contratado
//$query = "    SELECT TIKSALDO
//            FROM PER_TICK
//            WHERE PERCODIGO=$percodigo ";
//$Table = sql_query($query, $conn);
//if($Table->Rows_Count>0){
//Posee un ticket, se verifica el saldo
//    $row = $Table->Rows[0];
//    $tiksaldo = trim($row['TIKSALDO']);
//
//}else{
//    //NO tiene ticket comprado, redirecciono a comprar un ticket
//    header('Location: comptick');
//}
$query = "	UPDATE PER_MAEST SET PERULTLOG=CURRENT_TIMESTAMP
							WHERE PERCODIGO=$percodigo ";
$err = sql_execute($query, $conn);
//--------------------------------------------------------------------------------------------------------------
//Reuniones solicitadas y pendientes
$query = "	SELECT COUNT(*) AS CANTIDAD
			FROM REU_CABE R
			LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODSOL
			WHERE R.PERCODSOL=$percodigo AND R.REUESTADO=1 ";
$Table = sql_query($query, $conn);
$row = $Table->Rows[0];
$cantEnviados = trim($row['CANTIDAD']);
if ($cantEnviados == 0) {
    $cantEnviados = '';
}

//Reuniones recibidas y pendientes
$query = "	SELECT COUNT(*) AS CANTIDAD
			FROM REU_CABE R
			LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODSOL
			WHERE  R.PERCODDST=$percodigo AND R.REUESTADO=1  ";
$Table = sql_query($query, $conn);
$row = $Table->Rows[0];
$cantRecibidos = trim($row['CANTIDAD']);
if ($cantRecibidos == 0) {
    $cantRecibidos = '';
}

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
    $notreg = trim($row['NOTREG']);
    $notfchreg = BDConvFch($row['NOTFCHREG']);
    $nottitulo = DDStrIdioma($row['NOTTITULO']);
    $pernombre = trim($row['PERNOMBRE']);
    $perapelli = trim($row['PERAPELLI']);
    $percompan = trim($row['PERCOMPAN']);
    $notcodigo = trim($row['NOTCODIGO']);
    $reufecha = trim($row['REUFECHA']);
    $reuhora = trim($row['REUHORA']);
    $percodigodos = trim($row['PERCODIGO']);
    $reufecha = substr($reufecha, 5, 2) . '/' . substr($reufecha, 8, 2) . '/' . substr($reufecha, 0, 4);
    $vaux = explode(' ', $notfchreg);
    $notfchreg = $vaux[0];
    $notfchhor = $vaux[1];

    if (trim($row['PERAVATAR']) != '') {
        $peravatar = $pathimagenes . $percodigodos . '/' . trim($row['PERAVATAR']);
    } else {
        $peravatar = '../app-assets/img/avatar.png';
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

$liqueo = 0;
$pathimagenesvideo = 'vidimg/';
$tmpl->setVariable('displayondemand', 'd-none');

// Traigo todos los videos
$query = "SELECT FIRST 3 VIDREG,VIDTITULO,VIDURL,VIDURLPDF,VIDID,US_NOMBRE,US_MAIL,US_TEL,US_EMP,US_PAI,US_LIN,US_FAC,US_TWI,US_WEB,VIDIMG,VIDCATEGO
		FROM VID_MAEST
		WHERE ESTCODIGO=1
		ORDER BY VIDREG DESC";
$Table = sql_query($query, $conn);

$colvid = 0;
for ($i = 0; $i < $Table->Rows_Count; $i++) {
    $row = $Table->Rows[$i];
    $vidreg = trim($row['VIDREG']);
    $vidtitulo = trim($row['VIDTITULO']);
    $vidurl = trim($row['VIDURL']);
    $vidurlpdf = trim($row['VIDURLPDF']);
    $vidid = trim($row['VIDID']);
    $usnombre = trim($row['US_NOMBRE']);
    $usmail = trim($row['US_MAIL']);
    $ustelefono = trim($row['US_TEL']);
    $usempresa = trim($row['US_EMP']);
    $uspais = trim($row['US_PAI']);
    $uslinkedin = trim($row['US_LIN']);
    $usfacebook = trim($row['US_FAC']);
    $ustwitter = trim($row['US_TWI']);
    $usweb = trim($row['US_WEB']);
    $vidimg = trim($row['VIDIMG']);
    $vidcatego = trim($row['VIDCATEGO']);
    $tmpl->setVariable('displayondemand', '');

    $tmpl->setCurrentBlock('videos');

    $tmpl->setVariable('botonclick', 'fa-youtube-play');
    $tmpl->setVariable('botonclicklink', "addPoints(" . $vidreg . ",'',9)");

    if ($vidurl == '') {

        $tmpl->setVariable('displayvideo', 'display:none;');

        if ($vidurlpdf != '') {
            $tmpl->setVariable('botonclicklink', "addPoints(" . $vidreg . ",'" . $vidurlpdf . "',10)");
            $tmpl->setVariable('botonclick', 'fa-files-o');
        }

    }

    if ($vidurlpdf == '') {

        $tmpl->setVariable('displaypdf', 'display:none;');

    }
    if ($usnombre == '') {

        $tmpl->setVariable('displayusuario', 'display:none;');

    }

    $queryliqueo = "SELECT VIDREG
					FROM VID_LIKE
					WHERE VIDREG=$vidreg AND PERCODIGO=$percodigo ";
    $Tableliqueo = sql_query($queryliqueo, $conn);
    if ($Tableliqueo->Rows_Count > 0) {
        $liqueo = 1;
    }

    if ($liqueo == 1) {

        $tmpl->setVariable('colorlike', 'color: #00005f!important;');
        $liqueo = 0;
    } else {

        $tmpl->setVariable('colorlike', 'color: #FFFFFF!important;');
    }
    $tmpl->setVariable('vidreg', $vidreg);
    $tmpl->setVariable('vidtitulo', $vidtitulo);
    $tmpl->setVariable('vidurl', $vidurl);
    $tmpl->setVariable('vidurlpdf', $vidurlpdf);
    $tmpl->setVariable('vidid', $vidid);
    $tmpl->setVariable('usnombre', $usnombre);
    $tmpl->setVariable('usmail', $usmail);
    $tmpl->setVariable('ustelefono', $ustelefono);
    $tmpl->setVariable('usempresa', $usempresa);
    $tmpl->setVariable('uspais', $uspais);
    $tmpl->setVariable('uslinkedin', $uslinkedin);
    $tmpl->setVariable('usfacebook', $usfacebook);
    $tmpl->setVariable('ustwitter', $ustwitter);
    $tmpl->setVariable('usweb', $usweb);
    $tmpl->setVariable('vidcatego', $vidcatego);

    if ($vidimg == '') {
        $vidimg = $imgAvatarNull;
    } else {
        $vidimg = $pathimagenesvideo . $vidreg . '/' . $vidimg;
    }
    $tmpl->setVariable('vidimg', $vidimg);

    $tmpl->parse('videos');

}

//Traigo todas las noticias
$tmpl->setVariable('displaynoticias', 'd-none');
$query = "	SELECT FIRST 3  PR.PREREG,  PR.PRETITULO, PR.PREIMG,PR.PREBAJADA, PR.PREFECHA, PR.PRECATEGO
				FROM PREN_MAEST PR
				WHERE PR.PREESTADO<>3
				ORDER BY PR.PREN_ORD ASC, PR.PREFECHA DESC ";

//logerror($query);
$Table = sql_query($query, $conn);

for ($i = 0; $i < $Table->Rows_Count; $i++) {
    $row = $Table->Rows[$i];
    $prereg = trim($row['PREREG']);
    $pretitulo = trim($row['PRETITULO']);
    $preimg = trim($row['PREIMG']);
    $precatego = trim($row['PRECATEGO']);
    $prefuente = trim($row['PREFUENTE']);
    $prebajada = trim($row['PREBAJADA']);
    $prefecha = BDConvFch($row['PREFECHA']);

    $tmpl->setVariable('displaynoticias', '');

    $tmpl->setCurrentBlock('notise');

    $preimg1 = $pathimg . '/' . $prereg . '/' . $preimg;

    $prefecha = substr($prefecha, 6, 4) . '-' . substr($prefecha, 3, 2) . '-' . substr($prefecha, 0, 2);

    $tmpl->setVariable('avireg', $prereg);
    $tmpl->setVariable('avititulo', $pretitulo);
    $tmpl->setVariable('aviimagen', $pathimg . '/' . $prereg . '/' . $preimg);
    $tmpl->setvariable('avifuente', $prefuente);
    $tmpl->setvariable('avidescrip', $prebajada);
    $tmpl->setvariable('avicatego', $precatego);
    $tmpl->setvariable('avifecha', $prefecha);
    $tmpl->setvariable('aviurl', '../nota/bsq?ID=' . $prereg . '||' . $pretitulo);

    $tmpl->parse('noticias');
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

/* ----------------------- SECTION MOSTRAR ACTIVIADES ----------------------- */
//--------------------------------------------------------------------------------------------------------------
//Busco los parametros de configuracion
$diasini = $_SESSION['PARAMETROS']['SisEventoDiaInicio']; //Evento - Dia de Inicio
$diasdur = intval($_SESSION['PARAMETROS']['SisEventoDuracionDias']); //Evento - Cantidad de Dias de duracion
$horaini = $_SESSION['PARAMETROS']['SisEventoHorario']; //Evento - Horario de Inicio y Fin
$horaint = intval($_SESSION['PARAMETROS']['SisEventoHorarioIntervalo']); //Evento - Intervalo de tiempo (min)

$tmpl->setVariable('horaint', $horaint);

$inicio = substr($diasini, 6, 4) . '-' . substr($diasini, 3, 2) . '-' . substr($diasini, 0, 2); //Formato calendario (yyyy-mm-dd)

$diaInicio = substr($inicio, 8, 9);

$coloReunion = '#967ADC';
$colorBloqueado = '#FFAD8F';
$where = '';
$contadoractividades = 0;
//Proximas conferencias
$hoy = date('m/d/Y');
$ahora = date('H:i');
$ahoracero = date('H:i', strtotime(-$_SESSION[GLBAPPPORT . 'TIMOFFSET'] . ' seconds', strtotime($ahora))); //Pongo la hora, segun el Huso horario establecido por el perfil
$ahoraargentina = date('H:i', strtotime('-10800 seconds', strtotime($ahoracero))); //Pongo la hora en Huso horario 0
$ahora = $ahoraargentina;
$arraycharlas = null;
$query = "	SELECT AGEREG, AGEFCH, AGETITULO, AGEDESCRI, AGEHORINI, AGEHORFIN, AGELUGAR , SPKREG,AGEYOULNK,AGETITING
			FROM AGE_MAEST
			WHERE   ESTCODIGO=1 AND (AGEFCH>='$hoy')
			ORDER BY AGEFCH, AGEHORINI";

$Table = sql_query($query, $conn);
if ($Table->Rows_Count != -1) {

    $tmpl->setVariable('displaytextoactividades', 'd-none');

    for ($i = 0; $i < $Table->Rows_Count; $i++) {
        $row = $Table->Rows[$i];
        $agereg = trim($row['AGEREG']);
        $agedescri = trim($row['AGEDESCRI']);
        $agelugar = trim($row['AGELUGAR']);
        $spkreg = trim($row['SPKREG']);
        $agefch = BDConvFch($row['AGEFCH']);
        $fechacalendar = substr($agefch, 6, 4) . '-' . substr($agefch, 3, 2) . '-' . substr($agefch, 0, 2); //Formato calendario (yyyy-mm-dd)

        $agehorini = substr(trim($row['AGEHORINI']), 0, 5);
        $agehorfin = substr(trim($row['AGEHORFIN']), 0, 5);
        $diaaux = explode('/', $agefch);
        $charlaini = strtotime($diaaux[1] . '/' . $diaaux[0] . '/' . $diaaux[2] . ' ' . $agehorini);
        $charlafin = strtotime($diaaux[1] . '/' . $diaaux[0] . '/' . $diaaux[2] . ' ' . $agehorfin);
        ///CAMBIOS DE HORARIOS
        $haux = date('H:i', strtotime('+10800 seconds', strtotime($agehorini))); //Pongo la hora en Huso horario 0
        $haux = date('H:i', strtotime($_SESSION[GLBAPPPORT . 'TIMOFFSET'] . ' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
        $agehorini = $haux;
        $haux2 = date('H:i', strtotime('+10800 seconds', strtotime($agehorfin))); //Pongo la hora en Huso horario 0
        $haux2 = date('H:i', strtotime($_SESSION[GLBAPPPORT . 'TIMOFFSET'] . ' seconds', strtotime($haux2))); //Pongo la hora, segun el Huso horario establecido por el perfil
        $agehorfin = $haux2;
        //////

        $ageyoulnk = trim($row['AGEYOULNK']);
        if (($IdiomView == 'ING')) {

            $agetitulo = trim($row['AGETITING']);
            $agetitulo = $agetitulo;

        } else {
            $agetitulo = trim($row['AGETITULO']);
            $agetitulo = $agetitulo;
        }

        $agefch = substr($agefch, 0, 2) . '/' . substr($agefch, 3, 2) . '/' . substr($agefch, 6, 4); //Formato calendario (yyyy-mm-dd)
        $agehorini = ($agehorini != '') ? $agehorini : '';
        $agehorfin = ($agehorfin != '') ? $agehorfin : '';

        $spkreglen = strlen($spkreg);

        $tmpl->setCurrentBlock('actividades');

        if ($ageyoulnk != '') {
            $tmpl->setVariable('video', 'd-block');
            $tmpl->setVariable('ageyoulnk', $ageyoulnk);

        } else {
            $tmpl->setVariable('video', 'd-none');

        }

        $tmpl->setVariable('agereg', $agereg);
        $tmpl->setVariable('titulocharla', $agetitulo);
        $tmpl->setVariable('agedescri', $agedescri);
        $tmpl->setvariable('agelugar', $agelugar);
        $tmpl->setVariable('agehorini', $agehorini);
        $tmpl->setVariable('agehorfin', $agehorfin);
        $tmpl->setVariable('color', $coloReunion);
        $tmpl->setVariable('agefch', $agefch);
        $contadoractividades = $i;
        $arraycharlas[] = ['key' => $charlaini, 'fin' => $charlafin, 'value' => $agereg, 'cant' => $contadoractividades + 1, 'tipo' => 0, 'agereg' => $agereg, 'titulocharla' => $agetitulo, 'agehorini' => $agehorini, 'agehorfin' => $agehorfin, 'agefch' => $agefch, 'fechacalendar' => str_replace('-', '', $fechacalendar), 'horacalendar' => $agehorini, 'minutoscalendar' => '', 'minutosfincalendar' => $agehorfin, 'titulocalendar' => $agetitulo, 'locationcalendar' => URL_WEB, 'SisNombreEvento' => NAME_TITLE];
        $tmpl->parse('actividades');
    }
} else {
    $tmpl->setVariable('displayactividades', 'd-none');
    $tmpl->setVariable('displaytextoactividades', '');
}

$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$imgAvatarNull = 'app-assets/img/avatar.png';
//--------------------------------------------------------------------------------------------------------------
//Calendario Personal - Reuniones solicitadas y Agendas seleccionadas

//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
//Calendario Personal - Reuniones solicitadas y Agendas seleccionadas
$percodlog = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernomlog = (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';
$percompanlog = (isset($_SESSION[GLBAPPPORT . 'PERCOMPAN'])) ? trim($_SESSION[GLBAPPPORT . 'PERCOMPAN']) : '';
$percargolog = (isset($_SESSION[GLBAPPPORT . 'PERCARGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCARGO']) : '';
$perapelog = (isset($_SESSION[GLBAPPPORT . 'PERAPELLI'])) ? trim($_SESSION[GLBAPPPORT . 'PERAPELLI']) : '';
$perusuacc = (isset($_SESSION[GLBAPPPORT . 'PERUSUACC'])) ? trim($_SESSION[GLBAPPPORT . 'PERUSUACC']) : '';
$percorreo = (isset($_SESSION[GLBAPPPORT . 'PERCORREO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCORREO']) : '';
$peradmin = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';
$peravatarlog = (isset($_SESSION[GLBAPPPORT . 'PERAVATAR'])) ? trim($_SESSION[GLBAPPPORT . 'PERAVATAR']) : '';

$videoconferencia = "BBB"; //BBB (BigBlueButton), AGORA
$pathimagenes = '../perimg/';
$imgAvatarNull = '../app-assets/img/avatar.png';

$conn = sql_conectar(); //Apertura de Conexion

//Busco los parametros de configuracion
$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF WHERE ZPARAM CONTAINING 'SisEvento' ";
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
    $row = $Table->Rows[$i];
    $params[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
}

$diaactual = date('m/d/Y');

//////////////// cantidad reuniones enviadas/solicitadas  /////////////////////////
$query = "	SELECT COUNT(REUREG ) U
					FROM REU_CABE
					WHERE (PERCODDST=$percodlog OR PERCODSOL=$percodlog) AND (REUESTADO=1) AND (PERCODDST!=PERCODSOL) ";
$Table = sql_query($query, $conn);
$row = $Table->Rows[0];
$reunionespendientes = trim($row['U']);

$tmpl->setVariable('reunionespendientes', $reunionespendientes);

$where = '';
$relacion = '';
//Ver reuniones que envie y me enviaron, pero ya estan confirmadas
$relacion = ' P.PERCODIGO=R.PERCODDST ';
$where .= "  (R.PERCODDST=$percodlog OR R.PERCODSOL=$percodlog) AND (R.REUESTADO=2 AND R.REUFECHA>='$diaactual')";

$arrayreuniones = null;
$durreunion = intval($params['SisEventoHorarioIntervalo']);
$tmpl->setVariable('durreunion', $durreunion); //Duracion de Reuniones

//Reuniones que solicitaron
$query = "	SELECT 	PD.PERCODIGO AS PERCODDST, PD.PERNOMBRE AS PERNOMDST, PD.PERAPELLI AS PERAPEDST, PD.PERCOMPAN AS PERCOMDST, PD.PERCARGO AS PERCARDST, PD.PERCORREO AS PERCORDST, PD.PERAVATAR AS PERAVADST,
						PS.PERCODIGO AS PERCODSOL, PS.PERNOMBRE AS PERNOMSOL, PS.PERAPELLI AS PERAPESOL, PS.PERCOMPAN AS PERCOMSOL, PS.PERCARGO AS PERCARSOL, PS.PERCORREO AS PERCORSOL, PS.PERAVATAR AS PERAVASOL,
						R.REUESTADO,R.REUFECHA,R.REUHORA,R.REUREG,
						R.AGEREG,A.AGETITULO,A.AGELUGAR,
						PD.PERREUURL AS PERREUURLDST,PS.PERREUURL AS PERREUURLSOL,
						M.MESCODIGO,R.REULINK,R.REUTOKSOL,R.REUTOKDST
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=R.PERCODDST
				LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=R.PERCODSOL
				LEFT OUTER JOIN AGE_MAEST A ON A.AGEREG=R.AGEREG
				LEFT OUTER JOIN MES_DISP M ON M.REUREG=R.REUREG
				WHERE $where AND (PERCODDST!=PERCODSOL)
				ORDER BY R.REUESTADO ASC,R.REUFECHA,R.REUHORA,R.REUREG";

$Table = sql_query($query, $conn);
if ($Table->Rows_Count != -1) {

    for ($i = 0; $i < $Table->Rows_Count; $i++) {
        $row = $Table->Rows[$i];
        $percoddst = trim($row['PERCODDST']);
        $pernomdst = trim($row['PERNOMDST']);
        $perapedst = trim($row['PERAPEDST']);
        $percomdst = trim($row['PERCOMDST']);
        $percardst = trim($row['PERCARDST']);
        $percordst = trim($row['PERCORDST']);
        $peravadst = trim($row['PERAVADST']);
        $percodsol = trim($row['PERCODSOL']);
        $pernomsol = trim($row['PERNOMSOL']);
        $perapesol = trim($row['PERAPESOL']);
        $percomsol = trim($row['PERCOMSOL']);
        $percarsol = trim($row['PERCARSOL']);
        $percorsol = trim($row['PERCORSOL']);
        $peravasol = trim($row['PERAVASOL']);
        $agereg = trim($row['AGEREG']);
        $agetitulo = trim($row['AGETITULO']);
        $agelugar = trim($row['AGELUGAR']);
        $reufecha = BDConvFch($row['REUFECHA']);
        $reuhora = substr(trim($row['REUHORA']), 0, 5);
        $reuhoraorig = substr(trim($row['REUHORA']), 0, 5);
        $reuestado = trim($row['REUESTADO']);
        $reureg = trim($row['REUREG']);
        $mescodigo = trim($row['MESCODIGO']);
        $perreuurldst = trim($row['PERREUURLDST']);
        $perreuurlsol = trim($row['PERREUURLSOL']);
        $reulink = trim($row['REULINK']);
        $reutoksol = trim($row['REUTOKSOL']);
        $reutokdst = trim($row['REUTOKDST']);
        $perfil = 0;

        //El perfil que esta viendo el browser es el Destino de las reuniones
        if ($percoddst == $percodlog) {
            $perfil = 1; //Perfil Destino
            $percod = $percodsol;
            $pernombre = $pernomsol;
            $perapelli = $perapesol;
            $percompan = $percomsol;
            $percargo = $percarsol;
            $percorreo = $percorsol;
            $peravatar = $peravasol;
            $perreuurl = $perreuurlsol;
            $reutoken = $reutokdst; //Token de agora
            $reuestconf = 'Recibida';
            $tmpl->setVariable('btnconfirmarstyle', '');

        } else { //El perfil que esta viendo el browser es el Solicitante de las reuniones
            $perfil = 2; //Perfil Solicitante
            $percod = $percoddst;
            $pernombre = $pernomdst;
            $perapelli = $perapedst;
            $percompan = $percomdst;
            $percargo = $percardst;
            $percorreo = $percordst;
            $peravatar = $peravadst;
            $perreuurl = $perreuurldst;

            $reutoken = $reutoksol; //Token de agora
            $reuestconf = 'Enviada';
            $tmpl->setVariable('btnconfirmarstyle', 'display:none;');
        }

        $tmpl->setCurrentBlock('browser');

        if ($reuestado == 2) {
            //Hora segun Zona Horaria
            $haux = date('H:i', strtotime('+10800 seconds', strtotime($reuhora))); //Pongo la hora en Huso horario 0
            $haux = date('H:i', strtotime($_SESSION[GLBAPPPORT . 'TIMOFFSET'] . ' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
            $reuhora = $haux;
            $reuhoraconf = $reuhora;
            $reufechaconf = $reufecha;
            $reuzonaconf = 'Hora Bsas';
            $reuestconf = 'Confirmada';
            $tmpl->setVariable('colorconexion', 'color:green;');
            $reuestdes = "Confirmed meeting in <b>$reufecha</b> at <b>$reuhora</b> <br> Table N° <b>$mescodigo</b> ";
        } else {
            $reuestdes = "Unconfirmed meeting";
            $reuhoraconf = $reuhora;
            $reufechaconf = $reufecha;
            $tmpl->setVariable('colorconexion', 'color:blue;');
            $reuzonaconf = 'Reunion no confirmada';
            if ($reuestado == 3) {
                $reuhoraconf = $reuhora;
                $reufechaconf = $reufecha;
                $reuzonaconf = 'Reunion cancelada';
                $tmpl->setVariable('colorconexion', 'color:red;');
                $reuestconf = 'Cancelada';

            }
            //Busco los horarios solicitados para reunion
            $query = "	SELECT S.REUFECHA,S.REUHORA
						FROM REU_CABE R
						INNER JOIN REU_SOLI S ON S.REUREG=R.REUREG AND R.REUESTADO=S.REUESTADO
						WHERE R.REUREG=$reureg
						ORDER BY S.REUFECHA,S.REUHORA ";

            $TableReu = sql_query($query, $conn);
            for ($j = 0; $j < $TableReu->Rows_Count; $j++) {
                $rowReu = $TableReu->Rows[$j];
                $reufecha = BDConvFch($rowReu['REUFECHA']);
                $reuhora = substr(trim($rowReu['REUHORA']), 0, 5);

                //Hora segun Zona Horaria
                $haux = date('H:i', strtotime('+10800 seconds', strtotime($reuhora))); //Pongo la hora en Huso horario 0
                $haux = date('H:i', strtotime($_SESSION[GLBAPPPORT . 'TIMOFFSET'] . ' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
                $reuhora = $haux;
                $reuhoraconf .= ' ' . $reuhora . '  ';
                $reufechaconf .= ' ' . $reufecha . '  ';
                if ($reuestado != 3) {
                    $reuzonaconf = 'Reunion no confirmada';
                }

                $reuestdes .= '<br> * Date: ' . $reufecha . ' at ' . $reuhora;
            }
        }

        if ($IdiomView == 'ING') {
            $titulocalendar = 'Meeting with ' . $pernombre . ' ' . $perapelli;
            $tmpl->setVariable('titulocalendar', 'Meeting with ' . $pernombre . ' ' . $perapelli);
        } else if ($IdiomView == 'ESP') {
            $titulocalendar = 'Reunión con ' . $pernombre . ' ' . $perapelli;
            $tmpl->setVariable('titulocalendar', 'Reunión con ' . $pernombre . ' ' . $perapelli);
        } else {
            $titulocalendar = 'Reunião  com ' . $pernombre . ' ' . $perapelli;
            $tmpl->setVariable('titulocalendar', 'Reunião  com ' . $pernombre . ' ' . $perapelli);
        }
        $tmpl->setVariable('locationcalendar', URL_WEB);
        if ($IdiomView == 'ESP') {
            $tmpl->setVariable('agregarcalendario', 'Agregar');
        } else if ($IdiomView == 'ING') {
            $tmpl->setVariable('agregarcalendario', 'Add');
        } else {
            $tmpl->setVariable('agregarcalendario', 'Adicionar');
        }

        $tmpl->setVariable('reureg', $reureg);
        $tmpl->setVariable('percodigo', $percod);
        $tmpl->setVariable('pernombre', $pernombre);
        $tmpl->setVariable('perapelli', $perapelli);
        $tmpl->setVariable('percompan', $percompan);
        $tmpl->setVariable('percargo', $percargo);
        $tmpl->setVariable('percorreo', $percorreo);
        $tmpl->setVariable('reuestdes', $reuestdes);
        $tmpl->setVariable('reulink', $reulink);
        $tmpl->setVariable('reuhoraconf', $reuhoraconf);
        $tmpl->setVariable('reufechaconf', $reufechaconf);
        $tmpl->setVariable('reuzonaconf', $reuzonaconf);
        $tmpl->setVariable('percordst', $percordst);
        $tmpl->setVariable('percorsol', $percorsol);
        $tmpl->setVariable('horacalendar', substr($reuhora, 0, 2));
        $tmpl->setVariable('minutoscalendar', $reuhora[3] . $reuhora[4]);

        $time = strtotime($reuhora);
        $horareunionnueva = date("H:i", strtotime('+30 minutes', $time));
        $tmpl->setVariable('minutosfincalendar', $horareunionnueva);

        $fechacalendar = substr($reufecha, 6, 4) . '-' . substr($reufecha, 3, 2) . '-' . substr($reufecha, 0, 2); //Formato calendario (yyyy-mm-dd)
        $tmpl->setVariable('fechacalendar', str_replace('-', '', $fechacalendar));
        $tmpl->setVariable('pernomlog', $pernomlog);
        $tmpl->setVariable('perapelog', $perapelog);
        $tmpl->setVariable('percompanlog', $percompanlog);
        $tmpl->setVariable('percargolog', $percargolog);
        $tmpl->setVariable('reuestconf', $reuestconf);
        if ($peravatarlog != '') {
            $tmpl->setVariable('peravatarlog', $pathimagenes . $percodlog . '/' . $peravatarlog);
        } else {
            $tmpl->setVariable('peravatarlog', $imgAvatarNull);
        }

        //Link de Reunion Externa
        if ($reulink != '') {
            $tmpl->setVariable('reuilnk', $reulink);
        } else {
            $tmpl->setVariable('btnverreulink', 'display:none;');
        }

        //Link de Reuniones de perfil
        if ($perreuurl == '') {
            $tmpl->setVariable('btnvercontact', 'display:none;');
        } else {
            $tmpl->setVariable('perreuurl', $perreuurl);
        }

        if ($peravatar != '') {
            $tmpl->setVariable('peravatar', $pathimagenes . $percod . '/' . $peravatar);
        } else {
            $tmpl->setVariable('peravatar', $imgAvatarNull);
        }

        //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // VIDEOCONFERENCIAS SERVICIO BBB (BigBlueButton) *****************************
        if ($videoconferencia == 'BBB') {
            //$durreunion = intval($params['SisEventoHorarioIntervalo']);
            //$tmpl->setVariable('durreunion'    , $durreunion); //Duracion de Reuniones

            $diaaux = explode('/', $reufecha);
            $diahor = strtotime($diaaux[1] . '/' . $diaaux[0] . '/' . $diaaux[2] . ' ' . $reuhoraorig); //Dia y Hora d
            $hoy = strtotime(date('m/d/Y H:i:s')); //Dia y Hora actual
            $finreunion = strtotime("+$durreunion minutes", $diahor); //Dia y Hora de finalizacion de reunion

            $tmpl->setVariable('btnverreulink', 'display:none;');
            $tmpl->setVariable('btnvercall', 'display:none;');
            $tmpl->setVariable('btnhorasrest', 'display:none;');
            $tmpl->setVariable('btnagendarcal', 'display:none;');
            if ($reuestado == 2) {
                $tmpl->setVariable('btnhorasrest', 'display:;');
                $tmpl->setVariable('btnagendarcal', 'display:;');
            }
            //$arraycharlas[]=['key'=>$diahor,'fin'=>'','value'=>$reureg, 'cant'=>$i+1,'tipo'=>1];
            $arraycharlas[] = ['key' => $diahor, 'fin' => $durreunion, 'value' => $reureg, 'cant' => $contadoractividades + 1, 'tipo' => 1, 'agereg' => $reureg, 'titulocharla' => $titulocalendar . ' - ' . $percompan, 'agehorini' => $reuhoraconf, 'agehorfin' => $agehorfin, 'agefch' => $reufechaconf, 'fechacalendar' => str_replace('-', '', $fechacalendar), 'horacalendar' => substr($reuhora, 0, 2), 'minutoscalendar' => $reuhora[3] . $reuhora[4], 'minutosfincalendar' => $horareunionnueva, 'titulocalendar' => $titulocalendar, 'locationcalendar' => URL_WEB, 'SisNombreEvento' => NAME_TITLE];

        }
        $tmpl->parse('browser');
    }
} else {
    $tmpl->setVariable('displayreuniones', 'd-none');
}

function sortByKey($a, $b)
{
    return $a['key'] > $b['key'];
}

usort($arraycharlas, 'sortByKey'); //$people is now sorted by age (ascending)
//var_dump($arraycharlas);die;

$myJSONcharlas = json_encode($arraycharlas);
$tmpl->setvariable('arraycharlas', $myJSONcharlas);

//////////// IMAGENES /////////////////////

$pathimagenes = '/admimg/';
$imgBannerHomeNull = '../assets-nuevodisenio/img/bannerhome.jpg';
$tmpl->setVariable('imgProductoNull', $imgBannerHomeNull);

$countactive = false;
$pausective = false;
$query = "	SELECT *
		 FROM ADM_IMG
		 WHERE ESTCODIGO=1 AND (BANID>1 AND BANID<6)";

$Table = sql_query($query, $conn);

for ($i = 0; $i < $Table->Rows_Count; $i++) {
    $row = $Table->Rows[$i];

    $bannerhomeimg = trim($row['BANNERS']);
    $urlbanner = trim($row['URLBAN']);
    $estcodigo = trim($row['ESTCODIGO']);

    if ($bannerhomeimg == '') {
        $bannerhomeimg = $imgBannerHomeNull;
    } else {
        $bannerhomeimg = $pathimagenes . $bannerhomeimg;
    }
    $tmpl->setCurrentBlock('browserpause');
    $tmpl->setVariable('bannerid', $i);
    if ($pausective == false) {
        $pausective = true;
        $tmpl->setVariable('activepause', 'active');
    } else {
        $tmpl->setVariable('activepause', '');
    }
    $tmpl->parse('browserpause');
    $tmpl->setCurrentBlock('browserbanners');
    $tmpl->setVariable('bannerhomeimg', $bannerhomeimg);
    $tmpl->setVariable('urlbanner', $urlbanner);
    $tmpl->setVariable('urltarget', '_blank');

    if ($countactive == false) {
        $countactive = true;
        $tmpl->setVariable('activebanner', 'active');
    } else {
        $tmpl->setVariable('activobanner', '');
    }

    $tmpl->parse('browserbanners');
}

/// POP UP INICIO
$query = "	SELECT PERPOP2
		 FROM PER_MAEST
		 WHERE PERPOP2=0 AND PERCODIGO=$percodigo";

$Table = sql_query($query, $conn);

if ($Table->Rows_Count > 0) {

    $queryinfo = "	SELECT POP_URL
    FROM ADM_POP WHERE POP_REG=0";

$Tableinfo = sql_query($queryinfo,$conn);


if($Tableinfo->Rows_Count>0){
$rowinfo = $Tableinfo->Rows[0];

$popupinfo 	= trim($rowinfo['POP_URL']);

$arrayinfo = explode(',',$popupinfo);


if ($arrayinfo[0] === '1'){

   
    if ( ($arrayinfo[1] === '0') && ($arrayinfo[2] === '0') && ($arrayinfo[3] === '0') )
    {
        $tmpl->setVariable('activepopupinicio', '');
    }else{
       
        $tmpl->setVariable('activepopupinicio', 'showPopUpInicio();');
    }

   
}else{
    $tmpl->setVariable('activepopupinicio', '');
}

}

       
   
}else {
    $tmpl->setVariable('activepopupinicio', '');
}


///POP UP
$query = "	SELECT PERPOP
		 FROM PER_MAEST
		 WHERE PERPOP=0 AND PERCODIGO=$percodigo";

$Table = sql_query($query, $conn);

if ($Table->Rows_Count > 0) {

    $querypopup = "	SELECT ESTCODIGO
		 FROM ADM_POP
		 WHERE ESTCODIGO=1 AND POP_REG=1";

    $Tablepopup = sql_query($querypopup, $conn);

    if ($Tablepopup->Rows_Count > 0) {

        $tmpl->setVariable('activepopup', 'showPopUp();');
    } else {
        $tmpl->setVariable('activepopup', '');
    }
} else {
    $tmpl->setVariable('activepopup', '');
}

//busco los accesos directos en la tabla
$query = "	SELECT *
				FROM ACC_MAEST
                WHERE ESTCODIGO<>3";
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
    $row = $Table->Rows[$i];
    $accreg = trim($row['ACCREG']);
    $acctitulo = trim($row['ACCTITULO']);
    $accmostrar = trim($row['ACCMOSTRAR']);
    $acchref = trim($row['ACCHREF']);
    $accicono = trim($row['ACCICONO']);

    $tmpl->setCurrentBlock('accesos');
   //var_dump($acctitulo);
    $tmpl->setVariable('accreg', $accreg);
    if($accreg<9){
        $tmpl->setVariable('acctitulo'	, '{'.$acctitulo.'}');
    }else{
        $tmpl->setVariable('acctitulo'	, $acctitulo);
    }
    $tmpl->setVariable('accmostrar', $accmostrar == 'true' ? '' : 'd-none');
    if ($acchref == 'onclick'){

              
        $query3ayuda = "	SELECT PERCODIGO
            FROM ADM_AYUDA
            WHERE AYU_ID=0";

          $Table3ayuda = sql_query($query3ayuda,$conn);

         if($Table3ayuda->Rows_Count>0){
             $row3ayuda = $Table3ayuda->Rows[0];
             $ayuperfil 	= trim($row3ayuda['PERCODIGO']);
         }else{
            $ayuperfil = 0;
         }

        

        $tmpl->setVariable('acchref', 'onclick=showChatView('.$ayuperfil.',1);');
    }else{

        $tmpl->setVariable('acchref', 'href="' . $acchref . '"' );

    }
    
    $tmpl->setVariable('accicono', $accicono);
    $tmpl->parse('accesos');
}

sql_close($conn);

DDIdioma($tmpl);

$tmpl->show();
