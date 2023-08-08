<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$mescodigo 	= (isset($_POST['mescodigo']))? trim($_POST['mescodigo']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$mescodigo = VarNullBD($mescodigo , 'N');
	
	if($mescodigo!=0){
		

		/// BUSCAR INFO DE LA MESA SI ES FIJA O FLOTANTE
		$queryinfo = "	SELECT MESCODIGO, MESNUMERO, ESTCODIGO, PERCODIGO
					FROM MES_MAEST
					WHERE MESCODIGO=$mescodigo";

		$Table = sql_query($queryinfo,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$mescodigo = trim($row['MESCODIGO']);
			$mesnumero = trim($row['MESNUMERO']);
			$estcodigo = trim($row['ESTCODIGO']);
			$usuario = trim($row['PERCODIGO']);

			$usuarioeliminado = false;
			if($usuario != '0'){

				$queryEliminado = "	SELECT ESTCODIGO
				FROM PER_MAEST 
				WHERE PERCODIGO=$usuario AND ESTCODIGO<>3";
				$TableEliminado = sql_query($queryEliminado,$conn);

				if($TableEliminado->Rows_Count > 0){
					
				}else{
					$usuarioeliminado = true;
				}

			}

			if(!$usuarioeliminado){
				
			$query = "	SELECT REUREG, REUFECHA,REUHORA,PERCODSOL,PERCODDST
			FROM REU_CABE 
			WHERE MESCODIGO=$mescodigo AND REUTIPO=1 ";
			$TableReunionesMesa = sql_query($query,$conn);

			for ($i = 0; $i < $TableReunionesMesa->Rows_Count; $i++) {
				$rowmesa = $TableReunionesMesa->Rows[$i];
				$reureg = trim($rowmesa['REUREG']);
				$reufecha = trim($rowmesa['REUFECHA']);
				$reuhora = trim($rowmesa['REUHORA']);
				$percodsol = trim($rowmesa['PERCODSOL']);
				$percoddst = trim($rowmesa['PERCODDST']);

				// ES MESA FIJA  // BUSCAR MOVER LAS REUNIONES CON ESA MESA ASIGNADA Y LLEVARLAS A UNA FLOTANTE

				// ES MESA FLOTANTE // MODIFICAR LAS REUNIONES EN MESA FLOTANTE DEL USUARIO Y ASIGNARLA LA MESA FIJA
				// ELIMINAR DISPONIBILIDAD DE LA FLOTANTE
						
							$buscoflotante = 0;
							/// BUSCO MESA FIJA
							// BUSCO LA MESA DEL RECEPTOR 
							$querymesa = " 	SELECT MESCODIGO 
							FROM MES_MAEST 
							WHERE PERCODIGO=$percoddst AND ESTCODIGO<>3 AND MESCODIGO<>$mescodigo";

							$TableMesa = sql_query($querymesa,$conn);
							if($TableMesa->Rows_Count>0){
								$rowmesa = $TableMesa->Rows[0];
								$mesnumeronuevo 	= trim($rowmesa['MESCODIGO']);
							}else{

							// BUSCO MESA SOLICITANTE	
							$querymesa2 = " 	SELECT MESCODIGO 
							FROM MES_MAEST 
							WHERE PERCODIGO=$percodsol AND ESTCODIGO<>3  AND MESCODIGO<>$mescodigo";

							$TableMesa2 = sql_query($querymesa2,$conn);

							if($TableMesa2->Rows_Count>0){
							$rowmesa2 = $TableMesa2->Rows[0];
							$mesnumeronuevo 	= trim($rowmesa2['MESCODIGO']);	
							}else{

							$buscoflotante = 1;	
								//BUSCO MESA FLOTANTE 
							$queryflotante = " 	SELECT MESCODIGO 
							FROM MES_MAEST
							WHERE ESTCODIGO=2 AND MESCODIGO<>$mescodigo";

							$TableFlotante = sql_query($queryflotante,$conn);

							for($m=0; $m<$TableFlotante->Rows_Count; $m++){

								$rowFlotante= $TableFlotante->Rows[$m];
								$mesnumeronuevo = trim($rowFlotante['MESCODIGO']);

								// BUSCO SI ESTA LIBRE EL HORARIO
										$queryhorariomesa = " 	SELECT MESCODIGO 
										FROM MES_DISP
										WHERE MESCODIGO=$mesnumeronuevo AND MESDISFCH='$reufecha' AND MESDISHOR='$reuhora'";

										$TableHM = sql_query($queryhorariomesa,$conn);
										if($TableHM->Rows_Count>0){
											$rowmesa = $TableHM->Rows[0];
											$mesnumeronuevo 	= trim($rowmesa['MESCODIGO']);
										}else{
											
										//Genero un ID 
										$query 		= 'SELECT GEN_ID(G_MESADISP,1) AS ID FROM RDB$DATABASE';
										$TblId		= sql_query($query,$conn,$trans);
										$RowId		= $TblId->Rows[0];			
										$mesdisreg 	= trim($RowId['ID']);
										//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

										// INSERTO REUNION EN MES DISP
										$query = "	INSERT INTO MES_DISP(MESDISREG,MESCODIGO,MESDISFCH,MESDISHOR,REUREG)
										VALUES($mesdisreg,$mesnumeronuevo,'$reufecha','$reuhora',$reureg)";
										$err = sql_execute($query,$conn,$trans);

										$buscoflotante =2;

										break;
										}
								

								}

								if($buscoflotante == 1){
									$errcod = 3;

								}

							}
							}
				//


				
		
				if ( ($mesnumeronuevo != 0) && ($errcod !=3) ) {
					
					$query = "UPDATE REU_CABE SET MESCODIGO=$mesnumeronuevo WHERE REUREG=$reureg ";
					$err = sql_execute($query,$conn,$trans);

					if($estcodigo == 2){ // ES FLOTANTE Y ELIMINO LOS MES_DISP
						$query=" DELETE FROM MES_DISP WHERE MESCODIGO=$mescodigo ";
						$err = sql_execute($query,$conn,$trans);
					}


				}
				
			}

			}

				//Elimino el registro
				$query = "UPDATE MES_MAEST SET ESTCODIGO=3 WHERE MESCODIGO=$mescodigo ";
				$err = sql_execute($query,$conn,$trans);

		}
}
	
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Mesa eliminada!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al eliminar la mesa, debe tener reuniones que no se pueden reasignar!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
