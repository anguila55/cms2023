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
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$timoffset = (isset($_SESSION[GLBAPPPORT.'TIMOFFSET']))? trim($_SESSION[GLBAPPPORT.'TIMOFFSET']) : '';

	$percoddst = (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;

	$tmpl->setVariable('percoddst'	, $percoddst	);
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$queryUsuario = "	SELECT PERNOMBRE, PERAPELLI, PERCOMPAN
FROM PER_MAEST
WHERE PERCODIGO=$percoddst";
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
		WHERE PERCODIGO=$percodigo";
	$TableTipo = sql_query($queryTipo,$conn);
	$tiposolicitante = trim($TableTipo->Rows[0]['TIPO']);
	
	$queryTipo2 = "	SELECT TIPO
	FROM PER_MAEST
	WHERE PERCODIGO=$percoddst";
	$TableTipo2 = sql_query($queryTipo2,$conn);
	$tiporeceptor = trim($TableTipo2->Rows[0]['TIPO']);

	
	if ($IdiomView=='ESP') {
		$variabletextotipo="El usuario solo acepta reuniones presenciales. En caso de poder, modifique su perfil para poder coordinar una reunión en el evento";
		$variabletextotipo1="Solicitando reunión virtual. El usuario acepta reuniones virtuales y presenciales. En caso de poder, modifique su perfil para poder coordinar una reunión presencial. ";
		$variabletextotipo2="El usuario solo acepta reuniones virtuales. En caso de poder, modifique su perfil para poder coordinar una reunión virtual ";
		$variabletextotipo3="Solicitando reunión presencial";
		$variabletextotipo4="Solicitando reunión presencial. El usuario también puede reunirse virtualmente. Si lo de desea, dirijase a su perfil para editar sus preferencias";
		$variabletextotipo5="Solicitando reunión virtual. El usuario solo acepta reuniones virtuales.";
		$variabletextotipo6="Solicitando reunión presencial. El usuario solo acepta reuniones presenciales";
	}else if ($IdiomView=='ING'){
		$variabletextotipo="The user only accepts face-to-face meetings. If you can, modify your profile to be able to coordinate a meeting at the event";
		$variabletextotipo1="Requesting virtual meeting. The user accepts virtual and face-to-face meetings. If you can, modify your profile to be able to coordinate a face-to-face meeting. ";
		$variabletextotipo2="The user only accepts virtual meetings. If you can, modify your profile to be able to coordinate a virtual meeting ";
		$variabletextotipo3="Requesting face-to-face meeting";
		$variabletextotipo4="Requesting face-to-face meeting. The user can also meet virtually. If you wish, go to your profile to edit your preferences";
		$variabletextotipo5="Requesting virtual meeting. The user only accepts virtual meetings.";
		$variabletextotipo6="Requesting face-to-face meeting. The user only accepts face-to-face meetings";
	}else{
	
		$variabletextotipo="El usuario solo acepta reuniones presenciales. En caso de poder, modifique su perfil para poder coordinar una reunión en el evento";
		$variabletextotipo1="Solicitando reunión virtual. El usuario acepta reuniones virtuales y presenciales. En caso de poder, modifique su perfil para poder coordinar una reunión presencial. ";
		$variabletextotipo2="El usuario solo acepta reuniones virtuales. En caso de poder, modifique su perfil para poder coordinar una reunión virtual ";
		$variabletextotipo3="Solicitando reunión presencial";
		$variabletextotipo4="Solicitando reunión presencial. El usuario también puede reunirse virtualmente. Si lo de desea, dirijase a su perfil para editar sus preferencias";
		$variabletextotipo5="Solicitando reunión virtual. El usuario solo acepta reuniones virtuales.";
		$variabletextotipo6="Solicitando reunión presencial. El usuario solo acepta reuniones presenciales";
	}
	if($tiposolicitante == 0){ // solicitante virtual
	
		if ($tiporeceptor == 0){   /// los dos virtuales
			$tmpl->setVariable('mostrartipo'	, 'd-none'	);
			$tmpl->setVariable('mostrartextotipo'	, 'd-none'	);
		}else if ($tiporeceptor == 1)  // virtual solicitante , presencial receptor
		{
			$tmpl->setVariable('mostrartipo'	, 'd-none'	);
			$tmpl->setVariable('textotipo'	, $variabletextotipo	);
			$tmpl->setVariable('selectedpresencial'	, 'selected'	);
			$tmpl->setVariable('mostrartextotipo'	, ''	);
			$tmpl->setVariable('bloquearreunion'	, 'd-none'	);
		}else{
			$tmpl->setVariable('mostrartipo'	, 'd-none'	);
			$tmpl->setVariable('textotipo'	, $variabletextotipo1	);
			 $tmpl->setVariable('mostrartextotipo'	, ''	);
		}
	
	
	
	}else if ($tiposolicitante == 1){ // solicitante presencial
	
		if ($tiporeceptor == 0){  // solicitante virtual
	
			$tmpl->setVariable('mostrartipo'	, 'd-none'	);
			 $tmpl->setVariable('textotipo'	, $variabletextotipo2	);
			 $tmpl->setVariable('mostrartextotipo'	, ''	);
			 $tmpl->setVariable('bloquearreunion'	, 'd-none'	);
	
		}else if ($tiporeceptor == 1){  // ambos presenciales
	
			$tmpl->setVariable('mostrartipo'	, 'd-none'	);
			 $tmpl->setVariable('textotipo'	, $variabletextotipo3	);
			 $tmpl->setVariable('selectedpresencial'	, 'selected'	);
			 $tmpl->setVariable('mostrartextotipo'	, ''	);
	
		}else if ($tiporeceptor == 2) // presencial y acepta ambas
		{
			$tmpl->setVariable('textotipo'	, $variabletextotipo4	);
			$tmpl->setVariable('selectedpresencial'	, 'selected'	);
			$tmpl->setVariable('mostrartipo'	, 'd-none'	);
			$tmpl->setVariable('mostrartextotipo'	, ''	);
		}
		
	}else if ($tiposolicitante == 2){ // solicitante ambas
	
		if ($tiporeceptor == 0){  // solicitante virtual
	
			$tmpl->setVariable('mostrartipo'	, 'd-none'	);
			 $tmpl->setVariable('textotipo'	, $variabletextotipo5	);
			 $tmpl->setVariable('mostrartextotipo'	, ''	);
			 $tmpl->setVariable('bloquearreunion'	, ''	);
	
		}else if ($tiporeceptor == 1){  // presencial
	
			$tmpl->setVariable('mostrartipo'	, 'd-none'	);
			 $tmpl->setVariable('textotipo'	, $variabletextotipo6	);
			 $tmpl->setVariable('selectedpresencial'	, 'selected'	);
			 $tmpl->setVariable('mostrartextotipo'	, ''	);
	
		}else if ($tiporeceptor == 2) // presencial y acepta ambas
		{
			$tmpl->setVariable('mostrartextotipo'	, 'd-none'	);
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
	
	
	
	$vdia 	= explode('/',$diasini);
	$tdia	= $vdia[2].'-'.$vdia[1].'-'.$vdia[0]; //Formato: 2018-12-31
	$diafinal 		= date('d/m/Y', strtotime($tdia. ' + '.$diasdur.' day'));
	$vdia1 	= explode('/',$diafinal);
	$tdia1	= $vdia1[2].'-'.$vdia1[1].'-'.$vdia1[0]; //Formato: 2018-12-31
	$tmpl->setVariable('fechainicial'	, $tdia	);
	$tmpl->setVariable('fechafinal'	, $tdia1	);

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
