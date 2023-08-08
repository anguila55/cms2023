<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('vsl.html');
	
	//Diccionario de idiomas
	DDIdioma($tmpl);
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$expreg = (isset($_POST['expreg'])) ? trim($_POST['expreg']) : 0;	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($percodlog!=0){
		
		//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
		
		//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
		
		$query = "	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,P.PERCORREO
					FROM PER_MAEST P
					WHERE P.PERCODIGO=$percodlog";
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$percodigo 	= trim($row['PERCODIGO']);
			$desnombre 	= trim($row['PERNOMBRE']);
			$desapelli 	= trim($row['PERAPELLI']);
			$descompan 	= trim($row['PERCOMPAN']);
			$descorreo 	= trim($row['PERCORREO']);
			
			
			$tmpl->setVariable('percodigo'	, $percodigo	);
			$tmpl->setVariable('expreg'	, $expreg	);
			$tmpl->setVariable('desnombre'	, $desnombre	);
			$tmpl->setVariable('desapelli'	, $desapelli	);
			$tmpl->setVariable('descompan'	, $descompan	);
			$tmpl->setVariable('descorreo'	, $descorreo	);
			
	}
}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	

?>	
