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
	$workreg 	= (isset($_POST['workreg']))? trim($_POST['workreg']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$workreg = VarNullBD($workreg , 'N');
	
	if($workreg!=0){
		//Elimino el registro
		$query = "UPDATE WORK_MAEST SET ESTCODIGO=3 WHERE WORK_REG=$workreg ";
		$err = sql_execute($query,$conn,$trans);
		$deletePer =  "DELETE FROM WORK_PER WHERE WORK_REG = $workreg";
		$err =  sql_execute($deletePer,$conn,$trans);
	}
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Workshop eliminado!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al eliminar el Workshop!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
