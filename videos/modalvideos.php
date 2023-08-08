<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('modalvideos.html');

	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	//Guardo la session
	$conn = sql_conectar(); //Apertura de Conexion
	sql_close($conn);
	//--------------------------------------------------------------------------------------------------------------
	$vidreg = (isset($_POST['vidreg']))? trim($_POST['vidreg']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$query="SELECT VIDREG,VIDURL,VIDTITULOCARTA
		FROM VID_MAEST
		WHERE ESTCODIGO=1 AND VIDREG=$vidreg"; 
$Table = sql_query($query,$conn);

$colvid=0;
for($i=0; $i<$Table->Rows_Count; $i++){
 	$row = $Table->Rows[$i];
 	$vidreg 	= trim($row['VIDREG']);
	$vidurl 	= trim($row['VIDURL']);
	$vidtitulocarta 	= trim($row['VIDTITULOCARTA']);


 	$tmpl->setVariable('vidreg', $vidreg);
	$tmpl->setVariable('vidurl'		, $vidurl);
	$tmpl->setVariable('vidtitulocarta'		, $vidtitulocarta);
	

	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
