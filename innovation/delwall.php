<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$percodexp = (isset($_POST['percodexp']))? trim($_POST['percodexp']):0;
	$expregexp = (isset($_POST['expregexp']))? trim($_POST['expregexp']):0;
	
	//--------------------------------------------------------------------------------------------------------------
	$percodexp = VarNullBD($percodexp , 'N');
	$expregexp = VarNullBD($expregexp , 'N');
	if($percodexp!=0 && $expregexp!=0){
		//Elimino el registro
		$query = "UPDATE DES_PER  SET ESTCODIGO=3 WHERE PERCODIGO=$percodexp AND EXPREG=$expregexp ";
		$err = sql_execute($query,$conn,$trans);
		
	}
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Posteo eliminado';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al eliminar el posteo' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
