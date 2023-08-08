<?php
	if(!isset($_SESSION))  session_start();
	// include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC . '/constants.php';
	
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('comptickres.html');
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->setVariable('SisNombreEvento', NAME_TITLE );
$tmpl->setVariable('NAME_TITLE', NAME_TITLE );
$tmpl->setVariable('LOGIN_PERIOD', LOGIN_PERIOD );
$tmpl->setVariable('LOGIN_EMAIL', SEND_MAIL_LOGIN );
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->show();
	

