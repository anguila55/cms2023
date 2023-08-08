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
	
	$ayuperfil 		= (isset($_POST['ayuperfil']))? trim($_POST['ayuperfil']) : 0;
	$ayunumero 		= (isset($_POST['ayunumero']))? trim($_POST['ayunumero']) : '';
	$ayucorreo 		= (isset($_POST['ayucorreo']))? trim($_POST['ayucorreo']) : '';
	$ayufaq 		= (isset($_POST['ayufaq']))? trim($_POST['ayufaq']) : 0;
	

	//--------------------------------------------------------------------------------------------------------------
	
	$query = "	UPDATE ADM_AYUDA SET AYU_CORREO='$ayucorreo', AYU_FAQ=$ayufaq, AYU_NUMERO='$ayunumero', PERCODIGO=$ayuperfil WHERE AYU_ID=0";
	$err = sql_execute($query,$conn,$trans);
	
		
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Guardado correctamente!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
