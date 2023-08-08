<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	
	//Diccionario de idiomas
	DDIdioma($tmpl);


	$conn = sql_conectar(); //Apertura de Conexion

	

	///////////////// Tickets ////////////////////

	

	$query = "	SELECT PM.PERCODIGO, PM.PERTIKREG, PM.PERTIKFCH, PM.AGEREG, PM.PERTIKITM, EP.PERNOMBRE,EP.PERAPELLI, TM.TIKDESCRI, TM.TIKCODIGO
			FROM PER_TICK_SALD PM
			LEFT OUTER JOIN PER_MAEST EP ON EP.PERCODIGO = PM.PERCODIGO
			LEFT OUTER JOIN PER_TICK PT ON PT.PERTIKREG = PM.PERTIKREG
			LEFT OUTER JOIN TIK_MAEST TM ON PT.TIKCODIGO = TM.TIKCODIGO
			
			WHERE EP.PERCODIGO = PM.PERCODIGO AND PT.PERTIKREG = PM.PERTIKREG AND PT.TIKCODIGO = TM.TIKCODIGO
			ORDER BY EP.PERAPELLI";

	$Table = sql_query($query, $conn);
	for ($i = 0; $i < $Table->Rows_Count; $i++) {

		$row = $Table->Rows[$i];
		$codigoticket 	= trim($row['PERCODIGO']);
		$nombreticket	= trim($row['PERNOMBRE']);
		$apelliticket 	= trim($row['PERAPELLI']);
		$pertikreg 	= trim($row['PERTIKREG']);
		$pertikfch 	= trim($row['PERTIKFCH']);
		$ageregtik 	= trim($row['AGEREG']);
		$pertikitm 	= trim($row['PERTIKITM']);
		$tipoticket		= trim($row['TIKDESCRI']);
		
		$tmpl->setCurrentBlock('tickets');
		$tmpl->setVariable('codigoticket'		, $codigoticket);
		$tmpl->setVariable('nombreticket'	, $nombreticket);
		$tmpl->setVariable('apelliticket'		, $apelliticket);
		$tmpl->setVariable('pertikreg'	, $pertikreg);
		$tmpl->setVariable('pertikfch'		, $pertikfch);
		$tmpl->setVariable('ageregtik'		, $ageregtik);
		$tmpl->setVariable('pertikitm'		, $pertikitm);
		$tmpl->setVariable('tipoticket'		, $tipoticket);
		$tmpl->parse('tickets');
		
	}

	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	$tmpl->show();
	
?>	
