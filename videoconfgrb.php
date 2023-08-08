<?
	if (!isset($_SESSION))  session_start();
	//include($_SERVER["DOCUMENT_ROOT"] . '/webcoordinador/func/zglobals.php'); //DEV
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo 	= (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
	$parametro 	= (isset($_POST['param']))? trim($_POST['param']) : '';
	$reulink 	= (isset($_POST['reulink']))? trim($_POST['reulink']) : '';
	$errcod = 0;
	$errmsg = '';
	
	if ($percodigo != '' && $parametro!='' && $reulink!='') {
		
		$parametro 	= str_replace(md5('OnLiFeeOuNnCiRTada'),'',$parametro);
		$parametro 	= base64_decode($parametro);
		$parametro 	= str_replace(md5('NnCiRoEnTadaOnLf'),'',$parametro);
		$parametro 	= base64_decode($parametro);
		$parametro 	= str_replace(md5('NnCiRoEnTadaOnLf'),'',$parametro);
		$reureg 	= str_replace(md5('OnLiFeRCeOuNnCiRoEnTada'),'',$parametro);
		
		$conn = sql_conectar(); //Apertura de Conexion
		$trans	= sql_begin_trans($conn);
				
		$query = " UPDATE REU_CABE SET REULINK='$reulink' WHERE REUREG=$reureg ";
		$err = sql_execute($query,$conn,$trans);	
		
		//--------------------------------------------------------------------------------------------------------------
		if($err == 'SQLACCEPT' && $errcod==0){
			sql_commit_trans($trans);
			$errcod = 0;
			$errmsg = 'Guardado correctamente!';
		}else{            
			sql_rollback_trans($trans);
			$errcod = 2;
			$errmsg = 'Error al guardar';
		}	
		//--------------------------------------------------------------------------------------------------------------
		sql_close($conn);	
				
	}else{
		$errcod=2;
		$errmsg='Faltan datos en sistema.';
	}
	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
	
?>	
