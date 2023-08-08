<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('modaleditimagen.html');

	DDIdioma($tmpl);

	$expreg = (isset($_POST['expreg'])) ? trim($_POST['expreg']) : 0;
	$imgreg = (isset($_POST['imgreg'])) ? trim($_POST['imgreg']) : 0;

	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//
	//imagenes
	$imagenes ="SELECT  EXPIMG ,IMGREG
						FROM EXP_IMG
						WHERE EXPREG = $expreg AND IMGREG=$imgreg";

	$Table_imagenes = sql_query($imagenes, $conn); 
	$folderProd =  '../expimg/'.$expreg.'/';

	for ($index_img = 0; $index_img < $Table_imagenes->Rows_Count; $index_img++) {
		
		$row_img = $Table_imagenes->Rows[$index_img];

		$expimg 		= trim($row_img['EXPIMG']);
		$imgreg 		= trim($row_img['IMGREG']);
		
	
		$tmpl->setVariable('expimgsrc'		,$folderProd.$expimg);
		$tmpl->setVariable('imgreg'		,$imgreg);
		$tmpl->setVariable('expreg'	,  $expreg	);
		
		
		
	}

	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
