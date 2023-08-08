<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('modalperfil.html');

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
	
	$query="SELECT VIDREG,US_NOMBRE,US_MAIL,US_TEL,US_EMP,US_PAI,US_LIN,US_FAC,US_TWI,US_WEB
			FROM VID_MAEST
			WHERE ESTCODIGO=1 AND VIDREG=$vidreg"; 
	$Table = sql_query($query,$conn);

	$colvid=0;
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$vidreg 	= trim($row['VIDREG']);

		$usnombre 	= trim($row['US_NOMBRE']);
		$usmail 	= trim($row['US_MAIL']);
		$ustelefono = trim($row['US_TEL']);
		$usempresa 	= trim($row['US_EMP']);
		$uspais 	= trim($row['US_PAI']);
		$uslinkedin = trim($row['US_LIN']);
		$usfacebook	= trim($row['US_FAC']);
		$ustwitter 	= trim($row['US_TWI']);
		$usweb 	= trim($row['US_WEB']);
		
		$tmpl->setVariable('vidreg', $vidreg);
		$tmpl->setVariable('usnombre'		, $usnombre);
		$tmpl->setVariable('usmail'		, $usmail);
		$tmpl->setVariable('ustelefono'	, $ustelefono);
		$tmpl->setVariable('usempresa'		, $usempresa);
		$tmpl->setVariable('uspais'		, $uspais);
		$tmpl->setVariable('uslinkedin'	, $uslinkedin);
		$tmpl->setVariable('usfacebook'		, $usfacebook);
		$tmpl->setVariable('ustwitter'	, $ustwitter);
		$tmpl->setVariable('usweb'		, $usweb);
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
