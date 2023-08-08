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
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$msgtipo5 = 'Meeting registered'; //Descripcion de confirmacion para solicitud enviada a un Tipo de Perfil 5
	$istipo5 = false;
	$err 	= 'SQLACCEPT';
	
			//$urlweb = 'http://localhost/webcoordinador/'; //DEV
			$urlweb 			= 'https://virtualexpo.atlanticfoodexport.eu/'; //PRD
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$percodigo 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : ''; //Codigo de perfil solicitante (logueado)
	$percoddst 	= (isset($_POST['percoddst']))? trim($_POST['percoddst']) : 0; //Codigo de perfil al que se solicita
	$reuestado	= 1; //Reunion sin Confirmar
	$reureg		= 0;
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = VarNullBD($percodigo	, 'N');
	$percoddst = VarNullBD($percoddst	, 'N');
	
	//Busco si existe una reunion soliciada y sin confirmar
	$query = "	SELECT R.REUREG
				FROM REU_CABE R 
				WHERE R.PERCODSOL=$percodigo AND R.PERCODDST=$percoddst AND R.REUESTADO=1 ";
	$TableReu = sql_query($query,$conn);
	if($TableReu->Rows_Count>0){
		$reureg = trim($TableReu->Rows[0]['REUREG']);
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
			$query = " 	INSERT INTO REU_CABE(REUREG,REUFCHREG,PERCODSOL,PERCODDST,REUESTADO,REUFECHA,REUHORA,MESCODIGO)
						VALUES($reureg,CURRENT_TIMESTAMP,$percodigo,$percoddst,$reuestado,NULL,NULL,NULL) ";
			$err = sql_execute($query,$conn,$trans);
			
			
			//Inserto Notificacion de Solicitud
			$query = " INSERT INTO NOT_CABE (NOTREG, NOTFCHREG, NOTTITULO, NOTFCHLEI, PERCODDST, NOTESTADO, PERCODORI, REUREG,NOTCODIGO)
						VALUES (GEN_ID(G_NOTIFICACION,1), CURRENT_TIMESTAMP, 'Reunion solicitada', NULL, $percoddst, 1, $percodigo, $reureg,1); ";
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
			$queryDst = "	SELECT P.PERTIPO,P.PERCORREO
							FROM PER_MAEST P 
							WHERE P.PERCODIGO=$percoddst ";
			$TableDst = sql_query($queryDst,$conn);
			if($TableDst->Rows_Count>0){
				$pertipo = trim($TableDst->Rows[0]['PERTIPO']);
				$correodst = trim($TableDst->Rows[0]['PERCORREO']);
				
				if($pertipo==5){ //Comprador Ronda de Negocio
					$istipo5 = true;
				}
			}
			
			//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
			//Envio Mail de Reunion Solicitada al Destino
			if($correodst!=''){
				$mail = new PHPMailer();	
				$mail->IsSMTP();
				$mail->SMTPAuth 	= true; 	
				
				$mail->SMTPSecure = 'tls';
				$mail->Host = 'smtp.gmail.com';
				$mail->Port = 587;
				
				$mail->Username 	= "expo@atlanticfoodexport.eu";
				$mail->Password 	= "iQ9hJwGcd";	
				//$mail->Username 	= "international@exponenciar.com.ar";
				//$mail->Password 	= "Ingreso2019";
				
				$mail->FromName 	= 'ATLANTIC FOOD EXPORT';
				$mail->From			= "expo@atlanticfoodexport.eu";
				$mail->AddReplyTo('expo@atlanticfoodexport.eu', 'ATLANTIC FOOD EXPORT');
				
				$mail->IsHTML(true);    // set email format to HTML	
				
				$mail->AddAddress($correodst, '');
				$mail->Subject = 'You have a meeting request in the ATLANTIC FOOD EXPORT VIRTUAL EXPO.';
				$mail->AddEmbeddedImage('../imagenes-evento/logo.png', 'imglogo'); //cid:imglogo
				
				$percompan = $_SESSION[GLBAPPPORT.'PERCOMPAN'];
				
				$mail->Body ='<!DOCTYPE html>
								<html lang="en" class="loading">
									<head>
										<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
									</head>

								<body>
								<div style="text-align:center">
									<img src="cid:imglogo" alt="image.png" style="margin-right:0px" data-image-whitelisted="" class="CToWUd">
									<!--app-assets/img/logo-light.png
									-->
									<br>
								</div>
								<div dir="ltr">
									<div style="text-align:center">
										
										<br>
										<font color="#da5060" style="font-family:arial,sans-serif;font-size:18px;">
											<div style="text-align:center">The company '.$percompan.' has requested a meeting with you at the ATLANTIC FOOD EXPORT VIRTUAL EXPO. To accept or decline this request, log in the platform by clicking below: <a href="'.$urlweb.'reuniones/bsq?T=2">Aca / Here</a></div>
										</font>
									</div>';
				$mail->Send();
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
			$errmsg = 'Reunion solicitada!'; 
		}else{
			$errmsg = $msgtipo5; //Mensaje de solicitud enviada a un Perfil Tipo 5
		}
		     
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al soliciar la reunion!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
