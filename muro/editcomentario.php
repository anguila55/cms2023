<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$peradmin  = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';
	//--------------------------------------------------------------------------------------------------------------
	
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	
	//Control de Datos
	$comreg = (isset($_POST['comreg']))? trim($_POST['comreg']) : 0;
	$comdescri = (isset($_POST['comdescri']))? trim($_POST['comdescri']) : '';
	
	
	//--------------------------------------------------------------------------------------------------------------

	$comreg		= VarNullBD($comreg			, 'N');
	$comdescri	= VarNullBD($comdescri	, 'S');

	if ($comreg != 0) {

		$query = " 	UPDATE MUR_COMENT SET 
					COMDESCRI=$comdescri 
					WHERE COMREG = $comreg ";

		$err = sql_execute($query,$conn,$trans);
		
			
	}
	if ($err == 'SQLACCEPT' && $errcod == 0) {
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Guardado correctamente!';
	} else {
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg == '') ? 'Guardado correctamente!' : $errmsg;
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>