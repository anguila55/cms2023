<?php
	if(!isset($_SESSION))  session_start();
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/constants.php';
	
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('msgprox.html');

	$tmpl->setVariable('SisNombreEvento', NAME_TITLE );
	//--------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->show();
	

