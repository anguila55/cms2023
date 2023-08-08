<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';
	require_once GLBRutaFUNC.'/constants.php';		

	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';

	$conn = sql_conectar(); //Apertura de Conexion
$trans	= sql_begin_trans($conn);
	 //////////// IMAGENES /////////////////////
    
	//Marco la fecha de ingreso como estampa de ultimo login
	$query = "	UPDATE PER_MAEST SET PERPOP=1
	WHERE PERCODIGO=$percodigo ";
	$err	= sql_execute($query,$conn);

	if ($err == 'SQLACCEPT' && $errcod == 0) {
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'No se mostrara mas!';
	} else {
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg == '') ? 'No se mostrara mas!' : $errmsg;
	}

	sql_close($conn);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
	
?>	
