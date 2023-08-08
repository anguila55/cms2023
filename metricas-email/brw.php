<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaAPI  . '/mailchimp.php';
	require_once GLBRutaFUNC . '/constants.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	
	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	
	$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
	
	$where = '';
	if($fltdescri!=''){
		$where .= " AND SECDESCRI CONTAINING '$fltdescri' ";
	}
	
	$tagnombreevento = preg_replace('/\s+/', '-', NAME_TITLE);
	$conn= sql_conectar();//Apertura de Conexion
	



	$query = "	SELECT MSGREG, MSGTITULO
				FROM MSG_CABE
				WHERE MSGREG!=10 AND MSGESTADO<3
				ORDER BY MSGREG ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];



		$msgreg 	= trim($row['MSGREG']);
		$msgtitulo 	= trim($row['MSGTITULO']);
		$mail=$tagnombreevento."_".$msgreg;
		$jsonmails=exportActivity($mail);
		$tmpl->setCurrentBlock('browser');
		if ($jsonmails==0){
			$tmpl->setVariable('mailasunto'	, $msgtitulo);
		$tmpl->setVariable('mailenviados'	, 0);
		$tmpl->setVariable('mailaperturastot'	, 0);
		$tmpl->setVariable('mailaperturasuni'	, 0);
		$tmpl->setVariable('mailopenrate'	, "0%");
		$tmpl->setVariable('mailclicksuni'	, 0);
		$tmpl->setVariable('mailclickrate'	, "0%");
		}else{
		$mailopenrate=($jsonmails->stats->last_90_days->unique_opens/$jsonmails->stats->last_90_days->sent)*100;
		$mailclickrate=($jsonmails->stats->last_90_days->unique_clicks/$jsonmails->stats->last_90_days->unique_opens)*100;
		$tmpl->setVariable('mailasunto'	, $msgtitulo);
		$tmpl->setVariable('mailenviados'	, $jsonmails->stats->last_90_days->sent);
		$tmpl->setVariable('mailaperturastot'	, $jsonmails->stats->last_90_days->opens);
		$tmpl->setVariable('mailaperturasuni'	, $jsonmails->stats->last_90_days->unique_opens);
		$tmpl->setVariable('mailopenrate'	, $mailopenrate."%");
		$tmpl->setVariable('mailclicksuni'	, $jsonmails->stats->last_90_days->unique_clicks);
		$tmpl->setVariable('mailclickrate'	, $mailclickrate."%");
		}
		
		
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
