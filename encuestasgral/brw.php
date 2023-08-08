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
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	
	
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT ENC_REG,ENC_ID,ENC_TIPPER
				FROM ENC_GRAL
				WHERE ESTCODIGO<>3
				ORDER BY ENC_REG ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$encreg 	= trim($row['ENC_REG']);
		$encid 	= trim($row['ENC_ID']);
		$enctipper 	= trim($row['ENC_TIPPER']);
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('numeroencuesta'	, $encreg);
		$tmpl->setVariable('idencuesta'	, $encid);
		$tmpl->setVariable('tipoperfil'	, $enctipper);
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
