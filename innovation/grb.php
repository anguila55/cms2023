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
	
	
	//--------------------------------------------------------------------------------------------------------------
	$expreg = (isset($_POST['expreg']))? trim($_POST['expreg']):0;
	$percodigo = (isset($_POST['percodigo']))? trim($_POST['percodigo']):0;
	$resumo 		= (isset($_POST['resumo']))? trim($_POST['resumo']) : '';
	$linkvideo 		= (isset($_POST['linkvideo']))? trim($_POST['linkvideo']) : '';
	
	
	if($percodigo!=0 && $expreg!=0){
		
		
		//Verifico si existe la relacion 
		$existeRel=false;
		$query = "	SELECT PERCODIGO
					FROM DES_PER  
					WHERE PERCODIGO=$percodigo AND EXPREG=$expreg ";		
		$TableCtrl = sql_query($query,$conn);
		if($TableCtrl->Rows_Count>0){
			$existeRel=true;
			$query = " 	UPDATE DES_PER SET 
					DESDESC='$resumo', DESLINK='$linkvideo', ESTCODIGO=1
					WHERE PERCODIGO=$percodigo AND EXPREG=$expreg";
					$err = sql_execute($query,$conn);
		}
			
		if($existeRel==false){
			$query = "	INSERT INTO DES_PER (PERCODIGO, EXPREG, DESDESC, DESLINK, ESTCODIGO) 
					VALUES ($percodigo, $expreg, '$resumo', '$linkvideo', 1)";
			
			$err = sql_execute($query,$conn);
		}

		
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
