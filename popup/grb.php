<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaAPI  . '/timezone.php';
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';

	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$timezone 		= (isset($_POST['timezone'])) ? trim($_POST['timezone']) : '';
	$perempdes 		= (isset($_POST['perempdes'])) ? trim($_POST['perempdes']) : '';
	$percodigo 		= (isset($_POST['percodigo'])) ? trim($_POST['percodigo']) : 0;
	
	
	//--------------------------------------------------------------------------------------------------------------
	$perempdes	= VarNullBD($perempdes, 'S');
	$percodigo	= VarNullBD($percodigo, 'N');
	
	///////////////// Obetengo Timeoffset de la api//////////
	$timoffset=strval(getTimeZone($timezone));
	/////////////////////////////////////////////////////////
	
	if($percodigo!=0){
	
		if($timezone !=''){
			$query = " 	UPDATE PER_MAEST SET 
			TIMREG2='$timezone',TIMOFFSET=$timoffset
			WHERE PERCODIGO=$percodigo ";
		}

		$err = sql_execute($query,$conn,$trans);

		//Establezco la zona horaria
		if($percodigo == $_SESSION[GLBAPPPORT.'PERCODIGO']){ //Si el usuario es el logueado
			$_SESSION[GLBAPPPORT.'TIMOFFSET'] 	= $timoffset;
		}


		if($perempdes != 'NULL'){
			$query = " 	UPDATE PER_MAEST SET 
			PEREMPDES=$perempdes
			WHERE PERCODIGO=$percodigo ";
		}

		$err = sql_execute($query,$conn,$trans);
		
	}
	
	

//--------------------------------------
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
