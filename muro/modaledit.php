<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma		
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('modaledit.html');

			
	
	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$peradmin  = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';

	$comreg = (isset($_POST['comreg'])) ? trim($_POST['comreg']) : 0;


	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "SELECT COMREG, COMDESCRI
			FROM MUR_COMENT
			WHERE COMREG =  $comreg";

	$Table_Comentario = sql_query($query, $conn); 

	for ($com_index = 0; $com_index < $Table_Comentario->Rows_Count; $com_index++) {

	$row_com = $Table_Comentario->Rows[$com_index];
	$comreg 	= trim($row_com['COMREG']);
	$comdescri 		= trim($row_com['COMDESCRI']);
	

	$tmpl->setVariable('comreg'	,  $comreg	);
	$tmpl->setVariable('comdescri'	,  $comdescri	);

	}

	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
