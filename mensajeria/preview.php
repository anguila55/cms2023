<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('preview.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	//$msgreg = (isset($_SESSION[GLBAPPPORT.'MSGREG']))? trim($_SESSION[GLBAPPPORT.'MSGREG']) : '';
	$msgreg = (isset($_POST['msgreg']))? trim($_POST['msgreg']) : 0;

	
	
	
	
	$conn= sql_conectar();//Apertura de ConexionMSG

	$query="SELECT MSGREG, MSGTITULO, MSGDESCRI, MSGIMG, MSGBOT, MSGLNK FROM MSG_CABE WHERE MSGREG=$msgreg";
	//logerror($query);
	$Table = sql_query($query,$conn);
	$row = $Table->Rows[0];
	$msgreg 	= trim($row['MSGREG']);
	$msgtitulo= trim($row['MSGTITULO']);
	$msgdescri = trim($row['MSGDESCRI']);
	$msgbot = trim($row['MSGBOT']);
	$msglnk = trim($row['MSGLNK']);
	$msgimg = trim($row['MSGIMG']);
	$folderProd =  '../mailsimg/'.$msgreg.'/';
	$tmpl->setVariable('msgimg'	,  $folderProd.'/'.$msgimg 	);

	$tmpl->setVariable('msgreg'	, $msgreg);
	$tmpl->setVariable('msgtitulo'	, $msgtitulo);
	$msgdescri = htmlspecialchars_decode($msgdescri);
	$msgdescri = str_replace("ql-align-center","text-center",$msgdescri);
	$msgdescri = str_replace("ql-align-justify","text-justify",$msgdescri);
	$msgdescri = str_replace("ql-align-right","text-right",$msgdescri);
	$msgdescri = str_replace("VariableNombre","Plataforma",$msgdescri);
	$msgdescri = str_replace("VariableApellido","BTBox",$msgdescri);
	$msgdescri = str_replace("VariableEmpresa","BTBOX",$msgdescri);
	$tmpl->setVariable('msgdescri'	, $msgdescri);
	$tmpl->setVariable('msgbot'	, 'Ingresa Aquí');
	$tmpl->setVariable('msglnk'	, '');
	if($msgbot!=''){

		$tmpl->setVariable('msgbot'	, $msgbot);

	}
	if($msglnk!=''){

		$tmpl->setVariable('msglnk'	, $msglnk);
	}
	
	sql_close($conn);	
	$tmpl->show();
	
?>	
