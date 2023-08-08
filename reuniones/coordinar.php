<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('coordinar.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$timoffset = (isset($_SESSION[GLBAPPPORT.'TIMOFFSET']))? trim($_SESSION[GLBAPPPORT.'TIMOFFSET']) : '';

	$percodsol 	= (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	$reureg 	= (isset($_POST['reureg']))? trim($_POST['reureg']) : 0;
	$reufecha 	= (isset($_POST['reufecha']))? trim($_POST['reufecha']) : '';
	$reutipo 	= (isset($_POST['reutipo']))? trim($_POST['reutipo']) : 0;
	
	$tmpl->setVariable('percodsol'	, $percodsol	);
	$tmpl->setVariable('reufecha'	, $reufecha	);
	$reufecha 	= explode('/',$reufecha);
	
	$reufecha	= $reufecha[2].'-'.$reufecha[1].'-'.$reufecha[0]; //Formato: 2018-12-31
	
	$tmpl->setVariable('reureg'	, $reureg	);
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion

	$queryUsuario = "	SELECT PERNOMBRE, PERAPELLI, PERCOMPAN
FROM PER_MAEST
WHERE PERCODIGO=$percodsol";
$TableUsuario = sql_query($queryUsuario,$conn);
$nombresolicitud = trim($TableUsuario->Rows[0]['PERNOMBRE']);
$apellidosolicitud = trim($TableUsuario->Rows[0]['PERAPELLI']); 
$empresasolicitud = trim($TableUsuario->Rows[0]['PERCOMPAN']);

$tmpl->setVariable('nombresolicitud', $nombresolicitud);
$tmpl->setVariable('apellidosolicitud', $apellidosolicitud);
$tmpl->setVariable('empresasolicitud', $empresasolicitud);

	$query2 = " SELECT ZVALUE FROM ZZZ_CONF WHERE ZPARAM = 'TipoEvento'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$tipoevento = trim($row['ZVALUE']);
	if ($tipoevento != 'false') {

		$tmpl->setVariable('mostrarqr', '');
	} else {
		$tmpl->setVariable('mostrarqr', 'd-none');
	}
}

if ($tipoevento){

	// Veo si se puede dar reuniones presenciales entre las personas
	$queryTipo = "	SELECT TIPO
	FROM PER_MAEST
	WHERE PERCODIGO=$percodsol";
$TableTipo = sql_query($queryTipo,$conn);
$tiposolicitante = trim($TableTipo->Rows[0]['TIPO']);

$queryTipo2 = "	SELECT TIPO
FROM PER_MAEST
WHERE PERCODIGO=$percodigo";
$TableTipo2 = sql_query($queryTipo2,$conn);
$tiporeceptor = trim($TableTipo2->Rows[0]['TIPO']); 


if ($IdiomView=='ESP') {
	$variabletextotipo1="Reunión presencial";
	$variabletextotipo2="Reunión virtual";
	$variabletextotipo3="Reunión Virtual. El solicitante también acepta reuniones presenciales. Modifique su perfil si desea cambiar el tipo de reunión.";
	$variabletextotipo4="Reunión Presencial. El solicitante también acepta reuniones virtuales. Modifique su perfil si desea cambiar el tipo de reunión.";
}else if ($IdiomView=='ING'){
	$variabletextotipo1="Face-to-face meeting";
	$variabletextotipo2="Virtual meeting";
	$variabletextotipo3="Virtual Meeting. The applicant also accepts face-to-face meetings. Modify your profile if you want to change the type of meeting.";
	$variabletextotipo4="Reunión Presencial. El solicitante también acepta reuniones virtuales. Modifique su perfil si desea cambiar el tipo de reunión.";
}else{
	$variabletextotipo1="Reunión presencial";
	$variabletextotipo2="Reunión virtual";
	$variabletextotipo3="Reunión Virtual. El solicitante también acepta reuniones presenciales. Modifique su perfil si desea cambiar el tipo de reunión.";
	$variabletextotipo4="Face-to-face meeting. The applicant also accepts virtual meetings. Modify your profile if you want to change the type of meeting.";
}

if (($tiposolicitante == 0) && ($reutipo != 1)){ // solicitante virtual
	
	$tmpl->setVariable('mostrartipo'	, 'd-none'	);
	$tmpl->setVariable('mostrartextotipo'	, ''	);
	$tmpl->setVariable('textotipo'	, $variabletextotipo2	);
	

}else if (($tiposolicitante == 1) && ($reutipo == 1)){ // solicitante presencial

	$tmpl->setVariable('mostrartipo'	, 'd-none'	);
	$tmpl->setVariable('mostrartextotipo'	, ''	);
	$tmpl->setVariable('textotipo'	, $variabletextotipo1	);
	$tmpl->setVariable('selectedpresencial'	, 'selected'	);
		
}else if ($tiposolicitante == 2){ // solicitante ambas

	
	if (($tiporeceptor == 0) && ($reutipo == 0) ){  // solicitante virtual

	$tmpl->setVariable('mostrartipo'	, 'd-none'	);
	$tmpl->setVariable('mostrartextotipo'	, ''	);
	$tmpl->setVariable('textotipo'	, $variabletextotipo3	);

	}else if ( ($tiporeceptor == 1) && ($reutipo == 1) ){  // presencial

	$tmpl->setVariable('mostrartipo'	, 'd-none'	);
	$tmpl->setVariable('mostrartextotipo'	, ''	);
	$tmpl->setVariable('textotipo'	, $variabletextotipo4	);
	$tmpl->setVariable('selectedpresencial'	, 'selected'	);
	}else if ($tiporeceptor == 2) // presencial y acepta ambas
	{
		$tmpl->setVariable('mostrartipo'	, ''	);
		$tmpl->setVariable('mostrartextotipo'	, 'd-none'	);
		if ($reutipo == 1){
			$tmpl->setVariable('selectedpresencial'	, 'selected'	);
		}
	
	}

}

}

	
	//Busco los parametros de configuracion
	$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF WHERE ZPARAM CONTAINING 'SisEvento' ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$params[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
	}
	
	$diasini = $params['SisEventoDiaInicio']; 		 			//Evento - Dia de Inicio
	$diasdur = intval($params['SisEventoDuracionDias']); 
	$diasdur = $diasdur - 1;	 	//Evento - Cantidad de Dias de duracion
	$horaini = $params['SisEventoHorario']; 		 			//Evento - Horario de Inicio y Fin
	$horaint = intval($params['SisEventoHorarioIntervalo']); 	//Evento - Intervalo de tiempo (min)
	
	
	
	$tdia	= $reufecha;
	
	$vdia 	= explode('/',$diasini);
	$tdia	= $vdia[2].'-'.$vdia[1].'-'.$vdia[0]; //Formato: 2018-12-31
	$diafinal 		= date('d/m/Y', strtotime($tdia. ' + '.$diasdur.' day'));
	$vdia1 	= explode('/',$diafinal);
	$tdia1	= $vdia1[2].'-'.$vdia1[1].'-'.$vdia1[0]; //Formato: 2018-12-31
	//$tmpl->setVariable('fechainicial'	, $tdia	);
	$hoyfecha = date('Y-m-d');

	if ($hoyfecha<=$tdia){
		$tmpl->setVariable('fechainicial'	, $tdia	);
	}else if ($hoyfecha<=$tdia1){
		$tmpl->setVariable('fechainicial'	, $hoyfecha	);

	}else{
		$tmpl->setVariable('fechainicial'	, ""	);
	}
	$tmpl->setVariable('fechafinal'	, $tdia1	);
		
	$fecha 		= date('d/m/Y', strtotime($tdia));

	$tmpl->setVariable('fecha'		, $fecha	);
	$tmpl->setVariable('fechavalue'		, $reufecha	);

	$timoffset 	= $timoffset / 60.0 /60.0;
	$signo		= ($timoffset>0)? '+':'-';
	$timoffset	= abs($timoffset);
	$horas 		= floor($timoffset);
	$minutos 	= ($timoffset - $horas) * 60;
	
	$horas = str_pad($horas, 2, "0", STR_PAD_LEFT);
	$minutos = str_pad($minutos, 2, "0", STR_PAD_LEFT);
	$tmpl->setVariable('timdescri'	, " GMT$signo$horas:$minutos"	);
		
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
