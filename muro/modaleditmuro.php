<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma		
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('modaleditmuro.html');

			
	
	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$peradmin  = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';

	$murreg = (isset($_POST['murreg'])) ? trim($_POST['murreg']) : 0;


	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "SELECT MURREG, PERCODIGO, MURTITULO, MURTAG, MURENLACE, MURFCH, ESTCODIGO, MURDESCRI, MURIMG
			FROM MUR_MAEST
			WHERE MURREG =  $murreg";

	$Table_Comentario = sql_query($query, $conn); 

	for ($com_index = 0; $com_index < $Table_Comentario->Rows_Count; $com_index++) {

	$row_com = $Table_Comentario->Rows[$com_index];
	$murreg 	= trim($row_com['MURREG']);
	$murtitulo 		= trim($row_com['MURTITULO']);
	$murtag 	= trim($row_com['MURTAG']);
	$murenlace		= trim($row_com['MURENLACE']);
	$murdescri 		= trim($row_com['MURDESCRI']);
	$murimg 		= trim($row_com['MURIMG']);
	$folderProd =  '../murimg/'.$murreg.'/';

	$tmpl->setVariable('murreg'	,  $murreg	);
	$tmpl->setVariable('murtitulo'	,  $murtitulo	);
	$tmpl->setVariable('murtag'	,  $murtag	);
	$tmpl->setVariable('murenlace'	,  $murenlace	);
	$tmpl->setVariable('murdescri'	,  $murdescri	);
	$tmpl->setVariable('murimg'	,  $folderProd.'/'.$murimg 	);

	}

	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
