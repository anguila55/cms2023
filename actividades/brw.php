<?php include('../val/valuser.php');

?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/constants.php';
require_once GLBRutaFUNC.'/idioma.php';//Idioma

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brw.html');

DDIdioma($tmpl);
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------

$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$fecha = (isset($_POST['fecha']))? trim($_POST['fecha']):'';

$conn = sql_conectar(); //Apertura de Conexion
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



$hoy = date('Y-m-d');
$tmpl->setVariable('hoy', $inicio);


$coloReunion	= '#967ADC';
$colorBloqueado	= '#FFAD8F';



//Habilito las opciones del Menu
if (json_decode($_SESSION['PARAMETROS']['MenuActividades']) == false) {
	$tmpl->setVariable('ParamMenuActividades', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuAgenda']) == false) {
	$tmpl->setVariable('ParamMenuAgenda', 'display:none;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuMensajes']) == false) {
	$tmpl->setVariable('ParamMenuMensajes', 'display:none;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuNoticias']) == false) {
	$tmpl->setVariable('ParamMenuNoticias', 'display:none;');
}


$diaInicial =  substr($diasini, 0, 2) + 1 - 1;
$finalEvento =  $diasdur +  $diaInicial;
$contadorDias = 0;
$where 			= '';
$active 		= 'active';

//query salas
 $query = "SELECT CATDESCRI
				FROM AGE_CAT
				WHERE ESTCODIGO<>3  
				ORDER BY CATDESCRI";
				
				
				$Table = sql_query($query,$conn);

$salas[]='Ver Todo';
for($i=0; $i<$Table->Rows_Count; $i++){
	$row = $Table->Rows[$i];

	$sala 	= trim($row['CATDESCRI']);
	$salas[]=$sala;
}


if(count($salas) == 1){
	$tmpl->setVariable('viewsalas', 'd-none');
}
//$salas = ['Ver Todo','Room 1','Room 2'];

for ($i = 0; $i < count($salas); $i++) {

	
	$tmpl->setCurrentBlock('tabs');
	$tmpl->setVariable('active', $active);

	$tmpl->setVariable('dia', $salas[$i]);
	$tmpl->setVariable('id', str_replace(' ', '', $salas[$i]));
	$tmpl->parse('tabs');


	$hoy;

	$sala = $salas[$i];

	if ($i!=0){
		$where= "AND AGELUGAR = '$sala'";
	}

	$query = "SELECT AGEREG, AGEFCH, AGETITULO, AGEDESCRI, AGEHORINI, AGEHORFIN, AGELUGAR,SPKREG,AGEYOULNK, AGETITING,AGEDESING,QRCODE
				FROM AGE_MAEST
				WHERE ESTCODIGO<>3 AND AGEFCH='$fecha' $where
				ORDER BY AGEFCH,AGEHORINI ";


	$Table = sql_query($query, $conn);


	$tmpl->setCurrentBlock('dias');
	$tmpl->setVariable('active', $active);
	$tmpl->setVariable('sala',  str_replace(' ', '', $salas[$i]));

	if ($Table->Rows_Count != -1) {
		for ($u = 0; $u < $Table->Rows_Count; $u++) {

			$row = $Table->Rows[$u];
			$agereg 	= trim($row['AGEREG']);
			
			$agelugar   = trim($row['AGELUGAR']);
			$spkreg   	= trim($row['SPKREG']);
			$agefch     = BDConvFch($row['AGEFCH']);
			$ageyoulnk 	= trim($row['AGEYOULNK']);

			$agehorini  = substr(trim($row['AGEHORINI']), 0, 5);
			
			$agehorfin  = substr(trim($row['AGEHORFIN']), 0, 5);

			///CAMBIOS DE HORARIOS 

			
			// $agehorini = new DateTime($agehorini, new DateTimeZone('America/Argentina/Buenos_Aires'));
			// $agehorini->setTimezone(new DateTimeZone($nombreZh));
            // $agehorini = $agehorini->format('H:i') ;

			// $agehorfin = new DateTime($agehorfin, new DateTimeZone('America/Argentina/Buenos_Aires'));
			// $agehorfin->setTimezone(new DateTimeZone($nombreZh));
            // $agehorfin = $agehorfin->format('H:i') ;



		 	   $haux = date('H:i', strtotime('+10800 seconds', strtotime($agehorini))); //Pongo la hora en Huso horario 0
			   $haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
			   $agehorini = $haux;
			
			   $haux2 = date('H:i', strtotime('+10800 seconds', strtotime($agehorfin))); //Pongo la hora en Huso horario 0
			   $haux2 = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux2))); //Pongo la hora, segun el Huso horario establecido por el perfil
			   $agehorfin = $haux2;
			

			




			$agefch	= substr($agefch, 6, 4) . '-' . substr($agefch, 3, 2) . '-' . substr($agefch, 0, 2); //Formato calendario (yyyy-mm-dd)
			
			
			//$agehorini = ($agehorini != '') ? 'T' . $agehorini : '';
			//$agehorfin = ($agehorfin != '') ? 'T' . $agehorfin : '';
			$qrreg 	= trim($row['QRCODE']);
			

			if (($IdiomView=='ING')){

				$agetitulo 	= trim($row['AGETITING']);
				$agedescri 	= trim($row['AGEDESING']);
				$tmpl->setVariable('Idioma_Descripcion'		, 'Description'); 
				$tmpl->setVariable('Idioma_Ingresar'		, 'Enter'); 
				$tmpl->setVariable('Idioma_Agregar'		, 'Add'); 
			}else{
				$agetitulo 	= trim($row['AGETITULO']);
				$agedescri 	= trim($row['AGEDESCRI']);
				$tmpl->setVariable('Idioma_Descripcion'		, 'Descripción');
				$tmpl->setVariable('Idioma_Ingresar'		, 'Ingresar'); 
				$tmpl->setVariable('Idioma_Agregar'		, 'Agregar'); 
			}



			$ahora = date('H:i');
			$horaCharla = substr(trim($agehorini), 1, 2);
			$minutosCharla = substr(trim($agehorini), 4, 5);
			$minutosFinCharla = substr(trim($agehorfin), 4, 5);
			$horaFinCharla = substr(trim($agehorfin), 1, 2);
			$ahoraMinutos = substr($ahora, 3, 5);
			$ahoraHora = substr($ahora, 0, 2);
			
			//CHEQUEO FAVORITO
			$queryFav = "SELECT * FROM REU_CABE WHERE PERCODSOL = $percodigo AND AGEREG = $agereg ";
			$TableFav = sql_query($queryFav, $conn);
			$tmpl->setCurrentBlock('actividades');

			if ($ageyoulnk != '') {
				$tmpl->setVariable('video', 'd-flex d-block');
				$tmpl->setVariable('ageyoulnk', $ageyoulnk);
				$tmpl->setVariable('verageopc', 1);
				
				/*
				//Verifico si el perfil posee acceso a esta cuenta, para mostrar mensaje
				$queryTik = "SELECT PERTIKREG FROM PER_TICK_SALD WHERE PERCODIGO=$percodigo AND AGEREG=$agereg  ";
				$TableTik = sql_query($queryTik, $conn);
				if($TableTik->Rows_Count>0){ //Posee un ticket para ver la charla
					$tmpl->setVariable('verageopc', 1);
				}*/
				
			}else{
				$tmpl->setVariable('video', 'd-none');

			}

			//colorfavo
			if ($TableFav->Rows_Count != -1) {
				$tmpl->setVariable('agendado', 'fa-star');
			} else {
				$tmpl->setVariable('agendado', 'fa-star-o');
			}
			$spkreglen = strlen($spkreg);

				$prueba  =  explode(',',$spkreg);
				$count = 0;
				foreach ($prueba as $key => $value) {
	
					if($value != 0){
						$queryspk = "	SELECT SPKREG, SPKNOMBRE, SPKDESCRI, SPKIMG, SPKPOS, ESTCODIGO,SPKEMPRES,SPKCARGO
							FROM SPK_MAEST
							WHERE SPKREG=$value";
							
						$Tablespk = sql_query($queryspk, $conn);
						if ($Tablespk->Rows_Count > 0) {
	
							$rowspk = $Tablespk->Rows[0];
							$spkimg  	= trim($rowspk['SPKIMG']);
							$spkregnew  	= trim($rowspk['SPKREG']);
							$spknombre  	= trim($rowspk['SPKNOMBRE']);
							$spkempres  	= trim($rowspk['SPKEMPRES']);
							$spkcargo  	= trim($rowspk['SPKCARGO']);
	
							
							$tmpl->setCurrentBlock('spkimg');
						
							$tmpl->setVariable('spkimg', $spkimg);
							$tmpl->setVariable('skpnombre', $spknombre);
							$tmpl->setVariable('skpempres', $spkempres);
							$tmpl->setVariable('skpcargo', $spkcargo);
	
							if($count == 0){
								$tmpl->setVariable('imagespk', '50px');
							}else{
								$tmpl->setVariable('imagespk', '50px');
								
							}
							$count =1;
							$tmpl->setVariable('spkreg', $spkregnew);;
							$tmpl->parse('spkimg');
	
						}
					}
				}
			$tmpl->setVariable('display', 'flex');
			$tmpl->setVariable('agereg', $agereg);
			$tmpl->setVariable('active', $active);
			$tmpl->setVariable('agetitulo', $agetitulo);
			$tmpl->setVariable('agedescri',nl2br($agedescri)  );
			$tmpl->setvariable('agelugar', $agelugar);
			if ($i!=0){
				$tmpl->setvariable('displayagelugar', 'd-none');
			}
			$tmpl->setvariable('agefch', $agefch);
			$tmpl->setVariable('locationcalendar', URL_WEB );
			$tmpl->setVariable('qrreg', $qrreg );
			$tmpl->setVariable('agehorini', $agefch . $agehorini);
			//$tmpl->setVariable('hora', substr(trim($row['AGEHORINI']), 0, 5));
			//$tmpl->setVariable('agehorfin', substr(trim($row['AGEHORFIN']), 0, 5));
			$tmpl->setVariable('hora', $agehorini);
			$tmpl->setVariable('agehorfin', $agehorfin);
			//Cambios en la botonera
			//si esta en vivo
			//$tmpl->setVariable('displayBotonVideo', 'none');
			//$tmpl->setVariable('displayBotonVivo', 'none');
			//$currentDay = date('d');
			//dia actual con dia de la charla
			//if ($currentDay ==  $i) {
				// if ($ahoraHora == $horaCharla && $ahoraMinutos >= $minutosCharla && $ahoraMinutos <= $minutosFinCharla) {
				// 	$tmpl->setVariable('displayBotonVivo', 'bock');
				// }
				// //si la reunion ya paso
				// if ($ahoraHora >= $horaFinCharla && $ahoraMinutos  > $minutosFinCharla) {
				// 	$tmpl->setVariable('displayBotonVideo', 'block');
				// }
		//	}
			$tmpl->parse('actividades');
			
		}
	} else {
		$tmpl->setCurrentBlock('actividades');
		$tmpl->setVariable('msg', 'No events for this room');
		$tmpl->setVariable('display', 'none');
		$tmpl->setVariable('nohayactividades', 'd-none');

		$tmpl->parse('actividades');
	}
	$tmpl->parse('dias');
	$active = '';
	$contadorDias++;
}

$query2 = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoEvento'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$tipoevento = trim($row['ZVALUE']);
	if ($tipoevento != 'false') {

		$tmpl->setVariable('mostrarqr', 'd-flex');
	} else {
		$tmpl->setVariable('mostrarqr', 'd-none');
	}
}











sql_close($conn);
$tmpl->show();

?>	