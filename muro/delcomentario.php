<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$comreg = (isset($_POST['comreg']))? trim($_POST['comreg']) : 0;
	
	
	//--------------------------------------------------------------------------------------------------------------

	$comreg		= VarNullBD($comreg			, 'N');

	if ($comreg != 0) {

		$query = "DELETE FROM MUR_COMENT WHERE COMREG = $comreg";
		$err = sql_execute($query,$conn);
			
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>