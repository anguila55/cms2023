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
	
	$query = "	SELECT *
				FROM ACC_MAEST WHERE ESTCODIGO=1
			 ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$accreg	= trim($row['ACCREG']);
		$acctitulo 	= trim($row['ACCTITULO']);
		$accmostrar 	= trim($row['ACCMOSTRAR']);
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('acctitulo'	,  trim($acctitulo, "{}"));
		$tmpl->setVariable('accreg'		, $accreg);
		$tmpl->setVariable('checkedmostrar', $accmostrar==="true"?"checked":'');

		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
