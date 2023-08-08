<?php 
if (!isset($_SESSION))  session_start();
// include($_SERVER["DOCUMENT_ROOT"] . '/congresoaapresid/func/zglobals.php'); //DEV
include($_SERVER["DOCUMENT_ROOT"] . '/func/zglobals.php'); //PRD
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('cronograma.html');
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------

$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';

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
$where 	= '';


// //Habilito las opciones del Menu
// if (json_decode($_SESSION['PARAMETROS']['MenuActividades']) == false) {
// 	$tmpl->setVariable('ParamMenuActividades', 'display:;');
// }
// if (json_decode($_SESSION['PARAMETROS']['MenuAgenda']) == false) {
// 	$tmpl->setVariable('ParamMenuAgenda', 'display:none;');
// }
// if (json_decode($_SESSION['PARAMETROS']['MenuMensajes']) == false) {
// 	$tmpl->setVariable('ParamMenuMensajes', 'display:none;');
// }
// if (json_decode($_SESSION['PARAMETROS']['MenuNoticias']) == false) {
// 	$tmpl->setVariable('ParamMenuNoticias', 'display:none;');
// }




//--------------------------------------------------------------------------------------------------------------
//-----Seleccionamos los datos de la base de datos
// $query="SELECT AGEREG, AGEFCH, AGETITULO, AGEDESCRI, AGEHORINI, AGEHORFIN, AGELUGAR 
// 		FROM AGE_MAEST
// 		WHERE ESTCODIGO<>3";


// $Table = sql_query($query,$conn);
// for($i=0; $i<$Table->Rows_Count; $i++){
// 	$row = $Table->Rows[$i];
// 	$agereg 	= trim($row['AGEREG']);
// 	$agetitulo 	= trim($row['AGETITULO']);
// 	$agedescri 	= trim($row['AGEDESCRI']);
// 	$agelugar   = trim($row['AGELUGAR']);
// 	$agefch     = BDConvFch($row['AGEFCH']);
// 	$agehorini  = substr(trim($row['AGEHORINI']),0,5);
// 	$agehorfin  = substr(trim($row['AGEHORFIN']),0,5);

// 	$agefch	= substr($agefch,6,4).'-'.substr($agefch,3,2).'-'.substr($agefch,0,2); //Formato calendario (yyyy-mm-dd)
// 	$agehorini = ($agehorini!='')? 'T'.$agehorini: '';
// 	$agehorfin = ($agehorfin!='')? 'T'.$agehorfin: '';

// 	$tmpl->setCurrentBlock('actividadaes');
// 	$tmpl->setVariable('agereg'		, $agereg);
// 	$tmpl->setVariable('agetitulo'	, $agetitulo);
// 	$tmpl->setVariable('agedescri'	, $agedescri);
// 	$tmpl->setvariable('agelugar'	, $agelugar);
// 	$tmpl->setVariable('agehorini'	, $agefch.$agehorini);
// 	$tmpl->setVariable('agehorfin'	, $agefch.$agehorfin);
// 	$tmpl->setVariable('color'		, $coloReunion);
// 	$tmpl->parse('actividadaes');

// }


$active = 'active';

$diaInicial =  substr($diasini, 0, 2) + 1 - 1;
$finalEvento =  $diasdur +  $diaInicial;
$contadorDias = 0;
$salas = ['Sala Plenaria 1 CORTEVA','Sala Plenaria 2 CORTEVA ','Sala Plenaria 3 - HB4', 'CQ1 - FASTAC DUO','CQ2 - CREDENZ','CQ3 - ZIDUA','CQ4 - EXPEDITION','CQ5 -  ENLIST COLEX-D'];


// $salas=['Sala1','Sala2','Sala3','Sala4','Sala5','Sala6','Sala7','Sala8',];

// for ($o=0; $o <count($salas) ; $o++) { 
//         $tmpl->setCurrentBlock('salas1');
//         $tmpl->setVariable('active1', $active);

//         $tmpl->setVariable('sala1', $salas[$o]);
//         logerror($salas[$o]);
//         $tmpl->setVariable('id1', str_replace(' ', '', $salas[$o]));
//         $tmpl->parse('salas1');
// }
for ($i = 0; $i < count($salas); $i++) {
    
    
    
    

    $tmpl->setCurrentBlock('tabs');
	$tmpl->setVariable('active', $active);
    
	$tmpl->setVariable('dia', $salas[$i]);
	$tmpl->setVariable('id', str_replace(' ', '', $salas[$i]));
	$tmpl->parse('tabs');
    
 


	$hoy;

	$sala = $salas[$i];
	$query = "SELECT AGEREG, AGEFCH, AGETITULO, AGEDESCRI, AGEHORINI, AGEHORFIN, AGELUGAR,SPKREG
				FROM AGE_MAEST
				WHERE ESTCODIGO <> 3 AND   AGELUGAR = '$sala'";


	$Table = sql_query($query, $conn);


	$tmpl->setCurrentBlock('dias');
	$tmpl->setVariable('active', $active);
	$tmpl->setVariable('sala',  str_replace(' ', '', $salas[$i]));

	if ($Table->Rows_Count != -1) {
		for ($u = 0; $u < $Table->Rows_Count; $u++) {

			$row = $Table->Rows[$u];
			$agereg 	= trim($row['AGEREG']);
			$agetitulo 	= trim($row['AGETITULO']);
			$agedescri 	= trim($row['AGEDESCRI']);
			$agelugar   = trim($row['AGELUGAR']);
			$spkreg   = trim($row['SPKREG']);
			$agefch     = BDConvFch($row['AGEFCH']);
			$agehorini  = substr(trim($row['AGEHORINI']), 0, 5);
			$agehorfin  = substr(trim($row['AGEHORFIN']), 0, 5);

			$agefch	= substr($agefch, 6, 4) . '-' . substr($agefch, 3, 2) . '-' . substr($agefch, 0, 2); //Formato calendario (yyyy-mm-dd)
			$agehorini = ($agehorini != '') ? 'T' . $agehorini : '';
			$agehorfin = ($agehorfin != '') ? 'T' . $agehorfin : '';
			$ahora = date('H:i');
			$horaCharla = substr(trim($agehorini), 1, 2);
			$minutosCharla = substr(trim($agehorini), 4, 5);
			$minutosFinCharla = substr(trim($agehorfin), 4, 5);
			$horaFinCharla = substr(trim($agehorfin), 1, 2);
			$ahoraMinutos = substr($ahora, 3, 5);
			$ahoraHora = substr($ahora, 0, 2);
			
			// //CHEQUEO FAVORITO
			// $queryFav = "SELECT * FROM REU_CABE WHERE PERCODSOL = $percodigo AND AGEREG = $agereg ";
			// $TableFav = sql_query($queryFav, $conn);
			// $tmpl->setCurrentBlock('actividades');
			// //colorfavo
			// if ($TableFav->Rows_Count != -1) {
			// 	$tmpl->setVariable('agendado', 'fa-star');
			// } else {
			// 	$tmpl->setVariable('agendado', 'fa-star-o');
			// }
			$spkreglen = strlen($spkreg);

 

			$prueba  =  explode(',',$spkreg);
		


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
						$tmpl->setCurrentBlock('spkimg');
						$tmpl->setVariable('spkimg', $spkimg);
						$tmpl->setVariable('spkreg', $spkregnew);;
						$tmpl->parse('spkimg');

					}
				}
			}
		
			
			$tmpl->setVariable('display', 'flex');
			$tmpl->setVariable('agereg', $agereg);
			$tmpl->setVariable('active', $active);
			$tmpl->setVariable('agetitulo', $agetitulo);
			$tmpl->setVariable('agedescri', $agedescri);
			$tmpl->setvariable('agelugar', $agelugar);
			$tmpl->setVariable('agehorini', $agefch . $agehorini);
			$tmpl->setVariable('hora', substr(trim($row['AGEHORINI']), 0, 5));
			$tmpl->setVariable('agehorfin', substr(trim($row['AGEHORFIN']), 0, 5));
			//Cambios en la botonera
			//si esta en vivo
			$tmpl->setVariable('displayBotonVideo', 'none');
			$tmpl->setVariable('displayBotonVivo', 'none');
			$currentDay = date('d');
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
		$tmpl->setVariable('msg', 'No se encontraron eventos para esta sala');
		$tmpl->setVariable('display', 'none');

		$tmpl->parse('actividades');
	}
	$tmpl->parse('dias');
	$active = '';
	$contadorDias++;
}



sql_close($conn);
$tmpl->show();

?>