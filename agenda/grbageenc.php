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
	$agereg 		= (isset($_POST['agereg']))? trim($_POST['agereg']) : 0;
	$encreg 		= (isset($_POST['encreg']))? trim($_POST['encreg']) : 0;
		
	if($encreg==0 || trim($encreg)==''){
		$errcod=2;
		$errmsg='Falta seleccionar la Encuesta';
	}
	
	//Verifico si ya existe
	if($errcod!=2){
		$query = "SELECT AGEREG FROM AGE_ENCU WHERE AGEREG=$agereg AND ENCREG=$encreg ";
		$Table = sql_query($query,$conn,$trans);
		if($Table->Rows_Count>0){
			$errcod=2;
			$errmsg='Ya existe la encuesta para la agenda.';
		}
	}

	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	
	//--------------------------------------------------------------------------------------------------------------
	$encreg		= VarNullBD($encreg		, 'N');
	$agereg		= VarNullBD($agereg		, 'N');
	
	$query = " 	INSERT INTO AGE_ENCU(AGEREG,ENCREG)
				VALUES($agereg,$encreg) ";
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
