<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/sendfcmmessage.php';
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$percodigo 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$reureg = (isset($_POST['reureg']))? trim($_POST['reureg']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$reureg = VarNullBD($reureg	, 'N');
	
	if($reureg!=0){
		
		$query = " 	UPDATE REU_CABE SET REUESTADO=5,REUFCHCAN=CURRENT_TIMESTAMP WHERE REUREG=$reureg ";
		$err = sql_execute($query,$conn,$trans);
		
		$query = " 	UPDATE REU_SOLI SET REUESTADO=5 WHERE REUREG=$reureg ";
		$err = sql_execute($query,$conn,$trans);
	
	}
		
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Reunion eliminada!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al eliminar la reuni�n!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
