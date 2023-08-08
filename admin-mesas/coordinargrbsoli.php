<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/sendfcmmessage.php';
	require_once GLBRutaFUNC.'/sendiosmessage.php';
	require_once GLBRutaFUNC.'/class.phpmailer.php';
	require_once GLBRutaFUNC.'/class.smtp.php';
	require_once GLBRutaAPI  . '/mailchimp.php';
	require_once GLBRutaFUNC . '/constants.php';
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
		
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$solicitante 	= (isset($_POST['solicitante']))? trim($_POST['solicitante']) : 0;  //Codigo de perfil solicitante (logueado)
	$contraparte 	= (isset($_POST['contraparte']))? trim($_POST['contraparte']) : 0; //Codigo de perfil al que se solicita
	$reuestado	= 1; //Reunion sin Confirmar
	$reureg 	= (isset($_POST['reureg']))? trim($_POST['reureg']) : 0;
	$fechasolicitante 	= (isset($_POST['fechasolicitante']))? trim($_POST['fechasolicitante']) : ''; 
	$horasolicitante 	= (isset($_POST['horasolicitante']))? trim($_POST['horasolicitante']) : '';
	$mesasolicitante 	= (isset($_POST['mesasolicitante']))? trim($_POST['mesasolicitante']) : '';
	
	$mesnumero=$mesasolicitante;

	$mesnumero 	= VarNullBD($mesnumero	, 'N');
	//--------------------------------------------------------------------------------------------------------------
	$solicitante 	= VarNullBD($solicitante	, 'N');
	$contraparte 	= VarNullBD($contraparte	, 'N');

	
	if($solicitante!=0){

			$reufecha 	= VarNullBD($fechasolicitante  , 'S');
			$reuhora 	= VarNullBD($horasolicitante  , 'S');

			//Verifico si hubo una confirmacion de reunion simultanea
			$query = "	SELECT REUREG
						FROM REU_CABE 
						WHERE (PERCODSOL=$solicitante OR PERCODDST=$solicitante) AND REUFECHA=$reufecha AND REUHORA=$reuhora AND REUESTADO=2 ";
			$TableReuSimulSol = sql_query($query,$conn);

			//Verifico si hubo una confirmacion de reunion simultanea
			$query = "	SELECT REUREG
						FROM REU_CABE 
						WHERE (PERCODSOL=$contraparte OR PERCODDST=$contraparte) AND REUFECHA=$reufecha AND REUHORA=$reuhora AND REUESTADO=2 ";
			$TableReuSimulDdst = sql_query($query,$conn);

			if($TableReuSimulDdst->Rows_Count>0 || $TableReuSimulSol->Rows_Count>0){
				$errcod = 2;
				$errmsg = 'El horario fue ocupado...';  
			}
			
			// VERIFICO SI CAMBIO DE ESTADO LA REUNION
		$query = " SELECT REUTIPO,MESCODIGO FROM REU_CABE WHERE REUREG=$reureg ";
		$TableTipo = sql_query($query,$conn);
		if($TableTipo->Rows_Count>0){
			$tiporeunion = trim($TableTipo->Rows[0]['REUTIPO']);
			$mescodigo = trim($TableTipo->Rows[0]['MESCODIGO']);
		}
		

		$querymesas = "	SELECT ESTCODIGO
		FROM MES_MAEST 
		WHERE MESCODIGO=$mesnumero
		";
		$Tablemesas = sql_query($querymesas,$conn);
		if($Tablemesas->Rows_Count>0){
			$rowmesas= $Tablemesas->Rows[0];
			$tipodemesa 		= trim($rowmesas['ESTCODIGO']);
	
			if($tipodemesa == 2){
				// BLOQUEO DISPONIBILIDAD DE LA MESA SI ES FLOTANTE
				//Genero un ID 
				$query 		= 'SELECT GEN_ID(G_MESADISP,1) AS ID FROM RDB$DATABASE';
				$TblId		= sql_query($query,$conn,$trans);
				$RowId		= $TblId->Rows[0];			
				$mesdisreg 	= trim($RowId['ID']);
				//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	
				// INSERTO REUNION EN MES DISP
				$query = "	INSERT INTO MES_DISP(MESDISREG,MESCODIGO,MESDISFCH,MESDISHOR,REUREG)
				VALUES($mesdisreg,$mesnumero,$reufecha,$reuhora,$reureg)";
				$err = sql_execute($query,$conn,$trans);
			}
		}
				
		
		
		if ($errcod==0)
		{
			$query = "	UPDATE REU_SOLI SET REUESTADO=2
									WHERE REUREG=$reureg AND REUFECHA=$reufecha AND REUHORA=$reuhora AND REUESTADO=1 ";
						$err = sql_execute($query,$conn,$trans);
			//Actualización dde datos
			$query=" UPDATE REU_CABE SET REUFECHA=$reufecha, REUHORA=$reuhora, REUESTADO=2, AGEREG=NULL,MESCODIGO=$mesnumero WHERE REUREG=$reureg "; //Se actualiza el mescodigo en base a los condicionales impuestos.
			$err = sql_execute($query,$conn,$trans);
		}
		
		//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
	}
		
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Meeting accepted!';      
	}else{            
		sql_rollback_trans($trans);
		if($errcod == 3){
			$errcod = 2;
			$errmsg = ($errmsg=='')? 'Not available meeting table!' : $errmsg;
		}else{
			$errcod = 2;
			$errmsg = ($errmsg=='')? 'Error!' : $errmsg;
		}
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	

	
	
?>	
