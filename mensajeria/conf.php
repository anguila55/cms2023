<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('conf.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	//$msgreg = (isset($_SESSION[GLBAPPPORT.'MSGREG']))? trim($_SESSION[GLBAPPPORT.'MSGREG']) : '';
	$msgreg = (isset($_POST['msgreg']))? trim($_POST['msgreg']) : 0;
	$tmpl->setVariable('msgreg'	, $msgreg);

	
	
	$conn= sql_conectar();//Apertura de ConexionMSG

	$query="SELECT MSGREG, MSGREP, MSGCC, MSGCCO FROM MSG_CABE WHERE MSGREG=$msgreg";
	//logerror($query);
	$Table = sql_query($query,$conn);
	if(isset($Table->Rows[0])){ 
	$row = $Table->Rows[0];

	$msgrep= trim($row['MSGREP']);
	$msgcc = trim($row['MSGCC']);
	$msgcco = trim($row['MSGCCO']);

	
	$tmpl->setVariable('msgrep'	, $msgrep);
	$tmpl->setVariable('msgcc'	, $msgcc);
	$tmpl->setVariable('msgcco'	, $msgcco);
	}

	
	sql_close($conn);	
	$tmpl->show();
	
?>	
