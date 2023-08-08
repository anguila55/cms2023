<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$reg 	= (isset($_POST['reg']))? trim($_POST['reg']) : 0;
	$tabla = (isset($_POST['tabla']))? trim($_POST['tabla']) : '';
	$campo = (isset($_POST['campo']))? trim($_POST['campo']) : '';
	$tipo = (isset($_POST['tipo']))? trim($_POST['tipo']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	
	$reg = VarNullBD($reg , 'N');
	

	$tabla = strtoupper ( $tabla );
	$campo = strtoupper ( $campo );



	if($reg!=0){
		//Elimino el registro

		if($tipo == 1){
			$query = "DELETE FROM $tabla WHERE $campo = $reg";
			$err = sql_execute($query,$conn,$trans);
		}
		if($tipo == 2){
			$query = "UPDATE EXP_MAEST SET $campo='' WHERE EXPREG = $reg";
			$err = sql_execute($query,$conn,$trans);
		}

	}
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Expositor eliminado!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al eliminar el expositor!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
