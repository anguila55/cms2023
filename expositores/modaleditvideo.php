<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('modaleditvideo.html');

	DDIdioma($tmpl);

	$expreg = (isset($_POST['expreg'])) ? trim($_POST['expreg']) : 0;
	$vidreg = (isset($_POST['vidreg'])) ? trim($_POST['vidreg']) : 0;

	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//videos
	$videos ="SELECT  VIDURLID, VIDNOMBRE,VIDREG,VIDURL
	FROM EXP_VID
	WHERE EXPREG = $expreg AND VIDREG=$vidreg";

	$Table_videos = sql_query($videos, $conn); 

	for ($index_videos = 0; $index_videos < $Table_videos->Rows_Count; $index_videos++) {

	$row_video = $Table_videos->Rows[$index_videos];

	$vidurlid 		= trim($row_video['VIDURLID']);
	$vidurl		= trim($row_video['VIDURL']);
	$vidreg 		= trim($row_video['VIDREG']);
	$vidnombre 		= trim($row_video['VIDNOMBRE']);



	$tmpl->setVariable('vidurlid'		, $vidurlid);
	$tmpl->setVariable('vidurl'		, $vidurl);
	$tmpl->setVariable('vidreg'			, $vidreg);
	$tmpl->setVariable('vidnombre'		, $vidnombre);
	$tmpl->setVariable('expreg'	,  $expreg	);


	}	

	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
