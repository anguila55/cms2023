<?php
$foldercentral = '';
ini_set('default_charset', 'UTF-8');

//Validacion de LOGUEO de USUARIO	 
session_start();

if (!defined('GLBAPPPORT')) {
	define('GLBAPPPORT', $_SERVER['SERVER_PORT']);
}

require_once($_SERVER["DOCUMENT_ROOT"] . '/func/zglobals.php'); //PRD
require_once GLBRutaFUNC.'/zfvarias.php';
$percodigo = isset($_SESSION[GLBAPPPORT . 'PERCODIGO']) ? $_SESSION[GLBAPPPORT . 'PERCODIGO'] : isset($_COOKIE[GLBAPPPORT . 'PERCODIGO']) ? $_COOKIE[GLBAPPPORT . 'PERCODIGO'] : "";
validateLogin($percodigo);

//if (!isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) { //Control de Logueo de Usuario
	if (!$percodigo) { //Control de Logueo de Usuario
		$_SESSION[GLBAPPPORT . 'PERCODIGO'] = '';
		//$_SESSION[GLBAPPPORT . 'PERCODIGO'] 		= 210888;
		///	$_SESSION[GLBAPPPORT . 'PERIDIOMA'] = 'ESP';
		echo "<script> window.top.location='$foldercentral/index'; </script>";
		exit;
	} else {
		if (trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) == '') {
	
			echo "<script> window.top.location='$foldercentral/index'; </script>";
			exit;
		}
	}

//Variables Globales
// include($_SERVER["DOCUMENT_ROOT"].$foldercentral.'/func/zglobals.php'); //DEV
//include($_SERVER["DOCUMENT_ROOT"] . '/func/zglobals.php'); //PRD


//----------------------------------------------------------------------------------------------
//Valido KEY
require_once "valkey.php";
if (ValKey() != 'BENVIDO10450420SISTEMAS') {
	echo "Error de Registracion!";
	exit;
}
