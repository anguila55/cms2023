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
	$vardescri 		= (isset($_POST['vardescri']))? trim($_POST['vardescri']) : '';
	$vardescriing 		= (isset($_POST['vardescriing']))? trim($_POST['vardescriing']) : '';
	$vardescripor 		= (isset($_POST['vardescripor']))? trim($_POST['vardescripor']) : '';
	$varmost 		= (isset($_POST['varmost']))? trim($_POST['varmost']) : '';
	$varreq 			= (isset($_POST['varreq'])) ? trim($_POST['varreq']) : '';
	$vartipo 			= (isset($_POST['vartipo'])) ? trim($_POST['vartipo']) : '';
	$varopc 			= (isset($_POST['varopc'])) ? trim($_POST['varopc']) : '';
	$varreg 			= (isset($_POST['varreg'])) ? trim($_POST['varreg']) : 0;
	$usuario 			= (isset($_POST['usuario'])) ? trim($_POST['usuario']) : 0;

	if($vartipo == '0'){
		$varopc = '';
	}

	//--------------------------------------------------------------------------------------------------------------
	$vardescri		= VarNullBD($vardescri		, 'S');
	$vardescriing		= VarNullBD($vardescriing		, 'S');
	$vardescripor		= VarNullBD($vardescripor		, 'S');
	$varmost			= VarNullBD($varmost, 'S');
	$varreq			= VarNullBD($varreq, 'S');
	$vartipo			= VarNullBD($vartipo, 'N');
	$varopc			= VarNullBD($varopc, 'S');
	$varreg			= VarNullBD($varreg, 'N');
	$usuario			= VarNullBD($usuario, 'N');


	if ($varreg == 0) {
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_VARIABLES,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query, $conn, $trans);
		$RowId		= $TblId->Rows[0];
		$varreg 	= trim($RowId['ID']);
		$vartitulo = 'variable'.$varreg;


		$vartitulo			= VarNullBD($vartitulo, 'S');
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

		$query = " 	INSERT INTO VAR_MAEST(VARREG,VARDESCRI,VARDESCRIING,VARDESCRIPOR,VARMOST,VARREQ,VARTITULO,VARTIPO,VAROPC,USUARIO)
						VALUES($varreg,$vardescri,$vardescriing,$vardescripor,$varmost,$varreq,$vartitulo,$vartipo,$varopc,$usuario) ";

		$err = sql_execute($query,$conn,$trans);	
		}else{

			$query = " 	UPDATE VAR_MAEST SET 
					VARDESCRI=$vardescri, VARDESCRIING=$vardescriing,VARDESCRIPOR=$vardescripor,VARMOST=$varmost,VARREQ=$varreq,VARTIPO=$vartipo,VAROPC=$varopc
					WHERE VARREG=$varreg ";
			$err = sql_execute($query,$conn,$trans);	
		}
	
	
	
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Error al guardar!') : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
