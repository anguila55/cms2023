<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	


$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('mst.html');
DDIdioma($tmpl);


$conn = sql_conectar(); //Apertura de Conexion

//Busco los parametros de configuracion
$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF WHERE ZPARAM CONTAINING 'SisEvento' ";
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$params[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
}

////////////////////////////////Duracion dias de evento ///////////////////////////////////


$diasdur = intval($params['SisEventoDuracionDias']); 	 	//Evento - Cantidad de Dias de duracion
$tmpl->setVariable('evedur', $diasdur);
//////////////////////////////////////////////////////////////////////////////////////////

$diasini = $params['SisEventoDiaInicio'];
///////// Pongo horario de fecha inicial y final en el calendario de bloqueo de franja////////// 
$vdia 	= explode('/', $diasini);
$tdia	= $vdia[2] . '-' . $vdia[1] . '-' . $vdia[0]; //Formato: 2018-12-31
$diasdurfinal=$diasdur - 1;
$diafinal 		= date('d/m/Y', strtotime($tdia . ' + ' . $diasdurfinal . ' day'));
$vdia1 	= explode('/', $diafinal);
$tdia1	= $vdia1[2] . '-' . $vdia1[1] . '-' . $vdia1[0]; //Formato: 2018-12-31
$tmpl->setVariable('fechainicial', $tdia);
$tmpl->setVariable('fechafinal', $tdia1);

$hoyfecha = date('Y-m-d');

	if ($hoyfecha<=$tdia){
		$tmpl->setVariable('fechaincioreunion'	, $tdia	);
	}else if ($hoyfecha<=$tdia1){
		$tmpl->setVariable('fechaincioreunion'	, $hoyfecha	);

	}else{
		$tmpl->setVariable('fechaincioreunion'	, ""	);
	}
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////Pongo fecha Incial del evento///////////////////////
$fechaeventoinicial 	= explode('/', $diasini);

$fechaeventoinicial	= $fechaeventoinicial[2] . '-' . $fechaeventoinicial[1] . '-' . $fechaeventoinicial[0]; //Formato: 2018-12-31

$tmpl->setVariable('evefch', $fechaeventoinicial);

////////////////////////////////////////////////////////////////////////////////////




//////////////////////////////Hora inicio y hora fin de evento///////////////////////////////////
$horaini = $params['SisEventoHorario']; 		 			//Evento - Horario de Inicio y Fin
$vhora	= explode('-', $horaini); //Ej: 09:00-15:30 (inicio - fin)
$hini	= trim($vhora[0]);
$hfin	= trim($vhora[1]);

$haux = date('H:i', strtotime('+10800 seconds', strtotime($hini))); //Pongo la hora en Huso horario 0
$haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
$hini = $haux;
 
$haux2 = date('H:i', strtotime('+10800 seconds', strtotime($hfin))); //Pongo la hora en Huso horario 0
$haux2 = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux2))); //Pongo la hora, segun el Huso horario establecido por el perfil
$hfin = $haux2;

$tmpl->setVariable('evehorini', $hini);
$tmpl->setVariable('evehorfin', $hfin);
/////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////// Duracion de las reuniones //////////////////////////////////////
$horaint = intval($params['SisEventoHorarioIntervalo']);
$horadescanso = intval($params['SisEventoDescanso']); //Evento - Intervalo de tiempo (min)
$tmpl->setVariable('reudes', $horadescanso);
$tmpl->setVariable('reudur', $horaint);
////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////// Tipo de Reunion //////////////////////////////////////

$queryreunion = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoReunion'";
$Tablereunion = sql_query($queryreunion, $conn);
for ($i = 0; $i < $Tablereunion->Rows_Count; $i++) {
	$rowreunion = $Tablereunion->Rows[$i];
	$tiporeunion = trim($rowreunion['ZVALUE']);
	if ($tiporeunion == 'true') {


		$tmpl->setVariable('valorreunionesod', 'selected');
	} else {
		$tmpl->setVariable('valorreunionesn', 'selected');
	}
}

/////////////////////////////////// Tipo de Evento //////////////////////////////////////

$query2 = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoEvento'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$tipoevento = trim($row['ZVALUE']);
	if ($tipoevento == 'true') {
		$tmpl->setVariable('valoreventohibrido', 'selected');
	}else if ($tipoevento == 'false'){
		$tmpl->setVariable('valoreventodigital', 'selected');
	}else{
		$tmpl->setVariable('valoreventopresencial', 'selected');
	}
}

///
$query2 = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'CompartirDatos'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$compartirdatos = trim($row['ZVALUE']);
	if ($compartirdatos == 'true') {
		$tmpl->setVariable('valorcompartirsi', 'selected');
	}else if ($compartirdatos == 'false'){
		$tmpl->setVariable('valorcompartirno', 'selected');
	}
}



//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
