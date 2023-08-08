<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('coordinarsoli.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$solicitante = (isset($_POST['solicitante']))? trim($_POST['solicitante']) : 0;
	$contraparte = (isset($_POST['contraparte']))? trim($_POST['contraparte']) : 0;
	$tipo = (isset($_POST['tipo']))? trim($_POST['tipo']) : 0;
	$reureg = (isset($_POST['reureg']))? trim($_POST['reureg']) : 0;
	$reufecha 	= (isset($_POST['reufecha']))? trim($_POST['reufecha']) : '';
	

	$tmpl->setVariable('percodsol'	, $contraparte	);
	$tmpl->setVariable('solicitante'	, $solicitante	);
	$tmpl->setVariable('reureg'	, $reureg	);
	$tmpl->setVariable('reufecha'	, $reufecha	);
	$tmpl->setVariable('tiporeunion'	, $tipo	);
	$reufecha 	= explode('/',$reufecha);
	
	$reufecha	= $reufecha[2].'-'.$reufecha[1].'-'.$reufecha[0]; //Formato: 2018-12-31
	
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion


	

	
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
	$tmpl->setVariable('fechainicial'	, $tdia	);
	$tmpl->setVariable('fechafinal'	, $tdia1	);
		
	$fecha 		= date('d/m/Y', strtotime($tdia));

	$tmpl->setVariable('fecha'		, $fecha	);
	$tmpl->setVariable('fechavalue'		, $reufecha	);
		
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
