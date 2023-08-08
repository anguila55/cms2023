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
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT CATDESCRI, CATVALOR, CATREG
				FROM EXP_CAT
			 ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$catdescri 	= trim($row['CATDESCRI']);
		$catvalor 	= trim($row['CATVALOR']);
		$catreg	= trim($row['CATREG']);
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('catdescri'	, $catdescri);
		$tmpl->setVariable('catvalor'	, $catvalor);
		$tmpl->setVariable('catreg'		, $catreg);
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
