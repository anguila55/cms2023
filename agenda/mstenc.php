<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mstenc.html');
	$conn= sql_conectar();//Apertura de Conexion
	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	
	$agereg = (isset($_POST['agereg']))? trim($_POST['agereg']) : 0;
	$encreg = (isset($_POST['encreg']))? trim($_POST['encreg']) : 0;
	
	$query = "	SELECT A.AGETITULO
				FROM AGE_MAEST A
				WHERE A.AGEREG=$agereg ";

	$Table = sql_query($query,$conn);
	$row = $Table->Rows[0];
	$agetitulo 	= trim($row['AGETITULO']);
	$tmpl->setVariable('agereg'		, $agereg );
	$tmpl->setVariable('agetitulo'	, $agetitulo );
	
	//Cargo todas las encuestas
	$query = "	SELECT E.ENCREG,E.ENCDESCRI
				FROM ENC_CABE E
				WHERE E.ESTCODIGO=1 
				ORDER BY 2 ";

	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		
		$row = $Table->Rows[$i];
		
		$reg 		= trim($row['ENCREG']);
		$encdescri  = trim($row['ENCDESCRI']);
		
		$tmpl->setCurrentBlock('encuestas');
		$tmpl->setVariable('encreg'		, $reg);
		$tmpl->setVariable('encdescri'	, $encdescri);
		
		if($encreg == $reg){
			$tmpl->setVariable('encselect'	, 'selected');
		}
		
		$tmpl->parse('encuestas');	
	}

	
	//--------------------------------------------------------------------------------------------------------------
	
	sql_close($conn);	
	$tmpl->show();
	
?>	
