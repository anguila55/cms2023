<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brwclase.html');
	
	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	
	$sector = (isset($_POST['sector']))? trim($_POST['sector']):'';
	$tmpl->setVariable('fltperfil'	, $sector);
	
	
	$conn= sql_conectar();//Apertura de Conexion

	
	$query = "	SELECT SECSUBCOD, SECSUBDES
				FROM SEC_SUB
				WHERE ESTCODIGO <> 3 AND SECCODIGO=$sector
				ORDER BY SECSUBDES ";
	$Table = sql_query($query,$conn);

	
	for($i=0; $i<$Table->Rows_Count; $i++){

		$row = $Table->Rows[$i];
		$subcod 	= trim($row['SECSUBCOD']);
		$subcoddes 	= trim($row['SECSUBDES']);

		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('subsect'	, $subcod);
		$tmpl->setVariable('subsectdes'	, $subcoddes);
		$tmpl->setVariable('sector'	, $sector);
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
