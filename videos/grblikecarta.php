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
	//Guardo la session
	$conn = sql_conectar(); //Apertura de Conexion

	sql_close($conn);
	//--------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$vidreg 		= (isset($_POST['vidreg']))? trim($_POST['vidreg']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$vidreg		= VarNullBD($vidreg	, 'N');
	$like		= 0;
	if($vidreg!=0){
		//Busco si ya dio like, si es asi lo borro, sino lo inserto
		$query = "	SELECT VIDREG
					FROM VID_LIKE 
					WHERE VIDREG=$vidreg AND PERCODIGO=$percodlog ";						
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$query = " 	DELETE FROM VID_LIKE WHERE VIDREG=$vidreg AND PERCODIGO=$percodlog ";
			$err = sql_execute($query,$conn,$trans);
			$like = -1;

			$query = " 	UPDATE PER_MAEST SET 
					PERQRPUN=PERQRPUN-4
					WHERE PERCODIGO=$percodlog ";

			$err = sql_execute($query,$conn,$trans);
		}else{	
			$query = " 	INSERT INTO VID_LIKE(VIDREG,PERCODIGO)
						VALUES($vidreg,$percodlog) ";
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