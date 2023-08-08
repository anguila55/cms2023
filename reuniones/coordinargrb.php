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
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
		
	$SendMailReporteReuniones = 'fernando.pereyra@onlife.com.ar';
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$percodigo 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : ''; //Codigo de perfil solicitante (logueado)
	$percodsol 	= (isset($_POST['percodsol']))? trim($_POST['percodsol']) : 0; //Codigo de perfil al que se solicita
	$reuestado	= 1; //Reunion sin Confirmar
	$reureg 	= (isset($_POST['reureg']))? trim($_POST['reureg']) : 0;
	$tiporeunion 	= (isset($_POST['tiporeunion']))? trim($_POST['tiporeunion']) : 0;

	$mesnumero = 0;
	//--------------------------------------------------------------------------------------------------------------
	$percodigo 	= VarNullBD($percodigo	, 'N');
	$query="SELECT MSGTITULO,MSGDESCRI, MSGSUB, MSGBOT, MSGLNK, MSGIMG FROM MSG_CABE WHERE MSGREG=7";
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$msgtitulo 	= trim($row['MSGTITULO']);
		$msgdescri	= trim($row['MSGDESCRI']);
		$msgsub = trim($row['MSGSUB']);
		$msgbot = trim($row['MSGBOT']);
		$msglnk = trim($row['MSGLNK']);
		$msgimg = trim($row['MSGIMG']);
		$msgdescri = htmlspecialchars_decode($msgdescri);
		$msgdescri = str_replace('class="ql-align-center"','style="text-align:center"',$msgdescri);
		$msgdescri = str_replace('class="ql-align-justify"','style="text-align:justify"',$msgdescri);
		$msgdescri = str_replace('class="ql-align-right"','style="text-align:right"',$msgdescri);

		$query="SELECT MSGREP, MSGCC, MSGCCO FROM MSG_CABE WHERE MSGREG=10";
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$msgrep 	= trim($row['MSGREP']);
		$msgcc	= trim($row['MSGCC']);
		$msgcco	= trim($row['MSGCCO']);
	
	//Busco si existe una reunion soliciada y sin confirmar
	/*$query = "	SELECT R.REUREG
				FROM REU_CABE R 
				WHERE R.PERCODSOL=$percodsol AND R.PERCODDST=$percodigo AND R.REUESTADO=1 ";
	$TableReu = sql_query($query,$conn);

	if($TableReu->Rows_Count>0){
		$reureg = trim($TableReu->Rows[0]['REUREG']);
	}*/
	
	if($percodsol!=0){
		//Seleccionamos los datos de el solicitado
		$querysol ="SELECT MESCODIGO, PERTIPO,PERCORREO, PERNOMBRE, PERAPELLI, PERCOMPAN
					FROM PER_MAEST  
					WHERE PERCODIGO=$percodsol" ;
		$Table = sql_query($querysol,$conn);
		$row= $Table->Rows[0];
		$mesasol 	= trim($row['MESCODIGO']);
		$tiposol 	= trim($row['PERTIPO']);
		$correosol 	= trim($row['PERCORREO']);
		$nombrerecibe 	= trim($row['PERNOMBRE']);
		$apellidorecibe 	= trim($row['PERAPELLI']);
		$empresarecibe 	= trim($row['PERCOMPAN']);

		//Seleccionamos los datos del solicitante
		$querydest ="SELECT MESCODIGO, PERTIPO 
					 FROM PER_MAEST  
					 WHERE PERCODIGO=$percodigo" ;
		$Table = sql_query($querydest,$conn);
		$row= $Table->Rows[0];
		$mesadest 	= trim($row['MESCODIGO']);
		$tipodest = trim($row['PERTIPO']);

	

		$cambioHorario =false;
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
			if ($errcod==0){
				//Verifico si hubo un cambio de horario-dia
				$query = "	SELECT REUFECHA,REUHORA
				FROM REU_SOLI 
				WHERE REUREG=$reureg AND REUFECHA=$reufecha AND REUHORA=$reuhora AND REUESTADO=1 ";
				$TableChk = sql_query($query,$conn);
				if($TableChk->Rows_Count>0){
					$cambioHorario=false;
					$query = "	UPDATE REU_SOLI SET REUESTADO=2
								WHERE REUREG=$reureg AND REUFECHA=$reufecha AND REUHORA=$reuhora AND REUESTADO=1 ";
					$err = sql_execute($query,$conn,$trans);
				}else{
					$cambioHorario=true;

					$query = "	INSERT INTO REU_SOLI(REUREG,REUFECHA,REUHORA,REUESTADO)
								VALUES($reureg,$reufecha,$reuhora,2)";
					$err = sql_execute($query,$conn,$trans);
				}

			}
			
		}

		$cambiotipo = false;
		// VERIFICO SI CAMBIO DE ESTADO LA REUNION
		$query = " SELECT REUTIPO FROM REU_CABE WHERE REUREG=$reureg ";
		$TableTipo = sql_query($query,$conn);
		if($TableTipo->Rows_Count>0){
			$tipoviejo = trim($TableTipo->Rows[0]['REUTIPO']);
			
			if ($tipoviejo != $tiporeunion){
				$cambiotipo = true;
			}
		
		}




		if ($errcod==0)
		{

				
				/// SI CAMBIO FECHA Y HORA Y ES FLOTANTE ELIMINAR LA ANTERIOR DE MES_DISP Y VOLVER A BUSCAR UNA REUNION FLOTANTE, EDITAR REU_CABE CON LA NUEVA MESA
				/// SI PASA DE REUNION PRESENCIAL A DIGITAL - ELIMINAR SI ES FLOTANTE MES_DISP Y BORRAR MESCODIGO DE REU_CABE
				$buscoflotante = 0;	
				if( ( ($cambioHorario) && ($tiporeunion == 1) ) || ( ($cambiotipo) && ($tiporeunion == 0) ) ){

					// BUSCO LA MESA FLOTANTE 
					$querymesa = " 	SELECT MESCODIGO 
					FROM MES_DISP 
					WHERE REUREG=$reureg";

					$TableMesa = sql_query($querymesa,$conn);
					if($TableMesa->Rows_Count>0){
					$query=" DELETE FROM MES_DISP WHERE REUREG=$reureg ";
					$err = sql_execute($query,$conn,$trans);
					
					// SI CAMBIO HORARIO BUSCO MESA NUEVA 
					if($tiporeunion == 1){  
						
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
					}
				
					}
					
				}else if ( ($cambiotipo) && ($tiporeunion == 1) ){
				/// SI PASA DE DIGITAL A PRESENCIAL - BUSCAR MESA AGREGAR A MES_DISP SI ES FLOTANTE Y EDITAR REU_CABE
				$buscoflotante = 0;
				/// BUSCO MESA FIJA
				// BUSCO LA MESA DEL RECEPTOR 
				$querymesa = " 	SELECT MESCODIGO 
				FROM MES_MAEST 
				WHERE PERCODIGO=$percodigo AND ESTCODIGO<>3";
	
				$TableMesa = sql_query($querymesa,$conn);
				if($TableMesa->Rows_Count>0){
					$rowmesa = $TableMesa->Rows[0];
					$mesnumero 	= trim($rowmesa['MESCODIGO']);
				}else{
	
				// BUSCO MESA SOLICITANTE	
				$querymesa2 = " 	SELECT MESCODIGO 
				FROM MES_MAEST 
				WHERE PERCODIGO=$percodsol AND ESTCODIGO<>3";
	
				$TableMesa2 = sql_query($querymesa2,$conn);
	
				if($TableMesa2->Rows_Count>0){
				$rowmesa2 = $TableMesa2->Rows[0];
				$mesnumero 	= trim($rowmesa2['MESCODIGO']);	
				}else{
	
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
	
				}
				}

				}


				 
				
				


				/// SI SIGUE TODO IGUAL NO ACTUALIZAR EL MESCODIGO
			if ($mesnumero !=0){
				// NUEVA MESA FLOTANTE
				$variablequery = ", MESCODIGO=$mesnumero";
			}

			if ( ($cambiotipo) && ($tiporeunion == 0) ){
				$variablequery = ", MESCODIGO=0";
			}


			//Elimino los horarios solicitados, para dejar el confirmado
		$query = "DELETE FROM REU_SOLI WHERE REUREG=$reureg AND REUESTADO=1 ";
		$err = sql_execute($query,$conn,$trans);
		//Actualización dde datos
		$query=" UPDATE REU_CABE SET REUFECHA=$reufecha, REUHORA=$reuhora $variablequery, REUESTADO=2, AGEREG=NULL, REUTIPO =$tiporeunion WHERE REUREG=$reureg "; //Se actualiza el mescodigo en base a los condicionales impuestos.
		$err = sql_execute($query,$conn,$trans);
		
		$evefch = substr($fecha, 6, 4) . '-' . substr($fecha, 3, 2) . '-' . substr($fecha, 0, 2).' '.$hora;
		$time = strtotime($hora);
		$horareunionnueva = date("H:i", strtotime('+30 minutes', $time));
		$evefchfin = substr($fecha, 6, 4) . '-' . substr($fecha, 3, 2) . '-' . substr($fecha, 0, 2).' '.$horareunionnueva;

		switch($IdiomView){
			case 'ESP':
				$msgCambioHorario ='Reunión Confirmada';
	
				if($cambioHorario){
					$msgCambioHorario ='Reunión confirmada con cambio de horario';
				}

				if($cambiotipo){
					$msgCambioHorario ='Reunión confirmada con cambio de tipo';
				}
				break;
			case 'POR':
					$msgCambioHorario ='Reunião Confirmada';
		
					if($cambioHorario){
						$msgCambioHorario ='Reunião confirmada com mudança de horário';
					}
	
					if($cambiotipo){
						$msgCambioHorario ='Reunião confirmada com mudança de tipo';
					}
					break;
			case 'ING':
				$msgCambioHorario ='Meeting Confirmed';
	
				if($cambioHorario){
					$msgCambioHorario ='Meeting confirmed with schedule change';
				}

				if($cambiotipo){
					$msgCambioHorario ='Meeting confirmed with type change';
				}
				break;			
		}	
		
		
		//Inserto Notificacion de Aceptacion
		$query = " INSERT INTO NOT_CABE (NOTREG, NOTFCHREG, NOTTITULO, NOTFCHLEI, PERCODDST, NOTESTADO, PERCODORI, REUREG, NOTCODIGO)
					VALUES (GEN_ID(G_NOTIFICACION,1), CURRENT_TIMESTAMP, '$msgCambioHorario', NULL, $percodsol, 1, $percodigo, $reureg, 2); ";
		$err = sql_execute($query,$conn,$trans);
		
		
		//Envio una notifiacion mobile
		//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
		//Busco si el destino tiene mobile
		$query = "	SELECT N.ID,N.PROVIDER 
					FROM NOT_REGI N 
					WHERE N.PERCODIGO=$percodsol ";
		$TableMobil = sql_query($query,$conn);
		if($TableMobil->Rows_Count>0){
			$id = trim($TableMobil->Rows[0]['ID']);
			$provider = trim($TableMobil->Rows[0]['PROVIDER']);
			
			//Busco los datos de la empresa que solicita la reunion
			$query = "	SELECT PERNOMBRE,PERAPELLI,PERCOMPAN
						FROM PER_MAEST
						WHERE PERCODIGO=$percodigo";
			$TableOrigen = sql_query($query,$conn,$trans);
			$pernombre = trim($TableOrigen->Rows[0]['PERNOMBRE']);
			$perapelli = trim($TableOrigen->Rows[0]['PERAPELLI']);
			$percompan = trim($TableOrigen->Rows[0]['PERCOMPAN']);
			
			$titulo 	= $msgCambioHorario;
			$message 	= "$perapelli $pernombre from $percompan./ ";
			
			if($provider=='FCM'){
				$target = array();
				array_push($target,$id);
				$data =  array('title'=>$titulo,
							   'badge_number'=>1,
							   'server_message'=>'',
							   'text'=>$message,
							   'id'=>$reureg);
							   
				sendFCMMessage($data,$target);
			}else{
				sendIOSMessage($message, $id);
			}
		}
		//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
		
		//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
		//Envio Mail de Reunion Solicitada al Destino
		if($correosol!=''){
			
				$percompan = $_SESSION[GLBAPPPORT.'PERCOMPAN'];
				$pernombre = $_SESSION[GLBAPPPORT.'PERNOMBRE'];
				$perapelli = $_SESSION[GLBAPPPORT.'PERAPELLI'];
				$msgdescriaux = str_replace("*|Nombre Usuario|*",$nombrerecibe.' '.$apellidorecibe,$msgdescri);
				$msgdescriaux = str_replace("*|Nombre Contraparte|*",$pernombre.' '.$perapelli,$msgdescriaux);
				$msgdescriaux = str_replace("*|Empresa Usuario|*",$empresarecibe,$msgdescriaux);
				$msgdescriaux = str_replace("*|Correo Usuario|*",$correosol,$msgdescriaux);

			$msgdescriaux = str_replace("*|Empresa Contraparte|*",$percompan,$msgdescriaux);
			
			$body ='<!DOCTYPE html>
			<html lang="en" class="loading">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				</head>
		
			<body>
			<div style="text-align:center">
				<img src=' . getUrl("/mailsimg/7/$msgimg") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
				<!--app-assets/img/logo-light.png
				-->
				<br>
			</div>
			<div dir="ltr">
				
				<br>
				<br>
		
				<font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
					<div>'.$msgdescriaux.'</div>
				</font>

				<div style="text-align:center; margin-top: 30px;">
					<b><a style="background-color: grey;color:white;padding: 15px;font-size: 15px;border-radius:20px;text-decoration: none;" href="' . URL_WEB . 'reuniones/bsq?T=1">'.$msgbot.'</a></b>
				</div>
				<div style="text-align:center; margin-top:10px;">
					<a style="background-color: #286efa!important;border: none;color: white;padding: 15px 32px;text-align:center;text-decoration: none;display: inline-block;border-radius: 8px;font-size: 16px;" href="https://www.addevent.com/dir/?client=atcUIPxJKzRJHYfkDmWv110113&start='.$evefch.'&end='.$evefchfin.'&title=Reunion Confirmada&description='.$msgdescriaux.'&location='.URL_WEB.'reuniones/bsq?T=1">Add to Calendar</a>
				</div>
			</div>
		</body>
		</html>';

			
		$tagnombreevento = preg_replace('/\s+/', '-', NAME_TITLE);
		if ($msgcc!=''){
			$mail = [
				"from_email" => SEND_MAIL_USUARIO,
				"from_name" => $msgcco,
				"subject" => $msgsub,
				"html" => $body,
				"tags"=> [
					$tagnombreevento."_7"
				],
				"headers" =>[

					"Reply-To" => $msgrep
				],
				"to" => [
					[
						"email" => $correosol,
						"type" => "to"
					],
					[
						"email" => $msgcc,
						"type" => "cc"
					]
				]
			];


		}else{
			$mail = [
				"from_email" => SEND_MAIL_USUARIO,
				"from_name" => $msgcco,
				"subject" => $msgsub,
				"html" => $body,
				"tags"=> [
					$tagnombreevento."_7"
				],
				"headers" =>[

					"Reply-To" => $msgrep
				],
				"to" => [
					[
						"email" => $correosol,
						"type" => "to"
					]
				]
			];


		}
			
			
			if (filter_var($correosol, FILTER_VALIDATE_EMAIL)) {
				
				sendMail($mail);
			   // var_dump(sendMail($mail)); die;
			}
		}
		//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
		
		//Envio mail para ingresar el link de reunion videoconferencia
		//Busco los datos de la reunion
		$query = "	SELECT 	PD.PERCODIGO AS PERCODDST, PD.PERNOMBRE AS PERNOMDST, PD.PERAPELLI AS PERAPEDST, 
							PD.PERCOMPAN AS PERCOMDST, PD.PERCORREO AS PERCORDST,
							PS.PERCODIGO AS PERCODSOL, PS.PERNOMBRE AS PERNOMSOL, PS.PERAPELLI AS PERAPESOL, 
							PS.PERCOMPAN AS PERCOMSOL, PS.PERCORREO AS PERCORSOL,
							R.REUFECHA,R.REUHORA
					FROM REU_CABE R
					LEFT OUTER JOIN PER_MAEST PD ON PD.PERCODIGO=R.PERCODDST
					LEFT OUTER JOIN PER_MAEST PS ON PS.PERCODIGO=R.PERCODSOL 
					WHERE R.REUREG=$reureg ";
		$Table = sql_query($query,$conn,$trans);
		$row = $Table->Rows[0];
		$reufecha	= BDConvFch($row['REUFECHA']);
		$reuhora	= substr(trim($row['REUHORA']),0,5);
		
		$percoddst 	= trim($row['PERCODDST']);
		$pernomdst 	= trim($row['PERNOMDST']);
		$perapedst 	= trim($row['PERAPEDST']);
		$percomdst 	= trim($row['PERCOMDST']);
		$percordst 	= trim($row['PERCORDST']);
			
		$percodsol 	= trim($row['PERCODSOL']);
		$pernomsol 	= trim($row['PERNOMSOL']);
		$perapesol 	= trim($row['PERAPESOL']);
		$percomsol 	= trim($row['PERCOMSOL']);
		$percorsol 	= trim($row['PERCORSOL']);
		
		$parametro = md5('OnLiFeRCeOuNnCiRoEnTada').$reureg.md5('NnCiRoEnTadaOnLf');
		$parametro = base64_encode($parametro).md5('NnCiRoEnTadaOnLf');
		$parametro = trim(md5('OnLiFeeOuNnCiRTada').base64_encode($parametro));
					
		//http://......../videoconf.php?P=7a728b48fee4e58f87c47e4554747996WTJZd09UVmhNemMyWWpNell6VmpNalV4T0daak9ESTJZV1k0TkdZMFpETXhObUk1TURBNE56RmtZV0kxTmpJMVltUmxNbVpqWldNM05tVmtaVFkxTm1JeGI5MDA4NzFkYWI1NjI1YmRlMmZjZWM3NmVkZTY1NmIx
					
		//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
		//Envio Mail de Reunion concretada para coordinar informacion adicional al evento
		
		
		$body ='<!DOCTYPE html>
							<html lang="en" class="loading">
								<head>
									<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
								</head>
								<body>
								<div style="text-align:center">
								<img src=' . getUrl("/assets-nuevodisenio/img/mail.jpg") . ' alt="image.png" style="width:25%; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
									<br>
								</div>
								<div dir="ltr">
									<div style="text-align:center">
										<font color="#0091B2" style="font-family:arial,sans-serif;font-size:18px;">
											<div style="text-align:center">Meeting confirmed on '.$reufecha.' at '.$reuhora.'</div>
										</font>
									</div>
								</div>
								
								<div dir="ltr">
									<div style="text-align:center">
										<font color="#0091B2" style="font-family:arial,sans-serif;font-size:18px;">
											<div style="text-align:center">Profile 1: '.$pernomsol.' '.$perapesol.' from company '.$percomsol.' (email: '.$percorsol.')</div>
										</font>
									</div>
								</div>
								
								<div dir="ltr">
									<div style="text-align:center">
										<font color="#0091B2" style="font-family:arial,sans-serif;font-size:18px;">
											<div style="text-align:center">Profile 2: '.$pernomdst.' '.$perapedst.' from company '.$percomdst.' (email: '.$percordst.')</div>
										</font>
									</div>
								</div>							
								<br>
								<div dir="ltr">
									<div style="text-align:center">
										<a href="' . URL_WEB . '/videoconf.php?P='.$parametro.'"  target="_blank">Link to Videocall</a>
									</div>
								</body>
							</html>';
		
							$mail = [
								"from_email" => SEND_MAIL_USUARIO,
								"from_name" => MAIL_NAME_APP,
								"subject" => SUBJETC_ENVIARREUNION,
								"html" => $body,
								
								"to" => [
									[
										"email" => $SendMailReporteReuniones,
										"type" => "to"
									]
								]
							];
							
							if (filter_var($SendMailReporteReuniones, FILTER_VALIDATE_EMAIL)) {
								
								sendMail($mail);
							   // var_dump(sendMail($mail)); die;
							}
		

		}
		
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
