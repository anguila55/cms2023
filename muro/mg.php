<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//Control de Datos
	$percodigo 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$murreg = (isset($_POST['murreg']))? trim($_POST['murreg']) : 0;
	$datamg = (isset($_POST['datamg']))? trim($_POST['datamg']) : 0;
	
	//--------------------------------------------------------------------------------------------------------------
	
	if($datamg==1){
	
			$query = " 	DELETE FROM MUR_MGT WHERE PERCODIGO=$percodigo AND MURREG=$murreg ";
			$err = sql_execute($query,$conn);
			$query = " 	UPDATE PER_MAEST SET 
					PERQRPUN=PERQRPUN-2
					WHERE PERCODIGO=$percodigo ";

			$err = sql_execute($query,$conn);
			
	}else{
			//Creo la relacion de favorito
			$query = " 	INSERT INTO MUR_MGT (PERCODIGO, MURREG)
						VALUES ($percodigo, $murreg)";
			$err = sql_execute($query,$conn);
			$query = " 	UPDATE PER_MAEST SET 
					PERQRPUN=PERQRPUN+1
					WHERE PERCODIGO=$percodigo ";

			$err = sql_execute($query,$conn);
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>