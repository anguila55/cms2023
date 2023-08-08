<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	

	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	//Guardo la session
	$conn = sql_conectar(); //Apertura de Conexion

	sql_close($conn);
	//--------------------------------------------------------------------------------------------------------------
	
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$pitchreg 		= (isset($_POST['pitchreg']))? trim($_POST['pitchreg']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$pitchreg		= VarNullBD($pitchreg	, 'N');
	$like		= 0;
	if($pitchreg!=0){
		//Busco si ya dio like, si es asi lo borro, sino lo inserto
		$query = "	SELECT PITCHREG
					FROM PITCH_LIKE 
					WHERE PITCHREG=$pitchreg AND PERCODIGO=$percodlog ";						
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$query = " 	DELETE FROM PITCH_LIKE WHERE PITCHREG=$pitchreg AND PERCODIGO=$percodlog ";
			$err = sql_execute($query,$conn,$trans);
			$like = -1;

			$query = " 	UPDATE PER_MAEST SET 
					PERQRPUN=PERQRPUN-4
					WHERE PERCODIGO=$percodlog ";

			$err = sql_execute($query,$conn,$trans);
		}else{	
			$query = " 	INSERT INTO PITCH_LIKE(PITCHREG,PERCODIGO)
						VALUES($pitchreg,$percodlog) ";
			$err = sql_execute($query,$conn,$trans);	
			$like = 1;

			$query = " 	UPDATE PER_MAEST SET 
					PERQRPUN=PERQRPUN+4
					WHERE PERCODIGO=$percodlog ";

			$err = sql_execute($query,$conn,$trans);
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		//$errmsg = TrMessage('Guardado correctamente!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		//$errmsg = ($errmsg=='')? TrMessage('Guardado correctamente!') : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'","like":"'.$like.'"}';
	
?>