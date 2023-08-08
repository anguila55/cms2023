<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC.'/constants.php';
require_once GLBRutaAPI  . '/timezone.php';

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('index.html');

//--------------------------------------------------------------------------------------------------------------



$conn = sql_conectar(); //Apertura de Conexion
$peradmin  = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';
$tmpl->setVariable('SisNombreEvento', NAME_TITLE );
if ($peradmin!=1){
	$tmpl->setVariable('displayadminlanding', 'd-none' );
}
//variables utiles

$pathimagenes = '../expimg/';

$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'SisCorreoLinkAppStore'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$evefch = date('F j, Y', strtotime(trim($row['ZDESCRI'])));
	$tmpl->setVariable('evefchinicio', $evefch);
	
}
$query = "	SELECT BANNERS
			FROM ADM_IMG WHERE BANID='10'";

$Table = sql_query($query,$conn);

for($i=0; $i<$Table->Rows_Count; $i++){
	$row = $Table->Rows[$i];
	$file 	= trim($row['BANNERS']);
	$tmpl->setVariable('file', $file);

}
$query = "	SELECT ID_SECCION,NOM_SEC, TIT_SUP, TITULO, DESCRI, OPCIONAL1, OPCIONAL2,OPCIONAL3,OPCIONAL4,IMAGEN,ESTCODIGO
					FROM TBL_LANDING";

	$Table = sql_query($query, $conn);
	if ($Table->Rows_Count > 0) {
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$id_seccion 	= trim($row['ID_SECCION']);
			$nom_sec 	= trim($row['NOM_SEC']);
			$tit_sup 	= trim($row['TIT_SUP']);
			$titulo 	= trim($row['TITULO']);
			$descri  	= trim($row['DESCRI']);
			$opcional1 	= trim($row['OPCIONAL1']);
			$opcional2 	= trim($row['OPCIONAL2']);
			$opcional3 	= trim($row['OPCIONAL3']);
			$opcional4 	= trim($row['OPCIONAL4']);
			$imagen 	= trim($row['IMAGEN']);
			$estcodigo 	= trim($row['ESTCODIGO']);


			$tmpl->setVariable('id_seccion_'.$id_seccion, $id_seccion);
			$tmpl->setVariable('nom_sec_'.$id_seccion, $nom_sec);
			$tmpl->setVariable('tit_sup_'.$id_seccion, $tit_sup);
			$tmpl->setVariable('titulo_'.$id_seccion, $titulo);
			$tmpl->setVariable('descri_'.$id_seccion, nl2br($descri));
			$tmpl->setVariable('opcional1_'.$id_seccion, $opcional1);
			$tmpl->setVariable('opcional2_'.$id_seccion, $opcional2);
			$tmpl->setVariable('opcional3_'.$id_seccion, $opcional3);
			$tmpl->setVariable('opcional4_'.$id_seccion, $opcional4);
			$tmpl->setVariable('imagen_'.$id_seccion, '../../landing/landingimg/'.$id_seccion.'/'.$imagen);
			$tmpl->setVariable('estcodigo_'.$id_seccion, $estcodigo);
			$tmpl->setVariable('displayseccion_'.$id_seccion, '');
			if ($id_seccion==11){
				$tmpl->setVariable('displayopcional1', '');
				$tmpl->setVariable('displayopcional2', '');
				$tmpl->setVariable('displayopcional3', '');
				$tmpl->setVariable('displayopcional4', '');
				if ($opcional1==''){
					$tmpl->setVariable('displayopcional1', 'd-none');
				}
				if ($opcional2==''){
					$tmpl->setVariable('displayopcional2', 'd-none');
				}
				if ($opcional3==''){
					$tmpl->setVariable('displayopcional3', 'd-none');
				}
				if ($opcional4==''){
					$tmpl->setVariable('displayopcional4', 'd-none');
				}
			}
			if ($estcodigo == 3) {
				$tmpl->setVariable('displayseccion_'.$id_seccion, 'd-none');
			}
		}
	}
//Query para eventos
$active = 'active';

$query = "SELECT DISTINCT  AGEFCH
		FROM AGE_MAEST  
		WHERE ESTCODIGO<>3 $where";
$fechas = null;
$Table = sql_query($query, $conn);

for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$agefch     = BDConvFch($row['AGEFCH']);
	$agefch	= substr($agefch, 6, 4) . '-' . substr($agefch, 3, 2) . '-' . substr($agefch, 0, 2);
	$date = DateTime::createFromFormat("Y-m-d", $agefch);


	$tmpl->setCurrentBlock('agenda');
	$tmpl->setVariable('agefch', utf8_encode(strftime("%A %d %B", $date->getTimestamp())));
	$tmpl->setVariable('contador', $i + 1);
	$tmpl->setVariable('active2', $active);
	$active = '';
	$fechas[] = $agefch;
	$tmpl->setVariable('idfecha', 'tab' . str_replace(' ', '', $fechas[$i]));

	$tmpl->parse('agenda');
}



for ($i = 0; $i < count($fechas); $i++) {

	$tmpl->setCurrentBlock('dias');

	if ($i == 0) {
		$tmpl->setVariable('active', 'show active');
	} else {
		$tmpl->setVariable('active', '');
	}


	$tmpl->setVariable('fecha', 'tab' . str_replace(' ', '', $fechas[$i]));

	$ipaddress = '';
	if (isset($_SERVER['HTTP_CLIENT_IP']))
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_X_FORWARDED']))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_FORWARDED']))
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if(isset($_SERVER['REMOTE_ADDR']))
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	else
		$ipaddress = 'UNKNOWN';
	$timezoneip=getTimeZoneIp($ipaddress);
	$timoffset=strval(getTimeZone($timezoneip));

	$query2 = "SELECT  AGEHORINI,AGEHORFIN, AGETITULO, AGEDESCRI, AGELUGAR
				FROM AGE_MAEST
				WHERE ESTCODIGO<>3 AND AGEFCH='$fechas[$i]'
				ORDER BY AGEFCH,AGEHORINI";
	$Table2 = sql_query($query2, $conn);


	for ($j = 0; $j < $Table2->Rows_Count; $j++) {
		$row2 = $Table2->Rows[$j];

		$agehorini  = substr(trim($row2['AGEHORINI']), 0, 5);
		$agehorfin  = substr(trim($row2['AGEHORFIN']), 0, 5);
		$agetitulo 	= trim($row2['AGETITULO']);
		$agedescri 	= trim($row2['AGEDESCRI']);
		$agelugar   = trim($row2['AGELUGAR']);


		$haux = date('H:i', strtotime('+10800 seconds', strtotime($agehorini))); //Pongo la hora en Huso horario 0
		$haux = date('H:i', strtotime($timoffset.' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
		$agehorini = $haux;
	 
		$haux2 = date('H:i', strtotime('+10800 seconds', strtotime($agehorfin))); //Pongo la hora en Huso horario 0
		$haux2 = date('H:i', strtotime($timoffset.' seconds', strtotime($haux2))); //Pongo la hora, segun el Huso horario establecido por el perfil
		$agehorfin = $haux2;


		$tmpl->setCurrentBlock('actividades');
		$tmpl->setVariable('hora', $agehorini);
		$tmpl->setVariable('agehorfin', $agehorfin);
		$tmpl->setVariable('agetitulo', $agetitulo);
		$tmpl->setVariable('agedescri', nl2br($agedescri));
		$tmpl->setvariable('agelugar', $agelugar);
		$tmpl->parse('actividades');
	}
	$tmpl->parse('dias');
}


//Query para oradores
$query = "	SELECT FIRST 8 *
				FROM SPK_MAEST
				WHERE ESTCODIGO<>3 $where
				ORDER BY SPKPOS,SPKNOMBRE";


//logerror($query);
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$spkreg 	= trim($row['SPKREG']);
	$spktitulo 	= trim($row['SPKNOMBRE']);
	$spkdescri  = trim($row['SPKDESCRI']);
	$spkpos     = trim($row['SPKPOS']);
	$spkempres     = trim($row['SPKEMPRES']);
	$spkimg     = trim($row['SPKIMG']);
	$spkcargo     = trim($row['SPKCARGO']);
	$spklinked     = trim($row['SPKLINKED']);
	$spkins     = trim($row['SPKINS']);
	$spkfac     = trim($row['SPKFAC']);
	$spktwi     = trim($row['SPKTWI']);
    $mostrarlinked = "";
	$mostrarfac = "";
	$mostrarins = "";
	$mostrartwi = "";
	//$aviimagen  = trim($row['AVIIMAGEN']);

	if ($spklinked == "") {
		$spklinked = "";
		$mostrarlinked = "d-none";
	} else {
		$spklinked = "href='" . $spklinked . "'";
	}
	if ($spkfac == "") {
		$spkfac = "";
		$mostrarfac = "d-none";
	} else {
		$spkfac = "href='" . $spkfac . "'";
	}
	if ($spkins == "") {
		$spkins = "";
		$mostrarins = "d-none";
	} else {
		$spkins = "href='" . $spkins . "'";
	}
	if ($spktwi == "") {
		$spktwi = "";
		$mostrartwi = "d-none";
	} else {
		$spktwi = "href='" . $spktwi . "'";
	}

	$tmpl->setCurrentBlock('speakers');
	$tmpl->setVariable('spkreg', $spkreg);
	$tmpl->setVariable('spktitulo', $spktitulo);
	$tmpl->setVariable('spkdescri', $spkdescri);
	$tmpl->setVariable('spkempres', $spkempres);
	$tmpl->setVariable('spkcargo', $spkcargo);
	$tmpl->setVariable('spkimg', '../spkimg/' . $spkreg . '/' . $spkimg);
	$tmpl->setvariable('spkpos', $spkpos);
	$tmpl->setvariable('spklinked', $spklinked);
	$tmpl->setvariable('spkins', $spkins);
	$tmpl->setvariable('spkfac', $spkfac);
	$tmpl->setvariable('spktwi', $spktwi);
	$tmpl->setvariable('mostrarlinked', $mostrarlinked);
	$tmpl->setvariable('mostrarins', $mostrarins);
	$tmpl->setvariable('mostrarfac', $mostrarfac);
	$tmpl->setvariable('mostrartwi', $mostrartwi);

	$oradores[] = $spkreg;
	//$tmpl->setvariable('aviimagen',$aviimagen);
	$tmpl->parse('speakers');

	$tmpl->setvariable('totaloradores', count($oradores));
}
//query para sponsors

$query = "SELECT DISTINCT C.CATDESCRI, C.CATREG
FROM EXP_MAEST AS E
LEFT JOIN EXP_CAT AS C ON  C.CATREG = E.EXPCATEGO
WHERE ESTCODIGO<>3 ORDER BY C.CATVALOR ASC";


$cateogorias = null;

$Table = sql_query($query, $conn);
$categoriasreg = null;


for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];

	$catdescri = trim($row['CATDESCRI']);
	$catreg = trim($row['CATREG']);

	$categoriasreg[] = $catreg;
	$categoriasdescri[] = $catdescri;
}




$Table = sql_query($query, $conn);

for ($i = 0; $i < count($categoriasreg); $i++) {

	$tmpl->setCurrentBlock('categorias');
	$tmpl->setVariable('categoria', $categoriasdescri[$i]);

	$query = "SELECT EXPNOMBRE, EXPAVATAR, EXPREG
	FROM EXP_MAEST
	WHERE ESTCODIGO<>3 AND EXPCATEGO='$categoriasreg[$i]' $where";
	$Table2 = sql_query($query, $conn);


	for ($j = 0; $j < $Table2->Rows_Count; $j++) {
		$row2 = $Table2->Rows[$j];
		$expnombre 	= trim($row2['EXPNOMBRE']);
		$expavatar 	= trim($row2['EXPAVATAR']);
		$expreg 	= trim($row2['EXPREG']);

		$tmpl->setCurrentBlock('expositores');
		$tmpl->setVariable('expnombre', $expnombre);
		$tmpl->setVariable('expavatar', '../expimg/' . $expreg . '/' . $expavatar);
		$tmpl->setVariable('expreg', $expreg);
		$sponsors[] = $expreg;
		$tmpl->parse('expositores');
		$tmpl->setVariable('totalsponsors', count($sponsors));
	}

	$tmpl->parse('categorias');
}

//query perfiles

$query = "SELECT COUNT(PERCODIGO) AS TOTALPERFILES
				FROM PER_MAEST";

$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$totalperfiles 	= trim($row['TOTALPERFILES']);

	$tmpl->setVariable('totalperfiles', $totalperfiles);
}

//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>