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
	$msgtipo5 = 'Meeting request registered.'; //Descripcion de confirmacion para solicitud enviada a un Tipo de Perfil 5
	$istipo5 = false;
	$err 	= 'SQLACCEPT';
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$percodigo 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : ''; //Codigo de perfil solicitante (logueado)
	$percoddst 	= (isset($_POST['percoddst']))? trim($_POST['percoddst']) : 0; //Codigo de perfil al que se solicita
	$tiporeunion 	= (isset($_POST['tiporeunion']))? trim($_POST['tiporeunion']) : 0; //Codigo de perfil al que se solicita
	$reuestado	= 1; //Reunion sin Confirmar
	$reureg		= 0;
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = VarNullBD($percodigo	, 'N');
	$percoddst = VarNullBD($percoddst	, 'N');
	$tiporeunion = VarNullBD($tiporeunion	, 'N');



	$query="SELECT MSGTITULO,MSGDESCRI, MSGSUB, MSGBOT, MSGLNK, MSGIMG FROM MSG_CABE WHERE MSGREG=6";
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
	$query = "	SELECT R.REUREG
				FROM REU_CABE R 
				WHERE R.PERCODSOL=$percodigo AND R.PERCODDST=$percoddst AND R.REUESTADO=1 ";
	$TableReu = sql_query($query,$conn);
	if($TableReu->Rows_Count>0){
		//$reureg = trim($TableReu->Rows[0]['REUREG']);
	}
	
	if($percoddst!=0){
		if($reureg==0){
			//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
			//Genero un ID 
			$query 		= 'SELECT GEN_ID(G_REUNIONES,1) AS ID FROM RDB$DATABASE';
			$TblId		= sql_query($query,$conn,$trans);
			$RowId		= $TblId->Rows[0];			
			$reureg 	= trim($RowId['ID']);
			//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

			
		
			//Inserto reunion cabecera
			$query = " 	INSERT INTO REU_CABE(REUREG,REUFCHREG,PERCODSOL,PERCODDST,REUESTADO,REUFECHA,REUHORA,MESCODIGO,REUTIPO)
						VALUES($reureg,CURRENT_TIMESTAMP,$percodigo,$percoddst,$reuestado,NULL,NULL,NULL,$tiporeunion) ";
			$err = sql_execute($query,$conn,$trans);

		
			switch($IdiomView){
				case 'ESP':
					$msgnoti = "Reunion solicitada";
					break;
					case 'POR':
						$msgnoti = "Reunião solicitada";
						break;
				case 'ING':
					$msgnoti = "Requested meeting";
					break;			
			}	

			
			//Inserto Notificacion de Solicitud
			$query = " INSERT INTO NOT_CABE (NOTREG, NOTFCHREG, NOTTITULO, NOTFCHLEI, PERCODDST, NOTESTADO, PERCODORI, REUREG,NOTCODIGO)
						VALUES (GEN_ID(G_NOTIFICACION,1), CURRENT_TIMESTAMP, '$msgnoti', NULL, $percoddst, 1, $percodigo, $reureg,1); ";
			$err = sql_execute($query,$conn,$trans);
			

			//Envio una notifiacion mobile
			//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
			//Busco si el destino tiene mobile
			$query = "	SELECT N.ID,N.PROVIDER 
						FROM NOT_REGI N 
						WHERE N.PERCODIGO=$percoddst ";
			$TableMobil = sql_query($query,$conn);
			if($TableMobil->Rows_Count>0){
				$id = trim($TableMobil->Rows[0]['ID']);
				$provider = trim($TableMobil->Rows[0]['PROVIDER']);
				
				//Busco los datos de la empresa que solicita la reunion
				$query = "	SELECT PERNOMBRE,PERAPELLI,PERCOMPAN
							FROM PER_MAEST
							WHERE PERCODIGO=$percodigo";
				$TableOrigen = sql_query($query,$conn);
				$pernombre = trim($TableOrigen->Rows[0]['PERNOMBRE']);
				$perapelli = trim($TableOrigen->Rows[0]['PERAPELLI']);
				$percompan = trim($TableOrigen->Rows[0]['PERCOMPAN']);
				
				$titulo 	= 'Reunion solicitada / Meeting request';
				$message 	= "$perapelli $pernombre de/from $percompan.";
				
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
			
			//Si el perfil de destino, es una 5-Comprador Ronda de Negocio, 
			//emito una notificacion al solicitante para informar que la agencia se contactara
			$correodst = '';
			$queryDst = "	SELECT P.PERTIPO,P.PERCORREO,P.PERCOMPAN,P.PERNOMBRE,P.PERAPELLI
							FROM PER_MAEST P 
							WHERE P.PERCODIGO=$percoddst ";
			$TableDst = sql_query($queryDst,$conn);
			if($TableDst->Rows_Count>0){
				$pertipo = trim($TableDst->Rows[0]['PERTIPO']);
				$correodst = trim($TableDst->Rows[0]['PERCORREO']);
				$empresadst = trim($TableDst->Rows[0]['PERCOMPAN']);
				$nombredst = trim($TableDst->Rows[0]['PERNOMBRE']);
				$apellidst = trim($TableDst->Rows[0]['PERAPELLI']);
				
				if($pertipo==5){ //Comprador Ronda de Negocio
					$istipo5 = true;
				}
			}
			
			//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
			//Envio Mail de Reunion Solicitada al Destino
		
			if($correodst!=''){
				
				
				$percompan = $_SESSION[GLBAPPPORT.'PERCOMPAN'];
				$pernombre = $_SESSION[GLBAPPPORT.'PERNOMBRE'];
				$perapelli = $_SESSION[GLBAPPPORT.'PERAPELLI'];
				$msgdescriaux = str_replace("*|Nombre Contraparte|*",$pernombre.' '.$perapelli,$msgdescri);
				$msgdescriaux = str_replace("*|Empresa Contraparte|*",$percompan,$msgdescriaux);
				$msgdescriaux = str_replace("*|Nombre Usuario|*",$nombredst.' '.$apellidst,$msgdescriaux);
				$msgdescriaux = str_replace("*|Empresa Usuario|*",$empresadst,$msgdescriaux);
				$msgdescriaux = str_replace("*|Correo Usuario|*",$correodst,$msgdescriaux);
				
				$body ='<!DOCTYPE html>
				<html lang="en" class="loading">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					</head>
			
				<body>
				<div style="text-align:center">
					<img src=' . getUrl("/mailsimg/6/$msgimg") . ' alt="image.png" style="max-width: 800px; height:auto; margin-right:0px" data-image-whitelisted="" class="CToWUd">
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
						$tagnombreevento."_6"
					],
					"headers" =>[
	
						"Reply-To" => $msgrep
					],
					"to" => [
						[
							"email" => $correodst,
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
						$tagnombreevento."_6"
					],
					"headers" =>[
	
						"Reply-To" => $msgrep
					],
					"to" => [
						[
							"email" => $correodst,
							"type" => "to"
						]
					]
				];


			}
				
				
				if (filter_var($correodst, FILTER_VALIDATE_EMAIL)) {
					
					sendMail($mail);
				   // var_dump(sendMail($mail)); die;
				}

				
			}
			//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
		}
		

	

		//Insertando horarios solicitados para la reunion
		if(isset($_POST['dataCoordinar'])){
			$query=" DELETE FROM REU_SOLI WHERE REUREG=$reureg ";
			$err = sql_execute($query,$conn,$trans);
		
			foreach($_POST['dataCoordinar'] as $ind => $data){
				$fecha 	= $data['fecha'];
				$hora 	= $data['hora'];
	
				$reufecha 	= ConvFechaBD($fecha);
				$reuhora 	= VarNullBD($hora  , 'S');

				// BUSCO SI LA REUNION ES PRESENCIAL
			if($tiporeunion == 1){  
				$buscoflotante = 0;
				/// BUSCO MESA FIJA
				// BUSCO LA MESA DEL RECEPTOR 
				$querymesa = " 	SELECT MESCODIGO 
				FROM MES_MAEST 
				WHERE PERCODIGO=$percoddst AND ESTCODIGO<>3";
	
				$TableMesa = sql_query($querymesa,$conn);
				if($TableMesa->Rows_Count>0){
					$rowmesa = $TableMesa->Rows[0];
					$mesnumero 	= trim($rowmesa['MESCODIGO']);
				}else{
	
				// BUSCO MESA SOLICITANTE	
				$querymesa2 = " 	SELECT MESCODIGO 
				FROM MES_MAEST 
				WHERE PERCODIGO=$percodigo AND ESTCODIGO<>3";
	
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

				if($errcod != 3){
					$queryupdate = "	UPDATE REU_CABE SET
					MESCODIGO=$mesnumero
					WHERE REUREG=$reureg ";
					
					$err = sql_execute($queryupdate,$conn,$trans);
				}
				

			}
				
				$query = "	INSERT INTO REU_SOLI(REUREG,REUFECHA,REUHORA,REUESTADO)
							VALUES($reureg,$reufecha,$reuhora,$reuestado)";
				$err = sql_execute($query,$conn,$trans);

			
		

		}

	}

}
		
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		if(!$istipo5){
			$errmsg = 'Meeting requested!'; 
		}else{
			$errmsg = $msgtipo5; //Mensaje de solicitud enviada a un Perfil Tipo 5
		}
		     
	}else{            
		sql_rollback_trans($trans);

		if($errcod == 3){
			$errcod = 2;
			$errmsg = ($errmsg=='')? 'Not available meeting table!' : $errmsg;
		}else{
			$errcod = 2;
			$errmsg = ($errmsg=='')? 'Requesting meeting error!' : $errmsg;
		}

		
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
