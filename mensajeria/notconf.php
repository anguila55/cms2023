<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('notconf.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	//$msgreg = (isset($_SESSION[GLBAPPPORT.'MSGREG']))? trim($_SESSION[GLBAPPPORT.'MSGREG']) : '';
	$msgreg = (isset($_POST['msgreg']))? trim($_POST['msgreg']) : 0;

	
	
	
	
	$conn= sql_conectar();//Apertura de ConexionMSG
	if($msgreg!=0 && $msgreg!=10){
	$query="SELECT MSGREG, MSGTITULO, MSGESTADO,MSGDESCRI FROM MSG_CABE WHERE MSGREG=$msgreg";
	//logerror($query);
	$Table = sql_query($query,$conn);
	$row = $Table->Rows[0];
	$msgreg 	= trim($row['MSGREG']);
	$msgtitulo= trim($row['MSGTITULO']);
	$msgestado = trim($row['MSGESTADO']);
	$msgdescri = trim($row['MSGDESCRI']);

	$tmpl->setVariable('msgreg'	, $msgreg);
	$tmpl->setVariable('msgtitulo'	, $msgtitulo);
	$tmpl->setVariable('msgdescri'	, $msgdescri);
	$tmpl->setVariable('msgestado'	, $msgestado);
	$tmpl->setVariable('notsel'.$msgestado	, 'selected');

	}
	
	sql_close($conn);	
	$tmpl->show();
	
?>	
