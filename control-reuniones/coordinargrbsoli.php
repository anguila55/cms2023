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
	$percodigo 	= (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;  //Codigo de perfil solicitante (logueado)
	$percodsol 	= (isset($_POST['percodsol']))? trim($_POST['percodsol']) : 0; //Codigo de perfil al que se solicita
	$reuestado	= 1; //Reunion sin Confirmar
	$reureg 	= (isset($_POST['reureg']))? trim($_POST['reureg']) : 0;
	$reuconfnoti 	= (isset($_POST['reuconfnoti']))? trim($_POST['reuconfnoti']) : 0; //Codigo de perfil al que se solicita

	//--------------------------------------------------------------------------------------------------------------
	$percodigo 	= VarNullBD($percodigo	, 'N');
	$mesnumero=0;
	
	if($percodsol!=0){

		foreach($_POST['dataCoordinar'] as $ind => $data){
			$fecha 	= $data['fecha'];
			$hora 	= $data['hora'];
			
			$reufecha 	= ConvFechaBD($fecha);
			$reuhora 	= VarNullBD($hora  , 'S');

			//Verifico si hubo una confirmacion de reunion simultanea
			$query = "	SELECT REUREG
						FROM REU_CABE 
						WHERE (PERCODSOL=$percodsol OR PERCODDST=$percodsol) AND REUFECHA=$reufecha AND REUHORA=$reuhora AND REUESTADO=2 ";
			$TableReuSimulSol = sql_query($query,$conn);

			//Verifico si hubo una confirmacion de reunion simultanea
			$query = "	SELECT REUREG
						FROM REU_CABE 
						WHERE (PERCODSOL=$percodigo OR PERCODDST=$percodigo) AND REUFECHA=$reufecha AND REUHORA=$reuhora AND REUESTADO=2 ";
			$TableReuSimulDdst = sql_query($query,$conn);

			if($TableReuSimulDdst->Rows_Count>0 || $TableReuSimulSol->Rows_Count>0){
				$errcod = 2;
				$errmsg = 'El horario fue ocupado... Refresque y elija otro';  

			}

		// VERIFICO SI CAMBIO DE ESTADO LA REUNION
		$query = " SELECT REUTIPO,MESCODIGO FROM REU_CABE WHERE REUREG=$reureg ";
		$TableTipo = sql_query($query,$conn);
		if($TableTipo->Rows_Count>0){
			$tiporeunion = trim($TableTipo->Rows[0]['REUTIPO']);
			$mescodigo = trim($TableTipo->Rows[0]['MESCODIGO']);
		}
			// SI ES VIRTUAL NO HAGO NADA
		if($tiporeunion==1){

		
            // SI ES PRESENCIAL BORRO SI HAY FLOTANTE Y BUSCO NUEVA
				// BUSCO LA MESA FLOTANTE 
				$querymesa = " 	SELECT MESCODIGO 
				FROM MES_DISP 
				WHERE REUREG=$reureg";

				$TableMesa = sql_query($querymesa,$conn);
				if($TableMesa->Rows_Count>0){
				$query=" DELETE FROM MES_DISP WHERE REUREG=$reureg ";
				$err = sql_execute($query,$conn,$trans);

					// SI CAMBIO HORARIO BUSCO MESA NUEVA 
				
										
						$buscoflotante = 1;	
							//BUSCO MESA FLOTANTE 
						$queryflotante = " 	SELECT MESCODIGO 
						FROM MES_MAEST
						WHERE ESTCODIGO=2";

						$TableFlotante = sql_query($queryflotante,$conn);

						for($m=0; $m<$TableFlotante->Rows_Count; $m++){

							$rowFlotante= $TableFlotante->Rows[$m];
							$mesnumero = trim($rowFlotante['MESCODIGO']);

							// BUSCO SI ESTA LIBRE EL HORARIO
									$queryhorariomesa = " 	SELECT MESCODIGO 
									FROM MES_DISP
									WHERE MESCODIGO=$mesnumero AND MESDISFCH=$reufecha AND MESDISHOR=$reuhora";

									$TableHM = sql_query($queryhorariomesa,$conn);
									if($TableHM->Rows_Count>0){
										$rowmesa = $TableHM->Rows[0];
										$mesnumero 	= trim($rowmesa['MESCODIGO']);
									}else{
										
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

									$buscoflotante =2;

									break;
									}
							

							}

							if($buscoflotante == 1){
								$errcod = 3;
							}	
					

				}else{

					if($mescodigo){
						$mesnumero = $mescodigo;
					}else{
						$mesnumero=0;
					}

					
				}

			}
		}
		if ($errcod==0)
		{

			$query = "	UPDATE REU_SOLI SET REUESTADO=2
									WHERE REUREG=$reureg AND REUFECHA=$reufecha AND REUHORA=$reuhora AND REUESTADO=1 ";
						$err = sql_execute($query,$conn,$trans);
			//Actualización dde datos
			$query=" UPDATE REU_CABE SET REUFECHA=$reufecha, REUHORA=$reuhora, REUESTADO=2, AGEREG=NULL, MESCODIGO=$mesnumero WHERE REUREG=$reureg "; //Se actualiza el mescodigo en base a los condicionales impuestos.
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
