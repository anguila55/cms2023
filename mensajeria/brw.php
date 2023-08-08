<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';	

	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	DDIdioma($tmpl);
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	
	$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
	$fltbuscar 	= (isset($_POST['fltbuscar']))? $_POST['fltbuscar']:1;

	
	$where = '';
	if($fltdescri!=''){
		$where .= " MSGTITULO CONTAINING '$fltdescri' AND MSGESTADO<>3  ";
	}
	if ($fltbuscar==1){

		$where .= "  MSGREG > 14 AND MSGESTADO<3 ";
		$tmpl->setVariable('linkadd'	, "showMaestro(0);");


	}else if ($fltbuscar==4){

		$where .= " MSGREG > 14 AND MSGESTADO>3 ";
		$tmpl->setVariable('linkadd'	, "showNotConf(0);");

	}else
	{

		$where .= " MSGREG < 14 AND MSGREG!=10 AND MSGESTADO<3 ";
		$tmpl->setVariable('displayadd'	, 'd-none');
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	if ($IdiomView=='ESP') {
		$variabletextotitulo1="01- MAIL DE REGISTRO - CONFIRMAR CUENTA";
		$variabletextotitulo2="03- MAIL DE RECUPERO DE CONTRASEÑA";
		$variabletextotitulo3="04- MAIL DE PERMISO PARA SOLICITAR REUNIONES";
		$variabletextotitulo4="02- MAIL DE LIBERACIÓN DEL PERFIL";
		$variabletextotitulo5="09- MAIL DE CHAT RECIBIDO";
		$variabletextotitulo6="05- MAIL DE SOLICITUD DE REUNION";
		$variabletextotitulo7="07- MAIL DE CONFIRMACIÓN DE REUNION";
		$variabletextotitulo8="08- MAIL DE CANCELACIÓN DE REUNIONES";
		$variabletextotitulo9="06- MAIL DE SOLICITUD DE REUNION POR MICROSITIO";
		$variabletextotitulo10="10- MAIL REUNION 10 MINUTOS ANTES";
		$variabletextotitulo11="11- MAIL REUNION DESDE ADMIN REUNIONES";
		$variabletextotitulo12="12- MAIL GAFETE";
		
	}else if ($IdiomView=='ING'){
		$variabletextotitulo1="01- SIGN IN NOTIFICATION - CONFIRM YOUR ACCOUNT";
		$variabletextotitulo2="03- PASSWORD RECOVERY NOTIFICATION";
		$variabletextotitulo3="04- PERMISSION TO REQUEST MEETINGS NOTIFICATION";
		$variabletextotitulo4="02- PROFILE APPROVED NOTIFICATION";
		$variabletextotitulo5="09- CHAT RECEIVED NOTIFICATION";
		$variabletextotitulo6="05- MEETING REQUESTED NOTIFICATION";
		$variabletextotitulo7="07- MEETING CONFIRMED NOTFICATION";
		$variabletextotitulo8="08- MEETING CANCELLED NOTIFICATION";
		$variabletextotitulo9="06- MEETING REQUESTED VIA STAND NOTIFICATION";
		$variabletextotitulo10="10- 10 MINUTES BEFORE MEETING NOTIFICATION";
		$variabletextotitulo11="11- MEETING REQUESTED VIA ADMIN";
		$variabletextotitulo12="12- EMAIL ENTRANCE";
		
	}else{
		$variabletextotitulo1="01- MAIL DE REGISTRO - CONFIRMAR CUENTA";
		$variabletextotitulo2="03- MAIL DE RECUPERO DE CONTRASEÑA";
		$variabletextotitulo3="04- MAIL DE PERMISO PARA SOLICITAR REUNIONES";
		$variabletextotitulo4="02- MAIL DE LIBERACIÓN DEL PERFIL";
		$variabletextotitulo5="09- MAIL DE CHAT RECIBIDO";
		$variabletextotitulo6="05- MAIL DE SOLICITUD DE REUNION";
		$variabletextotitulo7="07- MAIL DE CONFIRMACIÓN DE REUNION";
		$variabletextotitulo8="08- MAIL DE CANCELACIÓN DE REUNIONES";
		$variabletextotitulo9="06- MAIL DE SOLICITUD DE REUNION POR MICROSITIO";
		$variabletextotitulo10="10- MAIL REUNION 10 MINUTOS ANTES";
		$variabletextotitulo11="11- MAIL REUNION DESDE ADMIN REUNIONES";
		$variabletextotitulo12="12- MAIL GAFETE";
		
	}
	$query = "	SELECT MSGREG, MSGFCHREG, MSGTITULO, MSGDESCRI, MSGESTADO
				FROM MSG_CABE
				WHERE $where
				ORDER BY MSGREG ";
	// logerror($query);
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$msgreg 	= trim($row['MSGREG']);
		$msgfchreg 	= trim($row['MSGFCHREG']);
		$msgtitulo 	= trim($row['MSGTITULO']);
		$msgdescri 	= trim($row['MSGDESCRI']);
		$msgestado 	= trim($row['MSGESTADO']);

		if ($fltbuscar==2 ){
			$tmpl->setVariable('displayeliminar'	, 'd-none');
			$tmpl->setVariable('displaymail'	, 'd-none');
			$tmpl->setVariable('displaynot'	, 'd-none');
			$tmpl->setVariable('linkaddedit'	, "showMaestro($msgreg);"	);
			

		}
		if ($fltbuscar==1 ){
			
			$tmpl->setVariable('displaynot'	, 'd-none');
			$tmpl->setVariable('linkaddedit'	, "showMaestro($msgreg);"	);
			


		}
		if ($fltbuscar==4 ){
			
			$tmpl->setVariable('displaymail'	, 'd-none');
			$tmpl->setVariable('displayprev'	, 'd-none');
			$tmpl->setVariable('linkaddedit'	, "showNotConf($msgreg);");
			

		}

		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('msgreg'	, $msgreg);
		$tmpl->setVariable('msgfchreg'	, $msgfchreg);
		if ($msgreg==1){
			$msgtitulo=$variabletextotitulo1;
		}else if ($msgreg==2){
			$msgtitulo=$variabletextotitulo2;
		}else if ($msgreg==3){
			$msgtitulo=$variabletextotitulo3;
		}else if ($msgreg==4){
			$msgtitulo=$variabletextotitulo4;
		}else if ($msgreg==5){
			$msgtitulo=$variabletextotitulo5;
		}else if ($msgreg==6){
			$msgtitulo=$variabletextotitulo6;
		}else if ($msgreg==7){
			$msgtitulo=$variabletextotitulo7;
		}else if ($msgreg==8){
			$msgtitulo=$variabletextotitulo8;
		}else if ($msgreg==9){
			$msgtitulo=$variabletextotitulo9;
		}else if ($msgreg==11){
			$msgtitulo=$variabletextotitulo10;
		}else if ($msgreg==12){
			$msgtitulo=$variabletextotitulo11;
		}else if ($msgreg==13){
			$msgtitulo=$variabletextotitulo12;
		}
		$tmpl->setVariable('msgtitulo'	, $msgtitulo);
		$tmpl->setVariable('msgdescri'	, $msgdescri);
		$tmpl->setVariable('msgestado'	, $msgestado);

		$tmpl->parse('browser');



		
		
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
