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

	

	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT PTS, TIPO, MODELO, NOMBRE
				FROM GAME_TABLE
				ORDER BY NOMBRE ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];



		$tipo 	= trim($row['TIPO']);
		$pts 	= trim($row['PTS']);
		$modelo 	=  trim($row['MODELO']);
		$nombre 	= trim($row['NOMBRE']);
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('tipo'	, $tipo);
		$tmpl->setVariable('pts'	, $pts);
		$tmpl->setVariable('modelo'	, $modelo);
		$tmpl->setVariable('nombre'	, $nombre);

	
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
