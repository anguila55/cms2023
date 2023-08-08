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
	$timcodigomst 		= (isset($_POST['timcodigomst']))? trim($_POST['timcodigomst']) : 0;
	$timoffsetmst 		= (isset($_POST['timoffsetmst']))? trim($_POST['timoffsetmst']) : '';
	$timoffsetdstmst 		= (isset($_POST['timoffsetdstmst']))? trim($_POST['timoffsetdstmst']) : '';
	$zonahoritmmst 		= (isset($_POST['zonahoritmmst']))? trim($_POST['zonahoritmmst']) : '';
	
	

	//--------------------------------------------------------------------------------------------------------------
	$timcodigomst 		= VarNullBD($timcodigomst		, 'S');
	$timoffsetmst		= VarNullBD($timoffsetmst		, 'N');
	$timoffsetdstmst			= VarNullBD($timoffsetdstmst			, 'N');
	$zonahoritmmst		= VarNullBD($zonahoritmmst		, 'N');
	
	
	if($zonahoritmmst==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
	
	}else{
		$query = " 	UPDATE TIM_ZONE SET 
					TIMDESCRI=$timcodigomst, TIMOFFSET=$timoffsetmst,TIMOFFSETDST=$timoffsetdstmst
					WHERE TIMREG=$zonahoritmmst ";
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
