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
	$agereg 	= (isset($_POST['agereg']))? trim($_POST['agereg']) : 0;
	$agepreitm 	= (isset($_POST['agepreitm']))? trim($_POST['agepreitm']) : 0;
	$agepreest 	= (isset($_POST['agepreest']))? trim($_POST['agepreest']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$agereg 	= VarNullBD($agereg 	, 'N');
	$agepreitm 	= VarNullBD($agepreitm 	, 'N');
	$agepreest 	= VarNullBD($agepreest 	, 'N');
	
	switch($agepreest){
		case 1: $estado='ACEPTADO'; break;
		case 2: $estado='REVOCADO'; break;
	}
	
	if($agereg!=0 && $agepreitm!=0){
		//Elimino el registro
		$query = "UPDATE AGE_PREG SET AGEPREEST=$agepreest WHERE AGEREG=$agereg AND AGEPREITM=$agepreitm ";
		$err = sql_execute($query,$conn,$trans);
	}
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = "Pregunta $estado!";
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al cambiar el estado!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
