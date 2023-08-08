<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);

	
	
	//Control de Datos
	$paicodigo 		= (isset($_POST['paicodigo']))? trim($_POST['paicodigo']) : 0;
	$pailet 		= (isset($_POST['pailet']))? trim($_POST['pailet']) : '';
	$paidescri 		= (isset($_POST['paidescri']))? trim($_POST['paidescri']) : '';
	$paidescriing 		= (isset($_POST['paidescriing']))? trim($_POST['paidescriing']) : '';
	$paireg 		= (isset($_POST['paireg']))? trim($_POST['paireg']) : '';
	$timereg 		= (isset($_POST['timereg']))? trim($_POST['timereg']) : '';
	
	
	//$paidescri = utf8_code($paidescri); 

	//--------------------------------------------------------------------------------------------------------------
	$paicodigo 		= VarNullBD($paicodigo		, 'N');
	$pailet		= VarNullBD($pailet		, 'S');
	$paidescri			= VarNullBD($paidescri			, 'S');
	$paidescriing		= VarNullBD($paidescriing		, 'S');
	$paireg		= VarNullBD($paireg		, 'S');
	$timereg		= VarNullBD($timereg		, 'N');
	
	
	
	if($paicodigo==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
	
	}else{
		$query = " 	UPDATE TBL_PAIS SET 
					PAICODIGO=$paicodigo, PAILET=$pailet,PAIDESCRI=$paidescri,PAIDESCRIING=$paidescriing,PAIREG=$paireg,TIMEREG=$timereg
					WHERE PAICODIGO=$paicodigo ";
	}
	$err = sql_execute($query,$conn,$trans);	
	
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Guardado correctamente!') : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
