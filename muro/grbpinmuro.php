<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$peradmin  = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';
	//--------------------------------------------------------------------------------------------------------------
	
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$murreg 		= (isset($_POST['murreg']))? trim($_POST['murreg']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$murreg		= VarNullBD($murreg	, 'N');
	$pin		= 0;
	if($murreg!=0){
		//Busco si ya dio pin, si es asi lo borro, sino lo inserto
		$query = "	SELECT MURREG
					FROM MUR_PIN 
					WHERE MURREG=$murreg ";						
		$Table = sql_query($query,$conn);
		
		if($Table->Rows_Count>0){
			$query = " 	DELETE FROM MUR_PIN WHERE MURREG=$murreg ";
			$err = sql_execute($query,$conn,$trans);
			$pin = -1;

			
		}else{	
			$query = " 	INSERT INTO MUR_PIN(MURREG,PERCODIGO)
						VALUES($murreg,$percodigo) ";
			$err = sql_execute($query,$conn,$trans);	
			$pin = 1;
	
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
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'","pin":"'.$pin.'"}';
	
?>